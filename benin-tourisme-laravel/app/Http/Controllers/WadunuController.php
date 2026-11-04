<?php

namespace App\Http\Controllers;

use App\Filament\Admin\Pages\WadunuCatalogPage;
use App\Mail\WadunuMagicLink;
use App\Models\Restaurant;
use App\Models\RestoAccessToken;
use App\Models\RestoOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
class WadunuController extends Controller
{
    /**
     * Variables communes attendues par le layout Blade principal.
     */
    private function pageDefaults(string $title, string $description = ''): array
    {
        return [
            'title'         => $title,
            'description'   => $description ?: 'Wa Dùnù — le carnet d\'adresses gourmandes du Bénin, édité par Dahomey Discovery.',
            'activeMenu'    => null,
            'headProfile'   => 'standard',
            'customContent' => null,
        ];
    }

    /**
     * POST /restaurants/order
     * Cree une commande "pending" et retourne les infos pour le widget KKiaPay.
     */
    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'email'      => 'required|email|max:120',
            'nom_client' => 'nullable|string|max:120',
        ]);

        $amount = config('services.kkiapay.catalog_amount_xof', 15000);

        $order = RestoOrder::create([
            'email'      => $data['email'],
            'nom_client' => $data['nom_client'] ?? null,
            'amount_xof' => $amount,
            'status'     => 'pending',
        ]);

        return response()->json([
            'order_id'   => $order->id,
            'amount'     => $amount,
            'email'      => $order->email,
            'name'       => $order->nom_client,
            'public_key' => config('services.kkiapay.public_key'),
            'sandbox'    => config('services.kkiapay.sandbox', true),
            'callback'   => route('wadunu.paid.callback', $order),
        ]);
    }

    /**
     * GET /restaurants/paid/{order}?transaction_id=xxx
     * Callback JS du widget KKiaPay : verifie la transaction cote serveur
     * via l'API KKiaPay, marque la commande "paid" et envoie le magic link.
     */
    /**
     * Validation d'un paiement KKiaPay, appelée de deux façons :
     *  - en AJAX (POST) par la page, à l'événement « success » du widget ;
     *  - en redirection (GET) par KKiaPay vers l'URL `callback` passée au widget.
     * Les deux peuvent arriver presque en même temps : le traitement est donc
     * idempotent (verrou sur la commande, une seule émission de lien).
     *
     * Contrôles : transaction vérifiée côté serveur auprès de KKiaPay, statut
     * réussi, montant payé >= montant de la commande, transaction jamais
     * utilisée pour une autre commande, commande pas déjà payée par une autre
     * transaction.
     */
    public function paidCallback(Request $request, RestoOrder $order)
    {
        $transactionId = trim((string) ($request->query('transaction_id') ?? $request->input('transaction_id', '')));
        if ($transactionId === '' || strlen($transactionId) > 100 || ! preg_match('/^[A-Za-z0-9_.:-]+$/', $transactionId)) {
            return $this->paymentResult($request, false, 'no_transaction_id', 400);
        }

        // Déjà payée : même transaction -> succès sans nouveau lien ; autre
        // transaction -> refus.
        if ($order->status === 'paid') {
            return $order->kkiapay_transaction_id === $transactionId
                ? $this->paymentResult($request, true, null, 200, $order)
                : $this->paymentResult($request, false, 'already_paid', 409);
        }

        if (RestoOrder::query()->where('kkiapay_transaction_id', $transactionId)->whereKeyNot($order->getKey())->exists()) {
            \Log::warning('Wa Dunu : transaction KKiaPay deja utilisee', ['order' => $order->id, 'transaction' => $transactionId]);

            return $this->paymentResult($request, false, 'transaction_already_used', 409);
        }

        // Verification serveur via KKiaPay
        $verified = $this->verifyKkiapayTransaction($transactionId);
        if (! $verified['ok']) {
            $order->update(['status' => 'failed', 'kkiapay_payload' => $verified['payload']]);

            return $this->paymentResult($request, false, 'verification_failed', 402);
        }

        $paidAmount = (int) round((float) ($verified['payload']['amount'] ?? 0));
        if ($paidAmount < (int) $order->amount_xof) {
            \Log::warning('Wa Dunu : montant paye insuffisant', ['order' => $order->id, 'paid' => $paidAmount, 'expected' => $order->amount_xof]);
            $order->update(['status' => 'failed', 'kkiapay_payload' => $verified['payload']]);

            return $this->paymentResult($request, false, 'amount_mismatch', 402);
        }

        $token = \Illuminate\Support\Facades\DB::transaction(function () use ($order, $transactionId, $verified) {
            /** @var RestoOrder $locked */
            $locked = RestoOrder::query()->lockForUpdate()->findOrFail($order->getKey());
            if ($locked->status === 'paid') {
                return null; // traitée entre-temps par l'autre appel
            }
            $locked->kkiapay_transaction_id = $transactionId;
            $locked->markPaid($verified['payload']);

            return RestoAccessToken::issueFor($locked, 30);
        });

        $order->refresh();
        if ($token === null) {
            return $order->kkiapay_transaction_id === $transactionId
                ? $this->paymentResult($request, true, null, 200, $order)
                : $this->paymentResult($request, false, 'already_paid', 409);
        }

        // Envoi immediat ; la file d'attente ne sert que de secours (sans
        // worker actif, un mail mis en file ne partirait jamais).
        try {
            Mail::to($order->email)->send(new WadunuMagicLink($token));
        } catch (\Throwable $e) {
            \Log::warning('Wa Dunu : envoi direct du lien impossible, mise en file', ['order' => $order->id, 'error' => $e->getMessage()]);
            Mail::to($order->email)->queue(new WadunuMagicLink($token));
        }

        return $this->paymentResult($request, true, null, 200, $order, $token);
    }

    /**
     * Réponse commune : JSON pour l'appel AJAX de la page, redirection vers la
     * page restaurants pour le retour navigateur depuis KKiaPay.
     */
    private function paymentResult(Request $request, bool $ok, ?string $error, int $status, ?RestoOrder $order = null, ?RestoAccessToken $token = null)
    {
        $token ??= $ok && $order ? $order->accessTokens()->latest('id')->first() : null;
        $url = $token ? route('wadunu.catalog', $token->token) : null;

        if ($request->isMethod('get') && ! $request->expectsJson()) {
            return $url
                ? redirect()->to($url)
                : redirect()->to('/sejourner/restaurants?paiement='.($ok ? 'valide' : 'echec'));
        }

        return response()->json($ok
            ? ['ok' => true, 'message' => 'Paiement validé, lien envoyé par email.', 'url' => $url]
            : ['ok' => false, 'error' => $error], $status);
    }

    public function catalog(Request $request, string $token)
    {
        $access = $this->resolveValidToken($token);
        if (!$access) {
            return response()->view('pages.wadunu.expired', $this->pageDefaults('Accès expiré · Wa Dùnù'), 403);
        }
        $access->markSeen();

        if ($access->expires_at && now()->diffInDays($access->expires_at, false) < 5 && $access->expires_at->isFuture()) {
            $access->renew(30);
        }

        // Source du PDF : prioritaire Nextcloud (prod), sinon disque local (dev)
        $pdfExists = $this->pdfIsAvailable();
        $pdfUpdatedAt = null;
        $pdfSizeBytes = 0;
        if ($pdfExists && !config('services.wadunu.pdf_url')) {
            $disk = Storage::disk('wadunu');
            $pdfUpdatedAt = \Carbon\Carbon::createFromTimestamp($disk->lastModified(WadunuCatalogPage::FILENAME));
            $pdfSizeBytes = $disk->size(WadunuCatalogPage::FILENAME);
        }

        return view('pages.wadunu.viewer', array_merge($this->pageDefaults('Mon catalogue Wa Dùnù'), [
            'token'         => $token,
            'access'        => $access,
            'pdfExists'     => $pdfExists,
            'pdfUpdatedAt'  => $pdfUpdatedAt,
            'pdfSizeBytes'  => $pdfSizeBytes,
            'expiresAt'     => $access->order->access_expires_at,
        ]));
    }

    /**
     * GET /mes-restos/{token}/pdf
     * Sert le PDF, protege par le token. Deux sources possibles :
     *  1) Nextcloud (prod) : proxy serveur avec auth du lien partage prive.
     *     Le mot de passe reste cote serveur, l'abonne ne le voit jamais.
     *  2) Disque local (dev) : stream du fichier `wadunu/catalogue.pdf`.
     */
    public function catalogPdf(Request $request, string $token)
    {
        $access = $this->resolveValidToken($token);
        if (!$access) {
            abort(403, 'Lien expire.');
        }
        $access->markSeen();

        $disposition = $request->query('download') ? 'attachment' : 'inline';
        $disposition .= '; filename="Wa-Dunu-catalogue.pdf"';
        $sharedHeaders = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition,
            'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ];

        // Priorite Nextcloud si configure
        $ncUrl = config('services.wadunu.pdf_url');
        $ncPwd = config('services.wadunu.pdf_password');
        if ($ncUrl) {
            try {
                $response = Http::withBasicAuth('', (string) $ncPwd)
                    ->timeout(120)
                    ->withOptions(['stream' => true])
                    ->get(rtrim($ncUrl, '/') . '/download');
                if (!$response->successful()) {
                    abort(502, 'Catalogue momentanement indisponible.');
                }
                // Diffusion par blocs : le PDF (plus de 100 Mo) n'est jamais
                // charge entierement en memoire, contrairement a ->body().
                $stream = $response->toPsrResponse()->getBody();
                $length = $response->header('Content-Length');
                if ($length !== '') {
                    $sharedHeaders['Content-Length'] = $length;
                }
                return response()->stream(function () use ($stream): void {
                    while (! $stream->eof()) {
                        echo $stream->read(1024 * 256);
                        flush();
                    }
                }, 200, $sharedHeaders);
            } catch (\Throwable $e) {
                \Log::warning('Wadunu PDF proxy error: ' . $e->getMessage());
                // Fallback vers le disque local
            }
        }

        // Fallback : disque local (dev ou absence de config Nextcloud)
        $disk = Storage::disk('wadunu');
        $filename = WadunuCatalogPage::FILENAME;
        if (!$disk->exists($filename)) {
            abort(404, 'Aucun catalogue publie pour le moment.');
        }
        return response()->file($disk->path($filename), $sharedHeaders);
    }

    /**
     * Verifie que le PDF est disponible (Nextcloud ou local).
     */
    private function pdfIsAvailable(): bool
    {
        if (config('services.wadunu.pdf_url')) {
            return true;
        }
        return Storage::disk('wadunu')->exists(WadunuCatalogPage::FILENAME);
    }

    /**
     * Retourne le RestoAccessToken si le token est valide et actif, sinon null.
     */
    private function resolveValidToken(string $token): ?RestoAccessToken
    {
        $access = RestoAccessToken::where('token', $token)->with('order')->first();
        return ($access && $access->isValid()) ? $access : null;
    }

    /**
     * Verifie une transaction KKiaPay via l'API serveur.
     */
    private function verifyKkiapayTransaction(string $transactionId): array
    {
        $endpoint = config('services.kkiapay.sandbox')
            ? 'https://api-sandbox.kkiapay.me/api/v1/transactions/status'
            : 'https://api.kkiapay.me/api/v1/transactions/status';

        try {
            $response = Http::withHeaders([
                'x-api-key' => config('services.kkiapay.public_key'),
                'x-secret-key' => config('services.kkiapay.secret'),
                'x-private-key' => config('services.kkiapay.private_key'),
            ])->timeout(15)->post($endpoint, [
                'transactionId' => $transactionId,
            ]);

            if (!$response->ok()) {
                return ['ok' => false, 'payload' => ['http_status' => $response->status()]];
            }

            $data = $response->json();
            $status = strtoupper($data['status'] ?? '');
            return [
                'ok'      => in_array($status, ['SUCCESS', 'SUCCESSFUL', 'COMPLETED']),
                'payload' => $data,
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'payload' => ['error' => $e->getMessage()]];
        }
    }
}
