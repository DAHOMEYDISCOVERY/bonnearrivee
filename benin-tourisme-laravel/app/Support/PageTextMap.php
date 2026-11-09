<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Dictionnaire « texte français → texte traduit » d'une page donnée, construit
 * à partir des traductions déjà enregistrées (TranslationStore).
 *
 * Sert à traduire des contenus dynamiques (fiches restaurants, etc.) qui sont
 * affichés hors page — par exemple dans le menu de recherche — en réutilisant
 * la traduction DÉJÀ faite de la page où ce contenu apparaît, sans nouvel appel
 * DeepL. Mis en cache : on ne reconstruit pas à chaque requête.
 */
final class PageTextMap
{
    /**
     * @return array<string, string> texte original => texte traduit
     */
    public static function frToEn(string $locale, string $path): array
    {
        if ($locale === 'fr') {
            return [];
        }
        $path = ContentLayer::normalisePath($path);

        return Cache::remember('pagetextmap:'.$locale.':'.$path, 3600, static function () use ($locale, $path): array {
            $translations = TranslationStore::get($locale, $path);
            if ($translations === []) {
                return [];
            }
            $html = PageSnapshot::html($path);
            if ($html === null) {
                return [];
            }

            $map = [];
            foreach (ContentLayer::extract($html, true) as $item) {
                if (! in_array($item['type'], ['text', 'rich', 'head'], true)) {
                    continue;
                }
                $fr = trim((string) ($item['value'] ?? ''));
                $en = $translations[$item['key']]['value'] ?? null;
                if ($fr !== '' && is_string($en) && trim($en) !== '') {
                    $map[$fr] = $en;
                }
            }

            return $map;
        });
    }

    /** Traduit un texte via le dictionnaire d'une page (repli : texte inchangé). */
    public static function translate(string $locale, string $path, ?string $text): ?string
    {
        if ($text === null || $text === '' || $locale === 'fr') {
            return $text;
        }
        $map = self::frToEn($locale, $path);

        return $map[trim($text)] ?? $text;
    }
}
