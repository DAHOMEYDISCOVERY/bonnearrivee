<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\SitePages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Garde-fou de l'audit (section J) : sur TOUTES les pages publiques — registre,
 * pages construites en code, fiches événements — le HTML servi ne contient
 * aucun reste de Blade non compilé (cause des 76 pages figées de BUG-01) et
 * définit window.globals quand il charge les scripts du thème (BUG-06).
 */
final class RenderedHtmlTest extends TestCase
{
    use RefreshDatabase;

    private const BLADE_LEFTOVERS = ['<x-', '{{ ', ' }}', '{!!', '@endverbatim', '@verbatim', '@section(', '@yield('];

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    public function test_aucune_page_ne_montre_de_blade_non_compile(): void
    {
        $pages = SitePages::all();
        $this->assertGreaterThan(95, count($pages), 'La liste des pages doit couvrir registre, pages spéciales et événements.');

        $failures = [];
        foreach ($pages as $page) {
            $response = $this->get($page['path']);
            if ($response->status() !== 200) {
                $failures[] = "{$page['path']} : HTTP {$response->status()}";
                continue;
            }
            $html = (string) $response->getContent();
            $body = (string) preg_replace('#<(script|style)\b.*?</\1\s*>#is', '', $html);
            foreach (self::BLADE_LEFTOVERS as $needle) {
                if (str_contains($body, $needle)) {
                    $failures[] = "{$page['path']} : contient « {$needle} »";
                }
            }
            if (str_contains($html, 'main.mjs') && ! str_contains($html, 'window.globals')) {
                $failures[] = "{$page['path']} : scripts du thème sans window.globals";
            }
        }

        $this->assertSame([], $failures, "Pages en défaut :\n".implode("\n", $failures));
    }

    public function test_les_liens_du_menu_menent_aux_pages_precises(): void
    {
        $html = $this->get('/quand-partir')->assertOk()->getContent();

        $this->assertStringContainsString('href="/sejourner/hebergements/chambres-dhotes"', $html);
        $this->assertStringContainsString('href="/sejourner/hebergements/gites-et-lodges"', $html);
        $this->assertStringContainsString('href="/sejourner/restaurants#maquis"', $html);
        $this->get('/sejourner/restaurants')->assertOk()->assertSee('id="maquis"', false);
    }

    public function test_les_variantes_d_images_de_l_accueil_existent(): void
    {
        $html = $this->get('/')->assertOk()->getContent();
        preg_match_all('#/assets/img/[A-Za-z0-9_./-]+\.(?:webp|jpe?g|png)#', $html, $m);

        $missing = array_values(array_filter(array_unique($m[0]), static fn (string $src) => ! is_file(public_path(ltrim($src, '/')))));

        $this->assertSame([], $missing, 'Images introuvables : '.implode(', ', $missing));
    }
}
