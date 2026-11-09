<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Remplit automatiquement les champs « en » d'un fichier de traduction via
 * l'API DeepL (FR -> EN), en préservant le HTML et les noms propres béninois.
 *
 * - Clé API : config('services.deepl.key') (DEEPL_API_KEY dans .env, jamais
 *   exposée). Les clés gratuites se terminent par « :fx » (endpoint api-free).
 * - Glossaire : les termes de self::PROTECT sont encadrés de <keep> et DeepL
 *   les laisse intacts (ignore_tags=keep), hors attributs/URLs.
 * - Reprise : seuls les segments encore vides sont traduits ; le fichier est
 *   réécrit après chaque lot, donc une interruption se reprend sans perte.
 *
 *   php artisan trad:translate storage/app/translations/extract-en.json
 */
class TranslationsTranslate extends Command
{
    protected $signature = 'trad:translate {file : Fichier JSON produit par trad:extract}
        {--target=EN-GB : Langue cible DeepL (EN-GB ou EN-US)}
        {--batch=40 : Nombre de segments par requête}
        {--force : Retraduire même les segments déjà remplis}';

    protected $description = 'Traduit les segments FR->EN d\'un fichier de traduction via DeepL.';

    /** Noms propres, marques et termes culturels béninois laissés intacts. */
    private const PROTECT = [
        // Marques et noms de site (jamais traduits).
        'Le Petit Train de Cotonou', 'Petit Train de Cotonou', 'Dahomey Discovery', 'Bonne Arrivée',
        'Wanilo Studio', 'Amazone Airlines', 'Bénin Tours', 'Benin Tours', 'DD Care', 'Zoulfati Hassan Derby',
        'BonneArrivee', 'Bienvenue',
        // Toponymes et termes culturels béninois.
        'Porto-Novo', 'Grand-Popo', 'Abomey-Calavi', 'Sô-Ava', 'Danxomè', 'Dahomey', 'Pendjari',
        'Atacora', 'Natitingou', 'Ouidah', 'Abomey', 'Ganvié', 'Cotonou', 'Nokoué', 'Akpakpa',
        'Ahémé', 'Tanougou', 'Possotomè', 'Parakou', 'Bohicon', 'Lokossa', 'Djougou', 'Savè',
        'Dassa', 'Allada', 'Sèmè', 'Fidjrossè', 'Koutammakou', 'Betammaribe', 'Tata Somba',
        'Tata', 'Zangbeto', 'Zinsou', 'Guèlèdè', 'Gelede', 'Egungun', 'Vodun', 'Vodoun', 'Tofa',
    ];

