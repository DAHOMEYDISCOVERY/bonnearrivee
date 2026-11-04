<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RestoAccessToken;
use App\Models\RestoOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Validation des paiements Wa Dùnù : montant, transaction unique, idempotence.
 * KKiaPay est simulé : aucun appel réseau, aucun paiement réel.
 */
final class WadunuPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        config(['services.kkiapay.sandbox' => true]);
    }

    private function order(int $amount = 15000): RestoOrder
    {
        return RestoOrder::query()->create([
            'email' => 'client@example.test',
            'nom_client' => 'Client',
            'amount_xof' => $amount,
            'status' => 'pending',
        ]);
    }

    private function kkiapayAnswers(string $status, int $amount): void
    {
        Http::fake(['*kkiapay.me/*' => Http::response(['status' => $status, 'amount' => $amount, 'transactionId' => 'TX'])]);
    }

    public function test_un_paiement_complet_valide_la_commande_et_envoie_le_lien(): void
    {
        $this->kkiapayAnswers('SUCCESS', 15000);
        $order = $this->order();

        $this->postJson("/restaurants/paid/{$order->id}?transaction_id=TX-1")
            ->assertOk()->assertJson(['ok' => true])->assertJsonStructure(['url']);

        $this->assertSame('paid', $order->fresh()->status);
        $this->assertSame(1, RestoAccessToken::query()->count());
        Mail::assertSent(\App\Mail\WadunuMagicLink::class, 1);
    }

    public function test_un_montant_insuffisant_est_refuse(): void
    {
        $this->kkiapayAnswers('SUCCESS', 100);
        $order = $this->order();

        $this->postJson("/restaurants/paid/{$order->id}?transaction_id=TX-2")->assertStatus(402)
            ->assertJson(['ok' => false, 'error' => 'amount_mismatch']);

        $this->assertNotSame('paid', $order->fresh()->status);
        $this->assertSame(0, RestoAccessToken::query()->count());
    }

    public function test_une_transaction_ne_peut_pas_debloquer_deux_commandes(): void
    {
        $this->kkiapayAnswers('SUCCESS', 15000);
        $first = $this->order();
        $second = $this->order();

        $this->postJson("/restaurants/paid/{$first->id}?transaction_id=TX-3")->assertOk();
        $this->postJson("/restaurants/paid/{$second->id}?transaction_id=TX-3")->assertStatus(409)
            ->assertJson(['error' => 'transaction_already_used']);

        $this->assertSame('pending', $second->fresh()->status);
    }

    public function test_un_double_appel_n_emet_qu_un_seul_lien(): void
    {
        $this->kkiapayAnswers('SUCCESS', 15000);
        $order = $this->order();

        $this->postJson("/restaurants/paid/{$order->id}?transaction_id=TX-4")->assertOk();
        $this->postJson("/restaurants/paid/{$order->id}?transaction_id=TX-4")->assertOk()->assertJson(['ok' => true]);

        $this->assertSame(1, RestoAccessToken::query()->count());
        Mail::assertSent(\App\Mail\WadunuMagicLink::class, 1);
    }

    public function test_le_retour_navigateur_de_kkiapay_ouvre_le_catalogue(): void
    {
        $this->kkiapayAnswers('SUCCESS', 15000);
        $order = $this->order();

        $response = $this->get("/restaurants/paid/{$order->id}?transaction_id=TX-5");

        $token = RestoAccessToken::query()->firstOrFail();
        $response->assertRedirect(route('wadunu.catalog', $token->token));
    }

    public function test_un_paiement_refuse_par_kkiapay_ne_debloque_rien(): void
    {
        $this->kkiapayAnswers('FAILED', 15000);
        $order = $this->order();

        $this->get("/restaurants/paid/{$order->id}?transaction_id=TX-6")
            ->assertRedirect('/sejourner/restaurants?paiement=echec');

        $this->assertSame(0, RestoAccessToken::query()->count());
    }
}
