<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Admin\Resources\PageResource\Pages\EditPage;
use App\Models\Page;
use App\Models\User;
use App\Support\PublishGuard;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Garde-fou de publication : une modification du back-office qui casserait
 * une page n'est jamais publiée ; le site garde la version précédente.
 */
final class PublishGuardTest extends TestCase
{
    use RefreshDatabase;

    private const PATH = '/explorez/terre-de-traditions/le-vodun';

    private const VIEW = 'pages.explorez.terre-de-traditions.le-vodun.index';

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    private function page(bool $override, ?string $content): Page
    {
        return Page::query()->create([
            'path' => self::PATH,
            'view' => self::VIEW,
            'title' => 'Le vodun | Bonne Arrivée',
            'description' => 'Test',
            'head_profile' => 'standard',
            'standalone' => false,
            'use_override' => $override,
            'wrap_content' => false,
            'content_override' => $content,
        ]);
    }

    public function test_une_page_saine_passe_le_controle(): void
    {
        $this->assertNull(PublishGuard::problem('/quand-partir'));
        $this->assertNull(PublishGuard::problem('/'));
    }

    public function test_un_bloc_casse_est_signale(): void
    {
        $content = (string) Page::extractContentFromView(self::VIEW);
        $this->page(true, $content."\n<x-composant-qui-n-existe-pas />");

        $problem = PublishGuard::problem(self::PATH);

        $this->assertNotNull($problem);
        $this->assertStringContainsString(self::PATH, $problem);
    }

    public function test_un_enregistrement_qui_casserait_la_page_n_est_pas_publie(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $editor = User::query()->create([
            'name' => 'Éditrice',
            'email' => 'editrice@example.test',
            'password' => bcrypt('secret-test'),
            'role' => User::ROLE_EDITOR,
        ]);
        $page = $this->page(false, null);
        $content = (string) Page::extractContentFromView(self::VIEW);

        Livewire::actingAs($editor)
            ->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'use_override' => true,
                'content_override' => $content."\n<x-composant-qui-n-existe-pas />",
            ])
            ->call('save');

        $page->refresh();
        $this->assertFalse((bool) $page->use_override, 'La version cassée ne doit pas être publiée.');
        $this->get(self::PATH)->assertOk()->assertDontSee('<x-', false);
    }

    public function test_un_enregistrement_correct_est_publie(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $editor = User::query()->create([
            'name' => 'Éditrice',
            'email' => 'editrice@example.test',
            'password' => bcrypt('secret-test'),
            'role' => User::ROLE_EDITOR,
        ]);
        $page = $this->page(false, null);
        $content = (string) Page::extractContentFromView(self::VIEW);

        Livewire::actingAs($editor)
            ->test(EditPage::class, ['record' => $page->getRouteKey()])
            ->fillForm([
                'use_override' => true,
                'content_override' => $content."\n<p>Ajout validé par le garde-fou</p>",
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue((bool) $page->refresh()->use_override);
        $this->get(self::PATH)->assertOk()->assertSee('Ajout validé par le garde-fou', false);
    }

    public function test_la_commande_de_controle_passe_sur_le_site(): void
    {
        $this->artisan('site:verifier')->assertExitCode(0);
    }
}