    public function handle(): int
    {
        $key = (string) config('services.deepl.key');
        if ($key === '') {
            $this->error('Clé DeepL absente. Ajoutez DEEPL_API_KEY=... dans .env, puis dans config/services.php :');
            $this->line("    'deepl' => ['key' => env('DEEPL_API_KEY')],");
            $this->line('Puis : php artisan config:clear');

            return self::FAILURE;
        }

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

        // Références de tous les segments à traduire.
        $refs = [];
        foreach ($data as $path => &$map) {
            if (! is_array($map)) {
                continue;
            }
            foreach ($map as $k => &$entry) {
                if (! is_array($entry) || trim((string) ($entry['fr'] ?? '')) === '') {
                    continue;
                }
                if ($this->option('force') || ($entry['en'] ?? null) === null) {
                    $refs[] = &$entry;
                }
                unset($entry);
            }
            unset($map);
        }

        $pending = count($refs);
        if ($pending === 0) {
            $this->info('Rien à traduire (tout est déjà rempli). Utilisez --force pour retraduire.');

            return self::SUCCESS;
        }

        $endpoint = str_ends_with($key, ':fx')
            ? 'https://api-free.deepl.com/v2/translate'
            : 'https://api.deepl.com/v2/translate';
        $target = (string) $this->option('target');
        $batch = max(1, (int) $this->option('batch'));

        // Certificat racine : sous WAMP, curl.cainfo pointe parfois vers un
        // fichier inexistant (cURL 77). On fournit un CA valide livré avec le
        // projet ; sinon on laisse la vérification système (Linux/OVH OK).
        $verify = true;
        $ca = storage_path('app/cacert.pem');
        if (is_file($ca)) {
            $verify = $ca;
        } elseif (class_exists(\Composer\CaBundle\CaBundle::class)) {
            $verify = \Composer\CaBundle\CaBundle::getBundledCaBundlePath();
        }

        // Dédoublonnage : un texte identique répété sur plusieurs pages (menu,
        // pied de page, libellés) n'est envoyé qu'une fois à DeepL -> quota
        // préservé. Chaque traduction est ensuite recopiée sur tous ses doublons.
        $byFr = [];
        foreach ($refs as $idx => $ref) {
            $byFr[$ref['fr']][] = $idx;
        }
        $uniqueFrs = array_keys($byFr);
        $uniqueCount = count($uniqueFrs);

        $this->info("À traduire : {$pending} segments ({$uniqueCount} textes uniques, DeepL {$target}).");
        $bar = $this->output->createProgressBar($uniqueCount);
        $bar->start();

        $done = 0;
        $chunks = array_chunk($uniqueFrs, $batch);
        foreach ($chunks as $chunk) {
            $texts = array_map(fn ($fr) => $this->protect((string) $fr), $chunk);

            try {
                $resp = Http::asForm()
                    ->withOptions(['verify' => $verify])
                    ->withHeaders(['Authorization' => 'DeepL-Auth-Key '.$key])
                    ->timeout(60)
                    ->retry(2, 1500)
                    ->post($endpoint, [
                        'text' => array_values($texts),
                        'source_lang' => 'FR',
                        'target_lang' => $target,
                        'tag_handling' => 'html',
                        'ignore_tags' => 'keep',
                    ]);
            } catch (\Throwable $e) {
                $this->newLine();
                $this->error('Erreur réseau DeepL : '.$e->getMessage().' — progression enregistrée, relancez pour reprendre.');
                $this->persist($file, $data);

                return self::FAILURE;
            }

            if ($resp->status() === 456) {
                $this->newLine();
                $this->error('Quota DeepL épuisé (456). Progression enregistrée ; reprenez le mois prochain ou passez en Pro.');
                $this->persist($file, $data);

                return self::FAILURE;
            }
            if (! $resp->successful()) {
                $this->newLine();
                $this->error('DeepL a répondu '.$resp->status().' : '.mb_strimwidth($resp->body(), 0, 200).' — progression enregistrée.');
                $this->persist($file, $data);

                return self::FAILURE;
            }

            $translations = $resp->json('translations') ?? [];
            $i = 0;
            foreach ($chunk as $fr) {
                $t = $translations[$i]['text'] ?? null;
                if (is_string($t)) {
                    $en = $this->unprotect($t);
                    foreach ($byFr[$fr] as $idx) {
                        $refs[$idx]['en'] = $en;
                        $done++;
                    }
                }
                $i++;
                $bar->advance();
            }

            $this->persist($file, $data); // reprise après chaque lot
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Traduits : {$done}/{$pending}. Fichier mis à jour : {$file}");
        $this->line("Puis : php artisan trad:import {$file}");

        return self::SUCCESS;
    }

    /** Encadre les termes protégés de <keep> dans le texte (hors balises/attributs). */
    private function protect(string $text): string
    {
        $pattern = self::protectPattern();
        $parts = preg_split('/(<[^>]+>)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$text];
        foreach ($parts as $j => $part) {
            if ($part === '' || $part[0] === '<') {
                continue; // balise : on ne touche pas
            }
            // Un seul passage, alternance « plus long d'abord » : la casse
            // d'origine est conservée (#BIENVENUE reste #BIENVENUE) et aucun
            // terme n'est ré-encadré à l'intérieur d'un autre.
            $parts[$j] = preg_replace_callback($pattern, static fn ($m) => '<keep>'.$m[0].'</keep>', $part);
        }

        return implode('', $parts);
    }

    /** Motif d'alternance des termes protégés, triés du plus long au plus court. */
    private static function protectPattern(): string
    {
        $terms = self::PROTECT;
        usort($terms, static fn ($a, $b) => mb_strlen($b) <=> mb_strlen($a));
        $alt = implode('|', array_map(static fn ($t) => preg_quote($t, '/'), $terms));

        return '/(?<![\p{L}\-])('.$alt.')(?![\p{L}\-])/ui';
    }

    private function unprotect(string $text): string
    {
        return str_replace(['<keep>', '</keep>'], '', $text);
    }

    /** @param array<mixed> $data */
    private function persist(string $file, array $data): void
    {
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
