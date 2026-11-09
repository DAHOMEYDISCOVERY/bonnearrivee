<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Traduit les textes de la carte interactive (public/assets/benin-atlas.js) :
 * descriptions des 29 lieux, saisons conseillées, listes « à voir », familles,
 * fils conducteurs et libellés d'interface. Ces textes sont codés en dur dans
 * le JavaScript, donc hors de portée du traducteur de pages.
 *
 * Chaîne : extraction des chaînes françaises -> traduction DeepL (via
 * trad:translate, mêmes glossaire et quota) -> écriture du dictionnaire
 * public/assets/benin-atlas.en.json, chargé par la carte quand lang="en".
 *
 *   php artisan trad:atlas
 */
class TranslationsAtlas extends Command
{
    protected $signature = 'trad:atlas {--target=EN-GB : Variante DeepL} {--force : Retraduire tout}';
    protected $description = 'Traduit les textes de la carte interactive (benin-atlas.js) via DeepL.';

    /** Libellés d'interface (hors données) à traduire. */
    private const UI = [
        'Touchez un repère pour ouvrir la fiche du lieu',
        'Cliquez un repère pour ouvrir la fiche du lieu',
        ' — ouvrir la fiche sur la carte',
        'Voir sur la carte',
        'aucune fiche',
        'Fermer la fiche',
    ];

    public function handle(): int
    {
        $js = public_path('assets/benin-atlas.js');
        if (! is_file($js)) {
            $this->error("Introuvable : {$js}");

            return self::FAILURE;
        }

        $strings = $this->extractStrings((string) file_get_contents($js));
        if ($strings === []) {
            $this->warn('Aucune chaîne extraite de la carte.');

            return self::SUCCESS;
        }

        // Fichier d'extraction au format trad:translate ({path:{key:{fr,en}}}),
        // fusionné avec l'existant pour préserver les traductions déjà faites.
        $file = storage_path('app/translations/extract-atlas-en.json');
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : [];
        $data = is_array($data) ? $data : [];
        $page = is_array($data['__atlas'] ?? null) ? $data['__atlas'] : [];

        $wanted = [];
        foreach ($strings as $fr) {
            $k = md5($fr);
            $wanted[$k] = true;
            if (! isset($page[$k])) {
                $page[$k] = ['type' => 'rich', 'group' => 'atlas', 'fr' => $fr, 'en' => null];
            } else {
                $page[$k]['fr'] = $fr; // garde la traduction 'en' existante
            }
        }
        // Retire les clés qui ne sont plus dans la carte.
        $page = array_intersect_key($page, $wanted);
        $data['__atlas'] = $page;

        if (! is_dir(dirname($file))) {
            mkdir(dirname($file), 0775, true);
        }
        file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // Traduction DeepL (mêmes glossaire, quota et reprise que les pages).
        if ((string) config('services.deepl.key') !== '') {
            $args = ['file' => $file, '--target' => (string) $this->option('target')];
            if ($this->option('force')) {
                $args['--force'] = true;
            }
            $this->call('trad:translate', $args);
        } else {
            $this->warn('DEEPL_API_KEY absente : traduction DeepL sautée.');
        }

        // Écriture du dictionnaire { "texte fr" : "english text" }.
        $data = json_decode((string) file_get_contents($file), true);
        $map = [];
        foreach (($data['__atlas'] ?? []) as $entry) {
            $en = $entry['en'] ?? null;
            if (is_string($en) && trim($en) !== '') {
                $map[$entry['fr']] = $en;
            }
        }
        $out = public_path('assets/benin-atlas.en.json');
        file_put_contents($out, json_encode($map, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info(sprintf('Carte : %d/%d libellés traduits → %s', count($map), count($strings), $out));

        return self::SUCCESS;
    }

    /**
     * Extrait les chaînes françaises traduisibles de benin-atlas.js :
     * SITES[].desc/when/cap/see[], CATS[].label, ROUTES[].title/court/sub, UI.
     *
     * @return list<string>
     */
    private function extractStrings(string $src): array
    {
        $out = [];

        // SITES : tableau JSON (clés entre guillemets) — on isole par équilibrage.
        if (preg_match('/const\s+SITES\s*=/', $src, $m, PREG_OFFSET_CAPTURE)) {
            $start = strpos($src, '[', $m[0][1]);
            if ($start !== false) {
                $depth = 0;
                $n = strlen($src);
                for ($i = $start; $i < $n; $i++) {
                    $c = $src[$i];
                    if ($c === '[') {
                        $depth++;
                    } elseif ($c === ']') {
                        $depth--;
                        if ($depth === 0) {
                            break;
                        }
                    }
                }
                $sites = json_decode(substr($src, $start, $i - $start + 1), true);
                if (is_array($sites)) {
                    foreach ($sites as $s) {
                        foreach (['desc', 'when', 'cap'] as $field) {
                            if (! empty($s[$field]) && is_string($s[$field])) {
                                $out[] = $s[$field];
                            }
                        }
                        if (! empty($s['see']) && is_array($s['see'])) {
                            foreach ($s['see'] as $x) {
                                if (is_string($x) && trim($x) !== '') {
                                    $out[] = $x;
                                }
                            }
                        }
                    }
                }
            }
        }

        // CATS[].label
        if (preg_match('/const\s+CATS\s*=(.*?)const\s+GLYPHS/s', $src, $m)
            && preg_match_all('/label:\s*"([^"]+)"/', $m[1], $mm)) {
            foreach ($mm[1] as $v) {
                $out[] = $v;
            }
        }

        // ROUTES[].title/court/sub
        if (preg_match('/const\s+ROUTES\s*=(.*?)const\s+SITES/s', $src, $m)) {
            foreach (['title', 'court', 'sub'] as $field) {
                if (preg_match_all('/'.$field.':\s*"([^"]*)"/', $m[1], $mm)) {
                    foreach ($mm[1] as $v) {
                        if (trim($v) !== '') {
                            $out[] = $v;
                        }
                    }
                }
            }
        }

        foreach (self::UI as $u) {
            $out[] = $u;
        }

        return array_values(array_unique($out));
    }
}
