<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Support\ContentLayer;
use App\Support\PageContentStore;
use App\Support\PageSnapshot;
use App\Support\SearchIndex;
use Database\Seeders\AdminUserSeeder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Recherche sur le texte réellement affiché (audit BUG-12) et création du
 * compte administrateur (BUG-18).
 */
final class SearchIndexTest extends TestCase
{
    use RefreshDatabase;

    private const TANOUGOU = '/explorez/naturellement-sauvage/les-chutes-de-tanougou';

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        Storage::fake('local');
    }

    public function test_chutes_trouve_la_page_tanougou(): void
    {
        SearchIndex::build([self::TANOUGOU, '/quand-partir']);

        $this->get('/recherche?q=chutes')->assertOk()->assertSee('href="'.self::TANOUGOU.'"', false);
    }

    public function test_une_page_construite_en_code_est_trouvee(): void
    {
        SearchIndex::build([self::TANOUGOU, '/quand-partir']);

        $this->get('/recherche?q=harmattan')->assertOk()->assertSee('href="/quand-partir"', false);
    }

    public function test_les_accents_et_majuscules_sont_ignores(): void
    {
        SearchIndex::build([self::TANOUGOU]);

        $this->assertNotEmpty(SearchIndex::search('CHUTES tanougou'));
        $this->assertSame([], SearchIndex::search('motintrouvablexyz'));
    }

    public function test_un_texte_modifie_au_back_office_est_cherchable_aussitot(): void
    {
        SearchIndex::build(['/quand-partir']);
        $html = (string) PageSnapshot::html('/quand-partir');
        $h1 = collect(ContentLayer::extract($html))->first(fn ($i) => $i['type'] === 'text' && $i['value'] === 'Quand partir au Bénin ?');

        PageContentStore::save('/quand-partir', [$h1['key'] => ['type' => 'text', 'value' => 'Calendrier zorglubien']]);

        $this->assertSame('/quand-partir', SearchIndex::search('zorglubien')[0]['path'] ?? null);
    }

    public function test_sans_index_la_recherche_garde_son_fonctionnement(): void
    {
        $this->assertFalse(SearchIndex::exists());
        $this->get('/recherche?q=vodun')->assertOk();
    }

    public function test_le_seeder_admin_ne_recree_pas_de_compte_si_un_admin_existe(): void
    {
        User::query()->create(['name' => 'A', 'email' => 'a@example.test', 'password' => bcrypt('x'), 'role' => User::ROLE_ADMIN]);

        $this->seed(AdminUserSeeder::class);

        $this->assertSame(1, User::query()->count());
        $this->assertFalse(User::query()->where('email', 'admin@bonnearrivee.bj')->exists());
    }

    public function test_le_seeder_admin_refuse_hors_poste_local_sans_mot_de_passe(): void
    {
        $this->app['env'] = 'production';

        // Appel direct : en production, la commande db:seed demande une
        // confirmation interactive, qu'un test ne peut pas donner.
        $this->app->make(AdminUserSeeder::class)->run();

        $this->assertSame(0, User::query()->count());
    }
}
