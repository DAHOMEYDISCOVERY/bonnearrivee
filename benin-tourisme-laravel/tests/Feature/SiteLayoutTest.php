<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Admin\Pages\SiteLayoutPage;
use App\Models\SiteFragment;
use App\Models\SiteSetting;
use App\Models\User;
use App\Support\LegacyFragmentImporter;
use App\Support\SiteLayout;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * En-tête et pied de page : le code fixe le balisage, le back-office le contenu.
 */
final class SiteLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    public function test_sans_enregistrement_le_site_affiche_les_valeurs_du_code(): void
    {
        $html = $this->get('/quand-partir')->assertOk()->getContent();

        foreach (['Découvrez', 'Vivez', 'Organisez', 'Les cinq Bénin', 'Où dormir ?', 'Toutes les infos pratiques'] as $label) {
            $this->assertStringContainsString($label, $html);
        }
        $this->assertStringContainsString('aria-label="Cafés et bars"', $html);
        $this->assertStringContainsString('data-srcset="/assets/img/pirogue-360x180.webp 360w', $html);
        $this->assertStringContainsString('<a href="tel:+2290167110220"', $html);
        $this->assertStringContainsString('reservations@dahomeydiscovery.com', $html);
        $this->assertStringNotContainsString('{{', $html);
    }

    public function test_le_menu_actif_est_signale(): void
    {
        $html = $this->get('/explorez')->assertOk()->getContent();

        $this->assertStringContainsString('class="menu-item is-submenu-parent is-active" role="button">', $html);
    }

    public function test_une_version_enregistree_au_back_office_est_publiee(): void
    {
        $footer = SiteLayout::defaultFooter();
        $footer['company'] = 'BONNE ARRIVEE';
        $footer['email'] = 'contact@bonnearrivee.bj';
        SiteSetting::write(SiteLayout::FOOTER_KEY, $footer);

        $header = SiteLayout::defaultHeader();
        $header['menu'][0]['label'] = 'Explorer';
        SiteSetting::write(SiteLayout::HEADER_KEY, $header);

        $html = $this->get('/quand-partir')->assertOk()->getContent();

        $this->assertStringContainsString('<strong>BONNE ARRIVEE</strong>', $html);
        $this->assertStringContainsString('mailto:contact@bonnearrivee.bj', $html);
        $this->assertStringContainsString('<span class="menuLink-title">Explorer</span>', $html);
    }

    public function test_revenir_aux_valeurs_du_code(): void
    {
        SiteSetting::write(SiteLayout::FOOTER_KEY, ['company' => 'Temporaire'] + SiteLayout::defaultFooter());
        SiteSetting::forgetKey(SiteLayout::FOOTER_KEY);

        $this->get('/quand-partir')->assertOk()->assertSee('<strong>Dahomey Discovery</strong>', false);
    }

    public function test_l_ancien_bloc_footer_modifie_est_repris_et_son_telephone_repare(): void
    {
        SiteFragment::query()->create([
            'slug' => 'footer',
            'label' => 'Pied de page',
            'content' => '<footer class="site-footer"><div class="brand-block"><p class="brand-tagline">Portail</p></div>'
                .'<div class="infos-block"><p>BONNE ARRIVEE</p><p>Rond-point Akpakpa<br>Cotonou</p>'
                .'<p>Tél. <a href="tel:+2290167110220">+229 01 67 11 02 2</a>0<br><span>contact@bonnearrivee.bj</span></p></div>'
                .'<div class="buttons-block"><a class="button primary" href="/a-propos" title="À propos">À propos</a></div></footer>',
        ]);

        $done = LegacyFragmentImporter::import();

        $this->assertTrue($done['footer']);
        $footer = SiteLayout::footer();
        $this->assertSame('BONNE ARRIVEE', $footer['company']);
        $this->assertSame('+229 01 67 11 02 20', $footer['phone']);
        $this->assertSame('contact@bonnearrivee.bj', $footer['email']);
    }

    public function test_un_editeur_enregistre_le_formulaire_et_le_site_change(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $editor = User::query()->create([
            'name' => 'Éditrice',
            'email' => 'editrice@example.test',
            'password' => bcrypt('secret-test'),
            'role' => User::ROLE_EDITOR,
        ]);

        Livewire::actingAs($editor)
            ->test(SiteLayoutPage::class)
            ->set('data.footer.company', 'Bonne Arrivée SARL')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/quand-partir')->assertOk()->assertSee('<strong>Bonne Arrivée SARL</strong>', false);
    }
}
