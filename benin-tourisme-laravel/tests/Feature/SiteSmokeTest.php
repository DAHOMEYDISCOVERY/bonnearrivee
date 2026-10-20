<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Suite de non-régression : ce que le site DOIT continuer à faire, chantier
 * après chantier. Les tests ici ne mesurent pas la performance, ils gardent
 * les invariants — les 89 pages répondent, le sitemap liste ce qu'il faut,
 * les meta SEO ne disparaissent pas, les vestiges Mayotte ne reviennent pas.
 *
 * Ces tests roulent contre le registre de fichiers `resources/pages.php` — la
 * base SQLite in-memory du testing n'a pas d'entrée, donc `AppServiceProvider`
 * bascule sur `FilePageRepository` (voir la méthode `baseAlimentée`).
 */
final class SiteSmokeTest extends TestCase
{
    // -------------------------------------------------------------------
    // Le contrat de base
    // -------------------------------------------------------------------

    public function test_la_home_repond_200_avec_le_titre_du_site(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Bonne Arrivée', escape: false);
    }

    public function test_toutes_les_pages_du_registre_repondent_200(): void
    {
        $pages = require resource_path('pages.php');
        $this->assertGreaterThan(80, count($pages), 'Le registre devrait contenir toutes les pages du miroir');

        $echecs = [];
        foreach (array_keys($pages) as $path) {
            $response = $this->get($path === '/' ? '/' : $path);
            if ($response->status() !== 200) {
                $echecs[] = "$path : HTTP {$response->status()}";
            }
        }
        $this->assertEmpty($echecs, "Pages en échec :\n".implode("\n", $echecs));
    }

    // -------------------------------------------------------------------
    // Routes explicites hors registre
    // -------------------------------------------------------------------

    public function test_sitemap_xml_liste_les_pages(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset', escape: false);
        $response->assertSee('/carte-du-benin', escape: false);   // route hors registre
        // Au moins 80 URL dans le sitemap
        $count = substr_count($response->getContent(), '<url>');
        $this->assertGreaterThan(80, $count, 'Le sitemap devrait lister toutes les pages');
    }

    public function test_robots_txt_reference_le_sitemap(): void
    {
        // /robots.txt est servi statiquement par le web serveur (Apache), pas
        // par Laravel ; on vérifie donc le fichier sur disque.
        $chemin = public_path('robots.txt');
        $this->assertFileExists($chemin);
        $contenu = file_get_contents($chemin);
        $this->assertStringContainsString('Sitemap:', $contenu);
        $this->assertStringContainsString('Disallow: /admin', $contenu);
    }

    public function test_webmanifest_est_du_json_valide_pwa(): void
    {
        // /site.webmanifest est un fichier statique servi hors Laravel.
        $chemin = public_path('site.webmanifest');
        $this->assertFileExists($chemin);
        $data = json_decode(file_get_contents($chemin), true);
        $this->assertIsArray($data);
        $this->assertSame('Bonne Arrivée', $data['name'] ?? null);
        $this->assertSame('#00a05d', $data['theme_color'] ?? null);
        $this->assertSame('standalone', $data['display'] ?? null);
        $this->assertNotEmpty($data['icons'] ?? []);
    }

    public function test_la_page_carte_du_benin_charge_le_composant(): void
    {
        $response = $this->get('/carte-du-benin');
        $response->assertStatus(200);
        $response->assertSee('benin-carte-interactive', escape: false);
        $response->assertSee('/assets/benin-carte.css', escape: false);
        // 12 boutons de département cliquables
        $count = substr_count($response->getContent(), 'class="map-button dept-');
        $this->assertSame(12, $count, 'La carte doit afficher les 12 départements');
    }

    // -------------------------------------------------------------------
    // Pages d'erreur personnalisées
    // -------------------------------------------------------------------

    public function test_404_personnalisee_garde_le_layout_du_site(): void
    {
        $response = $this->get('/adresse-qui-nexiste-vraiment-pas');
        $response->assertStatus(404);
        $response->assertSee('La page est introuvable', escape: false);
        $response->assertSee('Bonne Arrivée', escape: false);          // titre
        $response->assertSee('brand-block', escape: false);            // footer présent
    }

    // -------------------------------------------------------------------
    // Meta head : ce que le SEO ne doit pas perdre
    // -------------------------------------------------------------------

    public function test_chaque_page_a_meta_description_et_og_image(): void
    {
        $pages = ['/', '/mentions-legales', '/explorez', '/sejourner', '/carte-du-benin'];
        foreach ($pages as $path) {
            $html = $this->get($path)->getContent();
            $this->assertMatchesRegularExpression(
                '#<meta name="description" content="[^"]{20,}"#',
                $html,
                "$path devrait exposer une meta description non vide"
            );
            $this->assertMatchesRegularExpression(
                '#<meta property="og:image" content="[^"]+"#',
                $html,
                "$path devrait exposer une og:image (via fallback si non fournie par la page)"
            );
        }
    }

    public function test_chaque_page_a_un_h1(): void
    {
        $pages = ['/', '/mentions-legales', '/explorez', '/sejourner', '/carte-du-benin'];
        foreach ($pages as $path) {
            $html = $this->get($path)->getContent();
            $this->assertMatchesRegularExpression('#<h1\b#', $html, "$path devrait avoir un <h1>");
        }
    }

    public function test_json_ld_accueil_expose_organization_et_touristdestination(): void
    {
        $html = $this->get('/')->getContent();
        preg_match_all('#<script[^>]*application/ld\+json[^>]*>([\s\S]*?)</script>#', $html, $m);
        $this->assertNotEmpty($m[1], 'La home doit contenir au moins un bloc JSON-LD');

        $vus = [];
        foreach ($m[1] as $bloc) {
            $data = json_decode(trim($bloc), true);
            foreach ($data['@graph'] ?? [$data] as $node) {
                $type = $node['@type'] ?? null;
                if (is_array($type)) {
                    $vus = array_merge($vus, $type);
                } elseif (is_string($type)) {
                    $vus[] = $type;
                }
            }
        }
        $this->assertContains('WebSite', $vus);
        $this->assertContains('Organization', $vus);
        $this->assertContains('TouristDestination', $vus);
    }

    // -------------------------------------------------------------------
    // Vestiges Mayotte : ne doivent jamais revenir
    // -------------------------------------------------------------------

    public function test_pas_de_placeholder_angular_visible_sur_les_fiches_offres(): void
    {
        $fiches = [
            '/offres/hotel-du-lac-nokoue-cotonou-bj-3332266',
            '/offres/le-maquis-royal-abomey-bj-3329842',
            '/offres/pendjari-safari-tours-natitingou-bj-3313618',
        ];
        foreach ($fiches as $path) {
            $html = $this->get($path)->getContent();
            $this->assertStringNotContainsString('{{userProfile', $html, "$path : placeholder Angular visible");
            $this->assertDoesNotMatchRegularExpression('# ng-(if|href|repeat|model|click|change)=#', $html,
                "$path : directive ng-* résiduelle");
        }
    }

    public function test_pas_de_residu_benin_forever(): void
    {
        $pages = ['/', '/mentions-legales', '/explorez'];
        foreach ($pages as $path) {
            $html = $this->get($path)->getContent();
            $this->assertStringNotContainsString('Bénin Forever', $html);
            $this->assertStringNotContainsString('BENINFOREVER', $html);
            $this->assertStringNotContainsString('#beninforever', $html);
        }
    }
}
