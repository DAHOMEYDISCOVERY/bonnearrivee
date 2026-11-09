<?php

namespace App\Console\Commands;

use App\Support\TranslationStore;
use Illuminate\Console\Command;

/**
 * Importe les traductions depuis un fichier JSON (format produit par
 * trad:extract, champ « en » rempli). N'écrase jamais le français ; fusionne
 * avec les traductions déjà présentes.
 *
 *   php artisan trad:import storage/app/translations/extract-en.json
 */
class TranslationsImport extends Command
{
    protected $signature = 'trad:import {file : Fichier JSON des traductions} {--locale=en : Langue cible}';
    protected $description = 'Importe les traductions d\'un fichier JSON vers le magasin de traductions.';

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $file = (string) $this->argument('file');
        if (! is_file($file)) {
            $file = base_path($file);
        }
        if (! is_file($file)) {
            $this->error("Fichier introuvable : {$this->argument('file')}");

            return self::FAILURE;
        }

        $data = json_decode((string) file_get_contents($file), true);
        if (! is_array($data)) {
            $this->error('JSON invalide.');

            return self::FAILURE;
        }

        $pages = 0;
        $count = 0;
        foreach ($data as $path => $map) {
            if (! is_array($map)) {
                continue;
            }
            $items = TranslationStore::get($locale, (string) $path);
            $changed = false;
            foreach ($map as $key => $entry) {
                $en = is_array($entry) ? ($entry['en'] ?? $entry['value'] ?? null) : $entry;
                if (! is_string($en) || trim($en) === '') {
                    continue;
                }
                $items[(string) $key] = ['value' => $en];
                $count++;
                $changed = true;
            }
            if ($changed) {
                TranslationStore::save($locale, (string) $path, $items);
                $pages++;
            }
        }

        $this->info("Traductions importées : {$count} segments sur {$pages} pages (langue : {$locale}).");

        return self::SUCCESS;
    }
}
