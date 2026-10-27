<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Une page éditoriale du site.
 *
 * Le contenu HTML reste dans les vues Blade sur disque ; ce modèle porte les
 * métadonnées (titre, description, profil de <head>, menu actif). Il alimente
 * le PageRepository et sert de ressource au back-office Filament.
 */
final class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'view',
        'title',
        'description',
        'active_menu',
        'head_profile',
        'standalone',
        'content_override',
        'use_override',
    ];

    protected $casts = [
        'standalone' => 'boolean',
        'use_override' => 'boolean',
    ];

    /**
     * La page a-t-elle un contenu personnalisé actif ? Le back-office édite
     * `content_override` (HTML) et bascule `use_override` : le contrôleur ne
     * rend la vue générique qu'à ces deux conditions réunies, sinon il garde
     * la vue Blade d'origine.
     */
    public function hasCustomContent(): bool
    {
        return $this->use_override && trim((string) $this->content_override) !== '';
    }

    /**
     * Importe le registre statique `resources/pages.php` dans la table
     * `pages` : chaque entree y est inseree si son chemin n'existe pas
     * encore (firstOrCreate). Les lignes deja presentes ne sont jamais
     * touchees — un admin peut avoir modifie title/description/override
     * et on n'ecrase rien.
     *
     * Appelee par Filament au mount de la liste des pages, pour que le
     * back-office « voie » toutes les pages du site sans seeder manuel.
     *
     * @return array{created:int, total:int}
     */
    public static function syncFromRegistry(): array
    {
        $file = resource_path('pages.php');
        if (!is_file($file)) {
            return ['created' => 0, 'total' => 0];
        }
        $registry = require $file;
        $created = 0;
        foreach ($registry as $path => $entry) {
            $row = self::firstOrCreate(
                ['path' => (string) $path],
                [
                    'view'         => (string) ($entry['view'] ?? ''),
                    'title'        => (string) ($entry['title'] ?? ''),
                    'description'  => (string) ($entry['description'] ?? ''),
                    'active_menu'  => $entry['activeMenu']  ?? null,
                    'head_profile' => (string) ($entry['headProfile'] ?? 'standard'),
                    'standalone'   => (bool)   ($entry['standalone'] ?? false),
                    'use_override' => false,
                ]
            );
            if ($row->wasRecentlyCreated) $created++;
        }
        return ['created' => $created, 'total' => count($registry)];
    }

    /**
     * Rend la page sous la forme attendue par le contrôleur — tableau plutôt
     * qu'objet Eloquent, pour rester interchangeable avec l'ancienne source
     * `resources/pages.php` sans que la vue ne s'en aperçoive.
     *
     * @return array<string, mixed>
     */
    public function toRegistryEntry(): array
    {
        $entry = [
            'view' => $this->view,
            'title' => $this->title,
            'description' => $this->description,
            'activeMenu' => $this->active_menu,
            'headProfile' => $this->head_profile,
        ];
        if ($this->standalone) {
            $entry['standalone'] = true;
        }
        if ($this->hasCustomContent()) {
            // Le contrôleur préférera cette vue à celle stockée dans `view`,
            // et passera le HTML de `content_override` en variable de vue.
            $entry['customView'] = 'pages.editable';
            $entry['customContent'] = $this->content_override;
        }

        return $entry;
    }
}
