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
        'wrap_content',
    ];

    protected $casts = [
        'standalone' => 'boolean',
        'use_override' => 'boolean',
        'wrap_content' => 'boolean',
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
     * Shortcut : si le nombre de rows en DB est deja >= au registre, on
     * saute l'import pour eviter 89 queries a chaque mount Filament.
     * Le bouton 'Resynchroniser' force quand meme la verif via $force.
     *
     * @return array{created:int, total:int}
     */
    public static function syncFromRegistry(bool $force = false): array
    {
        $file = resource_path('pages.php');
        if (!is_file($file)) {
            return ['created' => 0, 'total' => 0];
        }
        $registry = require $file;
        $total    = count($registry);

        // Chemin rapide : si le count correspond, pas de scan ligne par ligne.
        // Un UPSERT prendrait au minimum $total requetes, c'est ce qu'on evite.
        if (!$force && self::count() >= $total) {
            return ['created' => 0, 'total' => $total];
        }

        // Charge les chemins deja presents en une seule requete, pour ne
        // faire un INSERT que sur ceux qui manquent (vs 89 firstOrCreate).
        $existing = self::query()->pluck('path')->flip();
        $toInsert = [];
        $now      = now();
        foreach ($registry as $path => $entry) {
            $path = (string) $path;
            if (isset($existing[$path])) continue;
            $toInsert[] = [
                'path'         => $path,
                'view'         => (string) ($entry['view'] ?? ''),
                'title'        => (string) ($entry['title'] ?? ''),
                'description'  => (string) ($entry['description'] ?? ''),
                'active_menu'  => $entry['activeMenu']  ?? null,
                'head_profile' => (string) ($entry['headProfile'] ?? 'standard'),
                'standalone'   => (bool)   ($entry['standalone'] ?? false),
                'use_override' => false,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        if (!empty($toInsert)) {
            self::query()->insert($toInsert);
        }
        return ['created' => count($toInsert), 'total' => $total];
    }

    /**
     * Extrait le HTML brut de la section 'content' d'une vue Blade existante,
     * pour pre-remplir le champ 'content_override' dans l'admin.
     *
     * La convention du projet : chaque page a un @section('content') ... @endsection,
     * souvent entoure d'un @verbatim. On retourne le HTML tel quel pour que
     * l'editeur de code voie exactement ce que le site rend.
     *
     * Retourne null si la vue est introuvable, ou si la section ne peut etre
     * localisee (ex. layouts speciaux).
     */
    public static function extractContentFromView(string $view): ?string
    {
        if ($view === '') return null;
        $relative = str_replace('.', DIRECTORY_SEPARATOR, $view) . '.blade.php';
        $path     = resource_path('views' . DIRECTORY_SEPARATOR . $relative);
        if (!is_file($path)) return null;

        $src = file_get_contents($path);
        if ($src === false || $src === '') return null;

        // On matche @section('content') ... @endsection (ou @stop). Les quotes
        // peuvent etre simples ou doubles. On capture le corps interieur.
        $pattern = "/@section\\(\\s*['\"]content['\"]\\s*\\)(.*?)@(?:endsection|stop)\\b/s";
        if (!preg_match($pattern, $src, $m)) return null;

        $body = $m[1];
        // Supprime les @verbatim/@endverbatim : leur HTML interieur est deja pur,
        // la directive n'a pas de sens dans un champ texte.
        $body = preg_replace('/@verbatim\\b/', '', $body);
        $body = preg_replace('/@endverbatim\\b/', '', $body);
        // Nettoie les sauts de ligne de debut/fin pour un rendu propre dans la textarea.
        return trim((string) $body, "\r\n");
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
            // wrap_content=false (defaut moderne) : HTML brut injecte dans le layout.
            // wrap_content=true (legacy)          : enveloppe custom-text + titre.
            $entry['customView']    = $this->wrap_content ? 'pages.editable' : 'pages.editable-raw';
            $entry['customContent'] = $this->content_override;
        }

        return $entry;
    }
}
