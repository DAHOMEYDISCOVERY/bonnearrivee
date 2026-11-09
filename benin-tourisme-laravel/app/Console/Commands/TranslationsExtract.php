<?php

namespace App\Console\Commands;

use App\Support\ContentLayer;
use App\Support\PageSnapshot;
use App\Support\SitePages;
use App\Support\TranslationStore;
use Illuminate\Console\Command;

/**
 * Extrait les textes traduisibles des pages (via le même repérage que
 * « Textes et images ») et écrit un fichier JSON à traduire. N'écrit rien en
 * base ; ne touche pas au français.
 *
 *   php artisan trad:extract                 # toutes les pages
 *   php artisan trad:extract --path=/sejourner/toutes-les-infos-pratiques/les-liens-utiles
 */
class TranslationsExtract extends Command
{
    protected $signature = 'trad:extract {--path= : Limiter à une page} {--locale=en : Langue cible}';
    protected $description = 'Extrait les textes à traduire (rapport des manquants) vers storage/app/translations.';

    private const TYPES = ['text', 'rich', 'head'];

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $pages = SitePages::all();

        if ($only = $this->option('path')) {
            $norm = ContentLayer::normalisePath((string) $only);
            $pages = array_values(array_filter($pages, static fn (array $p) => $p['path'] === $norm));
            if ($pages === []) {
                $this->error("Page introuvable dans la liste éditable : {$norm}");

                return self::FAILURE;
            }
        }

        $out = [];
        $total = 0;
        $missing = 0;
        $failed = [];

        foreach ($pages as $page) {
            $html = PageSnapshot::html($page['path']);
            if ($html === null) {
                $failed[] = $page['path'];
                continue;
            }
            $existing = TranslationStore::get($locale, $page['path']);
            $pageOut = [];
            foreach (ContentLayer::extract($html, true) as $item) {
                if (! in_array($item['type'], self::TYPES, true)) {
                    continue;
                }
                $fr = trim((string) ($item['value'] ?? ''));
                if ($fr === '') {
                    continue;
                }
                $en = $existing[$item['key']]['value'] ?? null;
                $pageOut[$item['key']] = [
                    'type' => $item['type'],
                    'group' => (string) ($item['group'] ?? ''),
                    'fr' => $fr,
                    'en' => is_string($en) ? $en : null,
                ];
                $total++;
                if ($en === null) {
                    $missing++;
                }
            }
            if ($pageOut !== []) {
                $out[$page['path']] = $pageOut;
            }
        }

        $dir = storage_path('app/translations');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $file = $dir.'/extract-'.$locale.'.json';
        file_put_contents($file, json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Pages traitées : '.count($out));
        $this->info("Segments de texte : {$total} (à traduire : {$missing})");
        if ($failed !== []) {
            $this->warn('Pages non rendues (ignorées) : '.implode(', ', $failed));
        }
        $this->line("Fichier écrit : {$file}");
        $this->line("Remplissez les champs \"en\" puis : php artisan trad:import {$file}");

        return self::SUCCESS;
    }
}
