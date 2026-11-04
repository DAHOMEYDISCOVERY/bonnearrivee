<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\AdminIdleTimeout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Accès au back-office : page de connexion en français, aucun accès sans
 * compte, session fermée après inactivité, en-têtes de sécurité.
 */
final class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    private function editor(string $role = User::ROLE_EDITOR): User
    {
        return User::query()->create([
            'name' => 'Éditrice',
            'email' => 'editrice-'.$role.'@example.test',
            'password' => bcrypt('secret-test'),
            'role' => $role,
        ]);
    }

    public function test_un_visiteur_est_renvoye_vers_la_connexion(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/pages')->assertRedirect('/admin/login');
    }

    public function test_la_page_de_connexion_est_en_francais_et_sans_cles_brutes(): void
    {
        config(['app.locale' => 'fr_FR', 'app.fallback_locale' => 'fr_FR']);

        $html = $this->get('/admin/login')->assertOk()->getContent();

        $this->assertStringContainsString('Espace d’administration', $html);
        $this->assertStringContainsString('Adresse e-mail', $html);
        $this->assertStringContainsString('Mot de passe', $html);
        $this->assertStringContainsString('Retour au site', $html);
        $this->assertStringNotContainsString('filament-panels::', $html);
    }

    public function test_les_pages_d_administration_ne_sont_ni_indexees_ni_encadrables(): void
    {
        $response = $this->get('/admin/login');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_un_editeur_connecte_accede_au_tableau_de_bord(): void
    {
        $this->actingAs($this->editor())->get('/admin')->assertOk();
    }

    public function test_un_compte_sans_role_est_refuse(): void
    {
        $this->actingAs($this->editor('inconnu'))->get('/admin')->assertForbidden();
    }

    public function test_la_session_se_ferme_apres_l_inactivite(): void
    {
        config(['admin.idle_minutes' => 60]);
        $user = $this->editor();

        $this->actingAs($user)
            ->withSession([AdminIdleTimeout::SESSION_KEY => time() - 61 * 60])
            ->get('/admin')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_une_activite_recente_garde_la_session(): void
    {
        config(['admin.idle_minutes' => 60]);

        $this->actingAs($this->editor())
            ->withSession([AdminIdleTimeout::SESSION_KEY => time() - 5 * 60])
            ->get('/admin')
            ->assertOk();

        $this->assertAuthenticated();
    }
}
