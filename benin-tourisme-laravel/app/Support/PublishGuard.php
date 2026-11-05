<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Log;

/**
 * Garde-fou de publication du back-office.
 *
 * Avant qu'une modification ne reste en ligne (page, « Textes et images »,
 * en-tête et pied de page), la page concernée est affichée exactement comme
 * un visiteur la verrait. Si elle ne s'affiche pas — erreur serveur, bloc qui
 * ne se compile pas, composant resté en code brut — la modification est
 * annulée et l'éditeur reçoit la raison en clair : une modification ne peut
 * plus casser une page du site.
 *
 * Le même contrôle, sur toutes les pages, est lancé par `php artisan
 * site:verifier` (déploiement et chaque nuit).
 */
final class PublishGuard
{
    /**
     * Problème d'affichage de la page, en français ; null si la page
     * s'affiche correctement.
     */
    public static function problem(string $path): ?string
    {
        $path = ContentLayer::normalisePath($path);
        OverrideHtml::$lastError = null;
        PageSnapshot::$lastError = null;

        try {
            $html = PageSnapshot::html($path);
        } catch (\Throwable $e) {
            return self::fail($path, 'la page ne s’affiche plus ('.self::short($e->getMessage()).')');
        }

        if (OverrideHtml::$lastError !== null) {
            return self::fail($path, 'un bloc de la page ne peut pas s’afficher ('.self::short(OverrideHtml::$lastError).')');
        }
        if ($html === null) {
            return self::fail($path, 'la page ne s’affiche plus ('.self::short(PageSnapshot::$lastError ?? 'erreur serveur').')');
        }

        try {
            $html = ContentLayer::apply($html, PageContentStore::edits($path));
        } catch (\Throwable $e) {
            return self::fail($path, 'les textes et images modifiés ne s’appliquent plus ('.self::short($e->getMessage()).')');
        }

        $body = (string) preg_replace('#<(script|style|template|textarea)\b.*?</\1\s*>#is', '', $html);
        if (preg_match('#<x-[\w.:-]+#', $body, $m)) {
            return self::fail($path, 'le bloc « '.$m[0].'> » est resté en code au lieu de s’afficher');
        }

        // Du code PHP ou Blade affiché en texte dans la page.
        $start = stripos($body, '<main');
        $end = strripos($body, '</main>');
        $texte = html_entity_decode(strip_tags($start !== false ? substr($body, $start, ($end ?: strlen($body)) - $start) : $body), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (preg_match('/\$[A-Za-z_]\w*\s*(=|\(|->)|=>\s*[\'"\[$]|<\?php|@(foreach|endforeach|php|endphp|if|endif)\b|\{\{\s*\$/', $texte, $m)) {
            return self::fail($path, 'du code s’affiche en texte sur la page (« '.trim($m[0]).' »)');
        }

        return null;
    }

    /**
     * Applique une modification puis vérifie les pages ; en cas de problème,
     * `$rollback` remet l'état précédent et le message est renvoyé.
     *
     * @param  list<string>  $paths
     */
    public static function attempt(callable $apply, callable $rollback, array $paths): ?string
    {
        $apply();
        foreach ($paths as $path) {
            $problem = self::problem($path);
            if ($problem !== null) {
                try {
                    $rollback();
                } catch (\Throwable $e) {
                    Log::error('Back-office : retour à l’état précédent impossible', ['page' => $path, 'error' => $e->getMessage()]);
                }

                return $problem;
            }
        }

        return null;
    }

    private static function fail(string $path, string $reason): string
    {
        Log::error('Back-office : modification refusée, elle casserait la page', ['page' => $path, 'raison' => $reason, 'utilisateur' => auth()->id()]);

        return "Sur la page {$path}, {$reason}.";
    }

    private static function short(string $message): string
    {
        $message = (string) preg_replace('/\s*\(View: [^)]*\)/', '', $message);

        return mb_strimwidth(trim($message), 0, 220, '…');
    }
}
