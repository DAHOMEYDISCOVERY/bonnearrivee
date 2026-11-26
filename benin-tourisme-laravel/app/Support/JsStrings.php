<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Textes affichés par le JavaScript des pages (titres de blocs, chiffres clés,
 * légendes de photos, libellés de boutons…). DeepL ne les voit pas dans le HTML :
 * ils sont écrits dans le code des scripts. Cette classe les repère dans les
 * scripts de la page (scripts en ligne, configurations JSON et nos fichiers
 * /assets/ba-*.js, /assets/benin-*.js), pour les faire traduire.
 *
 * Le repérage est volontairement prudent : seules les chaînes qui ressemblent à
 * du texte (pas du code, des sélecteurs, des classes ou des URL) sont retenues.
 * Une chaîne retenue à tort ne coûte qu'un peu de quota : elle n'est remplacée à
 * l'affichage que si exactement le même texte apparaît dans la page.
 */
final class JsStrings
{
    /** Fichiers JS du projet à analyser (hors bibliothèques et carte). */
    private const OWN_ASSET = '#^/assets/(ba-|benin-)(?!atlas)[\w.-]*\.js$#';

    /** @return list<string> textes français trouvés dans les scripts de la page */
    public static function fromHtml(string $html): array
    {
        $found = [];
        if (! preg_match_all('#<script\b([^>]*)>(.*?)</script\s*>#is', $html, $m, PREG_SET_ORDER)) {
            return [];
        }
        foreach ($m as $s) {
            $attrs = $s[1];
            $body = $s[2];
            if (preg_match('#\bsrc\s*=\s*["\']([^"\']+)["\']#i', $attrs, $src)) {
                $path = (string) parse_url(html_entity_decode($src[1]), PHP_URL_PATH);
                if (preg_match(self::OWN_ASSET, $path)) {
                    foreach (self::fromAsset($path) as $t) {
                        $found[$t] = true;
                    }
                }

                continue;
            }
            if (str_contains($attrs, 'data-i18n-skip')) {
                continue; // dictionnaires de traduction déjà écrits à la main
            }
            if (preg_match('#type\s*=\s*["\']?application/ld\+json#i', $attrs)) {
                continue; // données SEO, jamais affichées
            }
            if (trim($body) === '') {
                continue;
            }
            $isJson = (bool) preg_match('#type\s*=\s*["\']?application/json#i', $attrs);
            foreach (self::cached($body, $isJson) as $t) {
                $found[$t] = true;
            }
        }

        return array_keys($found);
    }

    /** @return list<string> */
    private static function cached(string $code, bool $isJson): array
    {
        $key = 'jsstrings:v1:'.($isJson ? 'j' : 's').':'.md5($code);
        try {
            return Cache::rememberForever($key, static fn () => $isJson ? self::fromJson($code) : self::fromJs($code));
        } catch (\Throwable) {
            return $isJson ? self::fromJson($code) : self::fromJs($code);
        }
    }

    /** @return list<string> */
    private static function fromAsset(string $path): array
    {
        $file = public_path(ltrim($path, '/'));
        if (! is_file($file)) {
            return [];
        }
        $key = 'jsstrings:v1:f:'.md5($file.'|'.filemtime($file));
        try {
            return Cache::rememberForever($key, static fn () => self::fromJs((string) file_get_contents($file)));
        } catch (\Throwable) {
            return [];
        }
    }

    /** @return list<string> */
    public static function fromJson(string $json): array
    {
        $data = json_decode(html_entity_decode(trim($json), ENT_QUOTES | ENT_HTML5, 'UTF-8'), true);
        if (! is_array($data)) {
            return self::fromJs($json);
        }
        $out = [];
        array_walk_recursive($data, static function ($v) use (&$out): void {
            if (is_string($v)) {
                foreach (self::pieces($v) as $p) {
                    $out[$p] = true;
                }
            }
        });

        return array_keys($out);
    }

