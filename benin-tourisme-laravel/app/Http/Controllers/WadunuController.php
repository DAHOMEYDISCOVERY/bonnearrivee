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
    public function paidCallback(Request $request, RestoOrder $order)
    {
        $transactionId = $request->query('transaction_id');
        if (!$transactionId) {
            return response()->json(['ok' => false, 'error' => 'no_transaction_id'], 400);
        }

        // Verification serveur via KKiaPay
        $verified = $this->verifyKkiapayTransaction($transactionId);
        if (!$verified['ok']) {
            $order->update(['status' => 'failed']);
            return response()->json(['ok' => false, 'error' => 'verification_failed'], 402);
        }

        $order->kkiapay_transaction_id = $transactionId;
        $order->markPaid($verified['payload']);

        $token = RestoAccessToken::issueFor($order, 30);

        Mail::to($order->email)->queue(new WadunuMagicLink($token));

        return response()->json([
            'ok'      => true,
            'message' => 'Paiement validé, lien envoyé par email.',
        ]);
    }

    /**
     * GET /mes-restos/{token}
     * Verifie le token et affiche le viewer PDF embarque.
     * Le PDF est heberge sur Nextcloud (lien de partage protege par mot de
     * passe). L'admin met a jour le PDF directement sur Nextcloud, tous
     * les abonnes voient la nouvelle version au prochain refresh.
     */
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
                $body = $response->body();
                $sharedHeaders['Content-Length'] = (string) strlen($body);
                return response($body, 200, $sharedHeaders);
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
