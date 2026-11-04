<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Admin\Pages\PageContentEditor;
use App\Filament\Admin\Pages\PageContentIndex;
use App\Models\Page;
use App\Models\User;
use App\Support\ContentLayer;
use App\Support\PageContentStore;
use App\Support\PageSnapshot;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * « Textes et images » : toute page du site se modifie depuis le back-office
 * (textes, photos, liens, titre Google), et la modification est publiée.
 */
final class PageContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
    }

    private function editor(): User
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        return User::query()->create([
            'name' => 'Éditrice',
            'email' => 'editrice@example.test',
            'password' => bcrypt('secret-test'),
            'role' => User::ROLE_EDITOR,
        ]);
    }

    /** @return array<string, mixed> */
    private function item(string $path, string $type, string $value): array
    {
        $html = PageSnapshot::html($path);
        $this->assertNotNull($html, "La page {$path} doit pouvoir être lue.");
        foreach (ContentLayer::extract($html) as $item) {
            if ($item['type'] === $type && $item['value'] === $value) {
                return $item;
            }
        }
        $this->fail("Élément {$type} « {$value} » introuvable sur {$path}.");
    }

    public function test_l_extraction_ignore_entete_pied_scripts_et_blocs_dynamiques(): void
    {
        $html = '<html><head><title>T</title></head><body><header><p>Menu</p></header><main>'
            .'<h1>Titre</h1><script>var a="<p>non</p>";</script><svg><text>non</text></svg>'
            .'<div data-ba-dynamique><p>Restaurant en base</p><img src="/assets/img/x.webp" alt=""></div>'
            .'<img src="https://partenaire.example/photo.jpg" alt=""></main><footer><p>Pied</p></footer></body></html>';

        $values = array_column(ContentLayer::extract($html), 'value');

        $this->assertContains('Titre', $values);
        foreach (['Menu', 'Pied', 'non', 'Restaurant en base', '/assets/img/x.webp', 'https://partenaire.example/photo.jpg'] as $absent) {
            $this->assertNotContains($absent, $values);
        }
    }

    public function test_une_page_sans_modification_reste_identique_octet_pour_octet(): void
    {
        $html = $this->get('/quand-partir')->assertOk()->getContent();

        $this->assertSame($html, ContentLayer::apply($html, []));
        $this->assertSame([], PageContentStore::edits('/quand-partir'));
    }

    public function test_un_texte_modifie_est_publie_puis_peut_etre_retire(): void
    {
        $h1 = $this->item('/quand-partir', 'text', 'Quand partir au Bénin ?');
        PageContentStore::save('/quand-partir', [$h1['key'] => ['type' => 'text', 'value' => 'La meilleure saison pour venir']]);

        $this->get('/quand-partir')->assertOk()->assertSee('La meilleure saison pour venir');

        PageContentStore::save('/quand-partir', []);
        $this->get('/quand-partir')->assertOk()->assertSee('Quand partir au Bénin ?');
    }

    public function test_le_titre_google_et_la_description_sont_modifiables(): void
    {
        $title = $this->item('/a-propos', 'head', 'À propos — Bonne Arrivée');
        $description = collect(ContentLayer::extract((string) PageSnapshot::html('/a-propos')))->firstWhere('key', 'head:description');
        PageContentStore::save('/a-propos', [
            $title['key'] => ['type' => 'head', 'value' => 'Qui sommes-nous ? | Bonne Arrivée'],
            $description['key'] => ['type' => 'head', 'value' => 'L’équipe & l’histoire de Bonne Arrivée.'],
        ]);

        $html = $this->get('/a-propos')->assertOk()->getContent();
        $this->assertStringContainsString('<title>Qui sommes-nous ? | Bonne Arrivée</title>', $html);
        $this->assertStringContainsString('<meta name="description" content="L’équipe &amp; l’histoire de Bonne Arrivée."', $html);
    }

    public function test_une_photo_remplacee_s_affiche_sans_les_anciennes_variantes(): void
    {
        $html = (string) PageSnapshot::html('/explorez/terre-de-traditions');
        $image = collect(ContentLayer::extract($html))->first(fn ($i) => $i['type'] === 'image' && str_contains($i['raw'], 'srcset'));
        $this->assertNotNull($image);

        PageContentStore::save('/explorez/terre-de-traditions', [
            $image['key'] => ['type' => 'image', 'value' => '/assets/img/uploads/pages/nouvelle.webp', 'alt' => 'Nouvelle photo'],
        ]);

        $out = $this->get('/explorez/terre-de-traditions')->assertOk()->getContent();
        $this->assertStringContainsString('src="/assets/img/uploads/pages/nouvelle.webp"', $out);
        $this->assertStringContainsString('alt="Nouvelle photo"', $out);
        $this->assertSame(1, substr_count($out, 'nouvelle.webp'), 'Aucune ancienne variante (srcset) ne doit subsister pour cette image.');
    }

    public function test_le_code_dangereux_saisi_est_neutralise(): void
    {
        $clean = ContentLayer::sanitiseRich('<b>Gras</b><img src=x onerror=alert(1)><script>x()</script><a href="javascript:alert(1)" onclick="y()">lien</a><a href="/ok">ok</a>');

        $this->assertStringContainsString('<b>Gras</b>', $clean);
        $this->assertStringContainsString('<a href="/ok">ok</a>', $clean);
        foreach (['<img', 'onerror', 'onclick', 'javascript:', '<script'] as $danger) {
            $this->assertStringNotContainsString($danger, $clean);
        }
        $this->assertFalse(ContentLayer::isSafeUrl('javascript:alert(1)'));
        $this->assertTrue(ContentLayer::isSafeUrl('/sejourner'));
        $this->assertTrue(ContentLayer::isSafeUrl('https://example.test/page'));
    }

    public function test_un_editeur_modifie_un_texte_dans_le_formulaire_et_le_site_change(): void
    {
        $editor = $this->editor();

        $component = Livewire::withQueryParams(['chemin' => '/quand-partir'])
            ->actingAs($editor)
            ->test(PageContentEditor::class);

        $index = collect($component->get('items'))->search(fn ($i) => $i['type'] === 'text' && $i['original'] === 'Quand partir au Bénin ?');
        $this->assertIsInt($index);

        $component->set("values.{$index}", 'Choisir sa saison au Bénin')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/quand-partir')->assertOk()->assertSee('Choisir sa saison au Bénin');
        $this->assertSame(1, PageContentStore::count('/quand-partir'));
    }

    public function test_un_editeur_remplace_une_photo_par_envoi_de_fichier(): void
    {
        Storage::fake('site_images');
        $editor = $this->editor();

        $component = Livewire::withQueryParams(['chemin' => '/quand-partir'])
            ->actingAs($editor)
            ->test(PageContentEditor::class);

        $index = collect($component->get('items'))->search(fn ($i) => $i['type'] === 'image');
        $this->assertIsInt($index);

        $component->set("uploads.{$index}", UploadedFile::fake()->image('Nouvelle Atacora.jpg', 1600, 900))
            ->call('save')
            ->assertHasNoErrors();

        $files = Storage::disk('site_images')->files('uploads/pages');
        $this->assertCount(1, $files);
        $this->assertStringStartsWith('uploads/pages/nouvelle-atacora-', $files[0]);
        $this->get('/quand-partir')->assertOk()->assertSee('/assets/img/'.$files[0], false);
    }

    public function test_un_fichier_qui_n_est_pas_une_image_est_refuse(): void
    {
        Storage::fake('site_images');
        $editor = $this->editor();

        $component = Livewire::withQueryParams(['chemin' => '/quand-partir'])
            ->actingAs($editor)
            ->test(PageContentEditor::class);
        $index = collect($component->get('items'))->search(fn ($i) => $i['type'] === 'image');

        $component->set("uploads.{$index}", UploadedFile::fake()->create('virus.php', 10, 'text/x-php'))
            ->call('save')
            ->assertHasErrors(["uploads.{$index}"]);

        $this->assertSame([], Storage::disk('site_images')->allFiles());
        $this->assertSame([], PageContentStore::edits('/quand-partir'));
    }

    public function test_tout_remettre_a_l_origine(): void
    {
        $h1 = $this->item('/quand-partir', 'text', 'Quand partir au Bénin ?');
        PageContentStore::save('/quand-partir', [$h1['key'] => ['type' => 'text', 'value' => 'Temporaire']]);

        Livewire::withQueryParams(['chemin' => '/quand-partir'])
            ->actingAs($this->editor())
            ->test(PageContentEditor::class)
            ->call('restoreAll');

        $this->assertSame([], PageContentStore::edits('/quand-partir'));
        $this->get('/quand-partir')->assertOk()->assertSee('Quand partir au Bénin ?')->assertDontSee('Temporaire');
    }

    public function test_la_liste_couvre_toutes_les_pages_y_compris_speciales_et_evenements(): void
    {
        Livewire::actingAs($this->editor())
            ->test(PageContentIndex::class)
            ->assertSee('/explorez/terre-de-traditions')
            ->assertSee('/a-propos')
            ->assertSee('/quand-partir')
            ->assertSee('/sejourner/restaurants')
            ->assertSee('/benin-en-fete/vodun-days')
            ->assertDontSee('/admin/login');
    }

    public function test_les_fiches_evenements_sont_modifiables(): void
    {
        $html = (string) PageSnapshot::html('/benin-en-fete/vodun-days');
        $title = collect(ContentLayer::extract($html))->first(fn ($i) => $i['type'] === 'text' && $i['value'] === 'Vodun Days');
        $this->assertNotNull($title);

        PageContentStore::save('/benin-en-fete/vodun-days', [$title['key'] => ['type' => 'text', 'value' => 'Les Vodun Days 2027']]);

        $this->get('/benin-en-fete/vodun-days')->assertOk()->assertSee('Les Vodun Days 2027');
    }

    public function test_un_visiteur_n_accede_pas_a_l_ecran_de_modification(): void
    {
        $this->get('/admin/textes-et-images')->assertRedirect('/admin/login');
        $this->get('/admin/textes-et-images/modifier?chemin=/')->assertRedirect('/admin/login');
    }

    public function test_benin_en_fete_publie_la_version_enregistree_dans_pages(): void
    {
        Page::syncFromRegistry(true);
        Page::query()->where('path', '/benin-en-fete')->update([
            'use_override' => true,
            'content_override' => '<div class="content-wrapper"><p>Agenda mis à jour depuis le back-office</p></div>',
        ]);

        $this->get('/benin-en-fete')->assertOk()->assertSee('Agenda mis à jour depuis le back-office');
    }

    public function test_benin_en_fete_sans_modification_garde_son_contenu(): void
    {
        $this->get('/benin-en-fete')->assertOk()
            ->assertSee('<title>Bénin en Fête | Bonne Arrivée</title>', false)
            ->assertSee('Vodun Days');
    }
}
