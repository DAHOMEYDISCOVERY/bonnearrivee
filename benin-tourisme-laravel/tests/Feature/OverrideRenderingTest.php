<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Non-régression des corrections du 4 novembre 2026 (audit) :
 *  - une page au contenu personnalisé ne sert plus jamais de balises <x-…> brutes ;
 *  - une copie identique à la vue d'origine est servie par la vue elle-même ;
 *  - toutes les pages définissent window.globals ;
 *  - les CSS/JS du projet ont une seule version, calculée.
 */
final class OverrideRenderingTest extends TestCase
{
    use RefreshDatabase;

    private const PATH = '/explorez/terre-de-traditions/le-vodun';
    private const VIEW = 'pages.explorez.terre-de-traditions.le-vodun.index';

    protected function setUp(): void
    {
        parent::setUp();
        // Les blocs agrégateur appellent des API WordPress externes : on les
        // neutralise pour que le test ne dépende pas du réseau.
        Http::fake();
    }

    private function pageWithOverride(string $content): void
    {
        Page::query()->create([
            'path' => self::PATH,
            'view' => self::VIEW,
            'title' => 'Le vodun | Bonne Arrivée',
            'description' => 'Test',
            'head_profile' => 'standard',
            'standalone' => false,
            'use_override' => true,
            'wrap_content' => false,
            'content_override' => $content,
        ]);
    }

    public function test_une_copie_identique_a_la_vue_est_servie_par_la_vue_d_origine(): void
    {
        $this->pageWithOverride((string) Page::extractContentFromView(self::VIEW));

        $response = $this->get(self::PATH);

        $response->assertOk();
        $response->assertDontSee('<x-', false);
        // Classe du <main> de la vue d'origine, et non celle de editable-raw.
        $response->assertSee('class="content-type-page page-hub"', false);
    }

    public function test_un_contenu_modifie_garde_ses_modifications_et_compile_ses_composants(): void
    {
        $content = (string) Page::extractContentFromView(self::VIEW);
        $this->pageWithOverride($content."\n<p>Paragraphe ajouté dans l'admin</p>");

        $response = $this->get(self::PATH);

        $response->assertOk();
        $response->assertSee("Paragraphe ajouté dans l'admin", false);
        $response->assertDontSee('<x-media.figure', false);
        $response->assertDontSee('<x-card', false);
        // L'image d'en-tête du composant <x-media.figure> est bien rendue.
        $response->assertSee('/assets/img/vodun-1920x480.webp', false);
    }

    public function test_les_pages_hors_gabarit_definissent_window_globals(): void
    {
        foreach (['/quand-partir', '/agenda', '/checklist', '/convertisseur', '/timeline-benin', '/guide-conversation'] as $path) {
            $this->get($path)->assertOk()->assertSee('window.globals=', false);
        }
    }

    public function test_les_assets_du_projet_ont_une_version_calculee(): void
    {
        $html = $this->get('/quand-partir')->assertOk()->getContent();

        $this->assertStringNotContainsString('benin-overrides.min.css?v=events', $html);
        $this->assertMatchesRegularExpression('#/assets/benin-overrides\.min\.css\?v=\d+#', $html);
    }

    public function test_la_feuille_servie_est_identique_a_la_source(): void
    {
        $this->assertFileEquals(
            public_path('assets/benin-overrides.css'),
            public_path('assets/benin-overrides.min.css'),
            'benin-overrides.min.css ne doit jamais être édité à la main : lancer tools/minify-css.sh',
        );
    }

    public function test_benin_carrousels_n_appelle_plus_de_fonction_supprimee(): void
    {
        $js = (string) file_get_contents(public_path('assets/benin-carrousels.js'));

        $this->assertStringNotContainsString('rendreRégionsContinu(', $js);
        $this->assertStringNotContainsString('querySelector(RÉGIONS)', $js);
    }
}