    /**
     * Chaînes littérales d'un code JavaScript (commentaires et expressions
     * régulières ignorés), découpées en morceaux de texte.
     *
     * @return list<string>
     */
    public static function fromJs(string $code): array
    {
        $out = [];
        $n = strlen($code);
        $i = 0;
        $prev = ''; // dernier caractère significatif (pour distinguer division et regex)
        while ($i < $n) {
            $c = $code[$i];
            $d = $i + 1 < $n ? $code[$i + 1] : '';
            if ($c === '/' && $d === '/') {
                $e = strpos($code, "\n", $i);
                $i = $e === false ? $n : $e;

                continue;
            }
            if ($c === '/' && $d === '*') {
                $e = strpos($code, '*/', $i + 2);
                $i = $e === false ? $n : $e + 2;

                continue;
            }
            if ($c === '/' && ($prev === '' || str_contains('(,=:[!&|?{};+-*%<>~^', $prev))) {
                // Expression régulière : on la saute.
                $i++;
                $cls = false;
                while ($i < $n) {
                    $ch = $code[$i];
                    if ($ch === '\\') {
                        $i += 2;

                        continue;
                    }
                    if ($ch === "\n") {
                        break;
                    }
                    if ($ch === '[') {
                        $cls = true;
                    } elseif ($ch === ']') {
                        $cls = false;
                    } elseif ($ch === '/' && ! $cls) {
                        $i++;

                        break;
                    }
                    $i++;
                }
                while ($i < $n && ctype_alpha($code[$i])) {
                    $i++;
                }
                $prev = ')';

                continue;
            }
            if ($c === '"' || $c === "'" || $c === '`') {
                $q = $c;
                $i++;
                $buf = '';
                while ($i < $n) {
                    $ch = $code[$i];
                    if ($ch === '\\' && $i + 1 < $n) {
                        $buf .= self::escape($code, $i);

                        continue;
                    }
                    if ($ch === $q) {
                        $i++;

                        break;
                    }
                    if ($q !== '`' && $ch === "\n") {
                        break; // chaîne mal formée : on arrête là
                    }
                    if ($q === '`' && $ch === '$' && ($code[$i + 1] ?? '') === '{') {
                        // ${…} : coupe le texte, on saute l'expression.
                        $buf .= "\n";
                        $depth = 0;
                        $i += 2;
                        while ($i < $n) {
                            if ($code[$i] === '{') {
                                $depth++;
                            } elseif ($code[$i] === '}') {
                                if ($depth === 0) {
                                    $i++;

                                    break;
                                }
                                $depth--;
                            }
                            $i++;
                        }

                        continue;
                    }
                    $buf .= $ch;
                    $i++;
                }
                foreach (self::pieces($buf) as $p) {
                    $out[$p] = true;
                }
                $prev = 'a';

                continue;
            }
            if (! ctype_space($c)) {
                $prev = $c;
            }
            $i++;
        }

        return array_keys($out);
    }

    /** Décode une séquence d'échappement JS à la position $i (avance $i). */
    private static function escape(string $code, int &$i): string
    {
        $e = $code[$i + 1];
        if ($e === 'u') {
            if (($code[$i + 2] ?? '') === '{' && preg_match('/\G\{([0-9a-fA-F]+)\}/', $code, $m, 0, $i + 2)) {
                $i += 3 + strlen($m[1]) + 1;

                return mb_chr((int) hexdec($m[1]), 'UTF-8') ?: '';
            }
            $hex = substr($code, $i + 2, 4);
            $i += 6;
            if (ctype_xdigit($hex)) {
                $cp = (int) hexdec($hex);
                // Paire de substitution UTF-16.
                if ($cp >= 0xD800 && $cp <= 0xDBFF && substr($code, $i, 2) === '\\u') {
                    $lo = substr($code, $i + 2, 4);
                    if (ctype_xdigit($lo)) {
                        $i += 6;
                        $cp = 0x10000 + (($cp - 0xD800) << 10) + (hexdec($lo) - 0xDC00);
                    }
                }

                return mb_chr($cp, 'UTF-8') ?: '';
            }

            return '';
        }
        if ($e === 'x') {
            $hex = substr($code, $i + 2, 2);
            $i += 4;

            return ctype_xdigit($hex) ? (mb_chr((int) hexdec($hex), 'UTF-8') ?: '') : '';
        }
        $i += 2;

        return match ($e) {
            'n', 'r', 't' => "\n",
            default => $e,
        };
    }

