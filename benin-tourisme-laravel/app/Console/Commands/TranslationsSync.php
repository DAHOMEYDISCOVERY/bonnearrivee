<?php

namespace App\Console\Commands;

use App\Support\Locales;
use Illuminate\Console\Command;

/**
 * Synchronise les traductions : extraction -> traduction DeepL -> import, pour
 * chaque langue étrangère du site (en, es, pt — voir App\Support\Locales).
 * Incrémental (seuls les nouveaux textes sont traduits). Lancée à la main
 * après un ajout de contenu, ou automatiquement chaque nuit (voir le
 * planificateur dans routes/console.php).
 *
 *   php artisan trad:sync                 # toutes les langues
 *   php artisan trad:sync --locale=es     # une seule langue
 */
class TranslationsSync extends Command
{
    protected $signature = 'trad:sync {--locale=all : Langue cible (en, es, pt) ou « all »} {--target= : Variante DeepL (une seule langue)} {--force : Retraduire tous les segments (corrige les traductions existantes)} {--refaire= : Retraduire seulement les segments contenant ces mots (séparés par des virgules)}';
    protected $description = 'Extrait, traduit (DeepL) puis importe les nouveaux contenus dans chaque langue étrangère.';

    public function handle(): int
    {
        $opt = (string) $this->option('locale');
        $locales = $opt === 'all' ? Locales::targets() : [$opt];

        foreach ($locales as $locale) {
            if (! Locales::isTarget($locale)) {
                $this->error("Langue inconnue : {$locale} (attendu : ".implode(', ', Locales::targets()).' ou all).');

                return self::FAILURE;
            }

            $this->newLine();
            $this->info('=== '.strtoupper($locale).' ('.(Locales::ALL[$locale]['name'] ?? $locale).') ===');
            $this->call('trad:extract', ['--locale' => $locale]);

            $file = storage_path('app/translations/extract-'.$locale.'.json');
            if (! is_file($file)) {
                $this->warn('Aucun contenu extrait.');
                continue;
            }

            if ((string) config('services.deepl.key') !== '') {
                $args = ['file' => $file, '--locale' => $locale];
                if (count($locales) === 1 && $this->option('target')) {
                    $args['--target'] = (string) $this->option('target');
                }
                if ($this->option('force')) {
                    $args['--force'] = true;
                }
                if ($this->option('refaire')) {
                    $args['--refaire'] = (string) $this->option('refaire');
                }
                if ($this->call('trad:translate', $args) !== self::SUCCESS) {
                    // Quota ou réseau : on importe ce qui est déjà traduit et on s'arrête là
                    // (la progression est enregistrée ; relancer reprend où l'on en était).
                    $this->call('trad:import', ['file' => $file, '--locale' => $locale]);
                    $this->warn("Traduction {$locale} interrompue : relancez « php artisan trad:sync --locale={$locale} » pour reprendre.");

                    return self::FAILURE;
                }
            } else {
                $this->warn('DEEPL_API_KEY absente : traduction DeepL sautée (import des traductions déjà remplies).');
            }

            $this->call('trad:import', ['file' => $file, '--locale' => $locale]);

            // Carte interactive (textes codés dans benin-atlas.js) : même pipeline.
            $atlasArgs = ['--locale' => $locale];
            if ($this->option('force')) {
                $atlasArgs['--force'] = true;
            }
            $this->call('trad:atlas', $atlasArgs);
        }

        $this->newLine();
        $this->info('Synchronisation des traductions terminée. Pensez à « php artisan search:index » pour la recherche.');

        return self::SUCCESS;
    }
}
