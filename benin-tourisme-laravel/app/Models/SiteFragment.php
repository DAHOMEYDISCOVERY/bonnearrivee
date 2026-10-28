<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Un morceau de template editable depuis le back-office : header, footer, et
 * tout autre bloc susceptible de changer sans redeploiement. Chaque fragment
 * porte un slug unique (header, footer, ...) que les partials Blade cherchent
 * en base avant de retomber sur le HTML en dur.
 */
final class SiteFragment extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'label', 'content', 'notes'];

    /** Catalogue des fragments connus par le site. */
    public const KNOWN = [
        'header' => "En-tête du site (logo + navigation + loupe)",
        'footer' => "Pied de page (logo, coordonnées, boutons, carte)",
    ];

    /**
     * Renvoie le HTML du fragment pour ce slug, ou null s'il n'existe pas en
     * base. Mis en cache en memoire pour que header + footer n'ajoutent qu'une
     * seule paire de requetes par rendu de page.
     */
    public static function html(string $slug): ?string
    {
        $slug = strtolower(trim($slug));
        $cacheKey = "site_fragment:{$slug}";

        return Cache::remember($cacheKey, 60, function () use ($slug) {
            $row = self::query()->where('slug', $slug)->first();
            return $row?->content;
        });
    }

    /** Invalidation du cache lors d'un save admin. */
    protected static function booted(): void
    {
        static::saved(fn (self $f) => Cache::forget("site_fragment:{$f->slug}"));
        static::deleted(fn (self $f) => Cache::forget("site_fragment:{$f->slug}"));
    }

    /**
     * Seed les fragments manquants a partir du HTML des partials actuels. Idempotent :
     * si un slug existe deja, il n'est jamais ecrase. Appelle extractHtmlFromPartial()
     * pour isoler le vrai HTML (sans la bascule DB/@if/@else) ajoutee aux partials.
     *
     * @return array{created:int, total:int}
     */
    public static function seedFromPartials(bool $force = false): array
    {
        $sources = [
            'header' => resource_path('views/partials/header.blade.php'),
            'footer' => resource_path('views/partials/footer.blade.php'),
        ];
        $created = 0;
        $total   = count(self::KNOWN);

        foreach ($sources as $slug => $path) {
            $existing = self::query()->where('slug', $slug)->first();
            if ($existing && !$force) continue;

            $html = self::extractHtmlFromPartial($path);
            if ($existing) {
                $existing->update(['content' => $html]);
            } else {
                self::query()->create([
                    'slug'    => $slug,
                    'label'   => self::KNOWN[$slug],
                    'content' => $html,
                    'notes'   => null,
                ]);
                $created++;
            }
        }

        return ['created' => $created, 'total' => $total];
    }

    /**
     * Extrait le HTML pur d'un partial de bascule DB. La convention :
     *   @php($__dbXxx = ...)
     *   @if ($__dbXxx !== null) ... @else <HTML ORIGINAL> @endif
     *
     * On veut juste <HTML ORIGINAL>. Si pas de bascule (ancien partial), tout
     * le fichier est renvoye nettoye.
     */
    public static function extractHtmlFromPartial(string $path): string
    {
        $src = is_file($path) ? (string) file_get_contents($path) : '';
        if ($src === '') return '';

        // Prefere la branche @else ... @endif si on la trouve (c'est l'HTML original).
        if (preg_match('/@else\b(.*?)@endif\b/s', $src, $m)) {
            $src = $m[1];
        }

        // Enleve commentaires Blade et wrappers verbatim.
        $src = preg_replace('/\{\{--.*?--\}\}/s', '', $src) ?? $src;
        $src = str_replace(['@verbatim', '@endverbatim'], '', $src);

        return trim($src);
    }
}