    /**
     * Morceaux de texte affichable d'une chaîne (balises HTML retirées).
     *
     * @return list<string>
     */
    public static function pieces(string $s): array
    {
        $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $out = [];
        foreach (preg_split('/<[^>]*>|\n/u', $s) ?: [] as $p) {
            $p = trim((string) preg_replace('/\s+/u', ' ', $p));
            if (self::looksLikeText($p)) {
                $out[] = $p;
            }
        }

        return $out;
    }

    public static function looksLikeText(string $p): bool
    {
        $len = mb_strlen($p);
        if ($len < 2 || $len > 600 || ! preg_match('/\p{L}/u', $p)) {
            return false;
        }
        // Code, gabarits, sélecteurs, CSS, URL.
        if (preg_match('/[{};=<>$`\\\\|]|=>|&&|\(\)|\bfunction\b|\breturn\b|^\s*(var|let|const)\s/u', $p)) {
            return false;
        }
        if (in_array($p, ['use strict', 'Accept'], true)) {
            return false;
        }
        if (preg_match('/(^|[\s,>+~])[.#][a-zA-Z]/u', $p)) {
            return false; // sélecteur CSS (« main .card », « #id »)
        }
        if (preg_match('/^[a-z0-9 -]+$/', $p) && preg_match('/\s/', $p) && preg_match('/[0-9-]/', $p)) {
            return false; // classes ou sélecteurs (« ba-x ba-y », « main h1 »)
        }
        if (preg_match('#^(\.|\#|\[|\(|@|/|--|https?:|data-|aria-|mailto:|tel:|www\.)#i', $p)) {
            return false;
        }
        if (preg_match('/^[a-z-]+\s*:\s*\S/', $p)) {
            return false; // déclaration CSS
        }
        if (preg_match('/\s[.#][\w-]|[\w-]\.[a-z][\w-]*(\s|$|,)|\w\[\w/u', $p) && ! preg_match('/\s/u', preg_replace('/[.#][\w-]+/u', '', $p) ?? '')) {
            return false; // sélecteur
        }
        if (preg_match('/[\w-]+\.(webp|jpe?g|png|svg|gif|js|css|mjs|json|php|html?)\b/i', $p) && ! preg_match('/\s/', $p)) {
            return false; // nom de fichier
        }
        if (! preg_match('/\s/u', $p)) {
            // Un seul mot : seulement s'il commence par une majuscule et n'est
            // ni un identifiant (camelCase, tirets, chiffres) ni une constante.
            if (! preg_match('/^\p{Lu}[\p{Ll}\'’-]+[\p{Ll}]$|^\p{Lu}\p{Ll}*[\'’]?\p{Ll}*\s*[:!?.]?$/u', $p)) {
                return false;
            }
            if (preg_match('/^[A-Z][a-z]+[A-Z]/', $p)) {
                return false; // camelCase (DOMContentLoaded, IntersectionObserver…)
            }
            if (in_array($p, ['Escape', 'Enter', 'Tab', 'Infinity', 'Object', 'Array', 'String', 'Number', 'Date', 'Math', 'Promise', 'Element', 'Node', 'Error', 'Symbol', 'Map', 'Set', 'GET', 'POST', 'None', 'True', 'False', 'Null'], true)) {
                return false;
            }
        } elseif (preg_match('/^[a-z0-9 ,_:()*+-]+$/', $p) && preg_match('/[,:()*+]/', $p)) {
            return false; // listes de sélecteurs/valeurs (« header, footer, nav »)
        }

        return true;
    }
}
