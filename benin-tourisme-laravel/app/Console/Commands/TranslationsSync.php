<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Synchronise les traductions : extraction -> traduction DeepL -> import.
 * Incrémental (seuls les nouveaux textes sont traduits). Lancée à la main
 * après un ajout de contenu, ou automatiquement chaque nuit (voir le
 * planificateur dans routes/console.php).
 *
 *   php artisan trad:sync
 */
class TranslationsSync extends Command
{
    protected $signature = 'trad:sync {--locale=en : Langue cible} {--target=EN-GB : Variante DeepL} {--force : Retraduire tous les segments (corrige les traductions existantes)}';
    protected $description = 'Extrait, traduit (DeepL) puis importe les nouveaux contenus dans la langue cible.';

    public function handle(): int
    {
        $locale = (string) $this->option('locale');
        $this->call('trad:extract', ['--locale' => $locale]);

        $file = storage_path('app/translations/extract-'.$locale.'.json');
        if (! is_file($file)) {
            $this->warn('Aucun contenu extrait.');

            return self::SUCCESS;
        }

        if ((string) config('services.deepl.key') !== '') {
            $args = ['file' => $file, '--target' => (string) $this->option('target')];
            if ($this->option('force')) {
                $args['--force'] = true;
            }
            $this->call('trad:translate', $args);
        } else {
            $this->warn('DEEPL_API_KEY absente : traduction DeepL sautée (import des traductions déjà remplies).');
        }

        $this->call('trad:import', ['file' => $file, '--locale' => $locale]);

        // Carte interactive (textes codés dans benin-atlas.js) : même pipeline.
        if ($locale === 'en') {
            $atlasArgs = ['--target' => (string) $this->option('target')];
            if ($this->option('force')) {
                $atlasArgs['--force'] = true;
            }
            $this->call('trad:atlas', $atlasArgs);
        }

        $this->info('Synchronisation des traductions terminée.');

        return self::SUCCESS;
    }
}
