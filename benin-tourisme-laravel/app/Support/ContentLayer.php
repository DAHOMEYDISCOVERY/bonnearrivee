<?php

declare(strict_types=1);

namespace App\Support;

/**
 * « Textes et images » : modifications de contenu appliquées à une page rendue.
 *
 * Toutes les pages du site — vues Woody, pages construites en code, fiches
 * événements, pages remplies depuis la base — produisent au final du HTML.
 * Cette classe y repère, dans le <main>, les éléments qu'une personne non
 * technique peut vouloir changer, et applique ce qui a été enregistré au
 * back-office :
 *
 *   - head        titre de l'onglet / Google, description Google (et leurs
 *                 équivalents Open Graph / Twitter) ;
 *   - text        un texte simple ;
 *   - rich        un paragraphe, titre ou élément de liste avec mise en forme
 *                 (gras, italique, liens…) édité d'un bloc ;
 *   - image       une image (fichier et texte alternatif) ;
 *   - link        l'adresse d'un lien ou d'un bouton.
 *
 * Le HTML n'est jamais ré-sérialisé : seules les plages repérées sont
 * remplacées, le reste du document (scripts, composants, attributs) reste
 * octet pour octet celui d'origine. Chaque élément porte une clé stable :
 * type + empreinte du contenu d'origine + rang parmi les éléments identiques.
 * Si le code change le contenu d'origine, la modification ne s'applique plus
 * (elle est signalée au back-office) au lieu de s'appliquer au mauvais endroit.
 *
 * Sont exclus : l'en-tête et le pied de page (écran dédié), les scripts,
 * styles, SVG, commentaires, et les blocs marqués data-ba-dynamique (contenus
 * alimentés par la base ou par les sites partenaires, à modifier à la source).
 */
final class ContentLayer
{
    public const SETTING_PREFIX = 'contenu:';

    public const INDEX_KEY = 'contenu:index';

    /** Éléments de bloc édités d'un tenant quand ils ne contiennent que du texte mis en forme. */
    private const RICH_BLOCKS = 'p|h1|h2|h3|h4|h5|h6|li|figcaption|blockquote|dt|dd|td|th|label|caption|summary';

    /** Balises de mise en forme autorisées dans un bloc « rich ». */
    public const INLINE_TAGS = ['a', 'strong', 'b', 'em', 'i', 'u', 'span', 'br', 'small', 'sup', 'sub', 'abbr', 'mark', 'time', 'q', 'cite', 'code', 's'];

    /** Zones jamais proposées à la modification. */
    private const PROTECTED_TAGS = ['script', 'style', 'svg', 'noscript', 'template', 'textarea', 'select'];

    public static function settingKey(string $path): string
    {
        return self::SETTING_PREFIX.self::normalisePath($path);
    }

    public static function normalisePath(string $path): string
    {
        $path = (string) parse_url($path, PHP_URL_PATH);
        $trimmed = trim($path, '/');

        return $trimmed === '' ? '/' : '/'.$trimmed;
    }

    /**
     * Éléments modifiables de la page, dans l'ordre du document.
     *
     * @return list<array{key:string,type:string,start:int,end:int,raw:string,value:string,alt?:string,group:string,label?:string,tag?:string}>
     */
    public static function extract(string $html, bool $forTranslation = false): array
    {
        $items = array_merge(self::extractHead($html), self::extractBody($html, $forTranslation));
        usort($items, static fn (array $a, array $b) => $a['start'] <=> $b['start']);

        return $items;
    }

    /**
     * Applique les modifications enregistrées.
     *
     * @param  array<string, array<string, mixed>>  $edits  clé => ['value' => …, 'alt' => …]
     */
    public static function apply(string $html, array $edits, bool $forTranslation = false): string
    {
        if ($edits === []) {
            return $html;
        }

        $replacements = [];
        foreach (self::extract($html, $forTranslation) as $item) {
            $edit = $edits[$item['key']] ?? null;
            if (! is_array($edit)) {
                continue;
            }
            foreach (self::replacementsFor($html, $item, $edit) as $r) {
                $replacements[] = $r;
            }
        }

        if ($replacements === []) {
            return $html;
        }

        // De la fin vers le début, pour que les positions restent valables ;
        // une plage qui en chevauche une autre déjà traitée est ignorée.
        usort($replacements, static fn (array $a, array $b) => $b[0] <=> $a[0]);
        $floor = PHP_INT_MAX;
        foreach ($replacements as [$start, $end, $text]) {
            if ($end > $floor) {
                continue;
            }
            $html = substr($html, 0, $start).$text.substr($html, $end);
            $floor = $start;
        }

        return $html;
    }

    /**
     * Nettoie un bloc mis en forme saisi au back-office : seules les balises de
     * mise en forme restent, sans gestionnaire d'événement ni lien javascript:.
     */
    public static function sanitiseRich(string $html): string
    {
        $allowed = '<'.implode('><', self::INLINE_TAGS).'>';
        $clean = strip_tags(str_replace(["\r\n", "\r"], "\n", $html), $allowed);
        $clean = (string) preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
        $clean = (string) preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $clean);
        $clean = (string) preg_replace_callback(
            '/\s+href\s*=\s*("([^"]*)"|\'([^\']*)\')/i',
            static function (array $m): string {
                $url = html_entity_decode($m[2] !== '' ? $m[2] : ($m[3] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');

                return self::isSafeUrl($url) ? ' href="'.htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8').'"' : '';
            },
            $clean,
        );

        return trim($clean);
    }

    public static function isSafeUrl(string $url): bool
    {
        $url = trim($url);
        if ($url === '') {
            return false;
        }

        return (bool) preg_match('#^(https?://|mailto:|tel:|/|\#|\?|\.{0,2}/)#i', $url)
            || ! preg_match('#^[a-z][a-z0-9+.-]*:#i', $url);
    }

    /** Texte comparable : entités décodées, espaces resserrés. */
    public static function plain(string $html): string
    {
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim((string) preg_replace('/\s+/u', ' ', str_replace("\u{00A0}", ' ', $text)));
    }

    // ------------------------------------------------------------------ head

    private static function extractHead(string $html): array
    {
        $items = [];
        $headEnd = stripos($html, '</head>');
        if ($headEnd === false) {
            return [];
        }
        $head = substr($html, 0, $headEnd);

        if (preg_match('#<title\b[^>]*>(.*?)</title>#is', $head, $m, PREG_OFFSET_CAPTURE)) {
            $items[] = [
                'key' => 'head:title', 'type' => 'head', 'label' => 'Titre de la page (onglet et Google)',
                'start' => $m[1][1], 'end' => $m[1][1] + strlen($m[1][0]),
                'raw' => $m[1][0], 'value' => self::plain($m[1][0]), 'group' => 'Référencement',
            ];
        }
        if ($attr = self::metaContent($head, 'name', 'description')) {
            $items[] = [
                'key' => 'head:description', 'type' => 'head', 'label' => 'Description Google',
                'start' => $attr[0], 'end' => $attr[1],
                'raw' => $attr[2], 'value' => self::plain($attr[2]), 'group' => 'Référencement',
            ];
        }

        return $items;
    }

    /** @return array{0:int,1:int,2:string}|null plage de la valeur de content="" */
    private static function metaContent(string $head, string $attr, string $name): ?array
    {
        $pattern = '#<meta\b[^>]*\b'.$attr.'\s*=\s*["\']'.preg_quote($name, '#').'["\'][^>]*>#i';
        if (! preg_match($pattern, $head, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }
        $tag = $m[0][0];
        if (! preg_match('#\bcontent\s*=\s*(["\'])(.*?)\1#is', $tag, $c, PREG_OFFSET_CAPTURE)) {
            return null;
        }
        $start = $m[0][1] + $c[2][1];

        return [$start, $start + strlen($c[2][0]), $c[2][0]];
    }

    // ------------------------------------------------------------------ body

    private static function extractBody(string $html, bool $forTranslation = false): array
    {
        [$from, $to] = self::mainRange($html, $forTranslation);
        if ($from === null) {
            return [];
        }

        $blocked = self::blockedRanges($html, $from, $to, $forTranslation);
        $isBlocked = static function (int $pos) use (&$blocked): bool {
            foreach ($blocked as [$s, $e]) {
                if ($pos >= $s && $pos < $e) {
                    return true;
                }
            }

            return false;
        };

        $items = [];
        $seen = [];
        $key = static function (string $type, string $basis) use (&$seen): string {
            $hash = substr(sha1($basis), 0, 16);
            $n = $seen[$type.$hash] = ($seen[$type.$hash] ?? -1) + 1;

            return $type.':'.$hash.':'.$n;
        };

        // 1. Blocs de texte mis en forme (un paragraphe avec du gras = un seul champ).
        $richRanges = [];
        $region = substr($html, $from, $to - $from);
        if (preg_match_all('#<('.self::RICH_BLOCKS.')\b[^>]*>(.*?)</\1\s*>#is', $region, $mm, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($mm as $m) {
                $innerStart = $from + $m[2][1];
                $inner = $m[2][0];
                if ($isBlocked($innerStart) || ! self::isInlineOnly($inner) || self::plain(strip_tags($inner)) === '') {
                    continue;
                }
                if (! preg_match('#<[a-z]#i', $inner)) {
                    continue; // texte seul : traité comme « text » plus bas
                }
                $items[] = [
                    'key' => $key('rich', self::plain(strip_tags($inner)).'|'.self::plain($inner)),
                    'type' => 'rich', 'tag' => strtolower($m[1][0]),
                    'start' => $innerStart, 'end' => $innerStart + strlen($inner),
                    'raw' => $inner, 'value' => trim($inner),
                ];
                $richRanges[] = [$innerStart, $innerStart + strlen($inner)];
            }
        }
        $inRich = static function (int $pos) use ($richRanges): bool {
            foreach ($richRanges as [$s, $e]) {
                if ($pos >= $s && $pos < $e) {
                    return true;
                }
            }

            return false;
        };

        // 2. Balises : images, liens ; et textes entre les balises.
        preg_match_all('#<!--.*?-->|<[^>]+>#s', $region, $tags, PREG_OFFSET_CAPTURE);
        $cursor = $from;
        $openTag = null;
        foreach ($tags[0] as [$tag, $offset]) {
            $abs = $from + $offset;

            // Texte entre la balise précédente et celle-ci.
            if ($abs > $cursor) {
                $segment = substr($html, $cursor, $abs - $cursor);
                $text = trim($segment);
                if ($text !== '' && preg_match('/[\p{L}\p{N}]/u', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
                    && ! $isBlocked($cursor) && ! $inRich($cursor)) {
                    $lead = strlen($segment) - strlen(ltrim($segment));
                    $items[] = [
                        'key' => $key('text', self::plain($text)),
                        'type' => 'text', 'tag' => $openTag,
                        'start' => $cursor + $lead, 'end' => $cursor + $lead + strlen($text),
                        'raw' => $text, 'value' => self::plain($text),
                    ];
                }
            }
            $cursor = $abs + strlen($tag);

            if (str_starts_with($tag, '<!--') || $isBlocked($abs)) {
                continue;
            }
            if (preg_match('#^<([a-z][a-z0-9-]*)#i', $tag, $tn)) {
                $openTag = strtolower($tn[1]);
            }

            if (preg_match('#^<img\b#i', $tag)) {
                $src = self::attr($tag, 'src');
                if ($src === null || $src[2] === '' || ! self::isOwnImage($src[2])) {
                    continue;
                }
                $alt = self::attr($tag, 'alt');
                $items[] = [
                    'key' => $key('image', $src[2]),
                    'type' => 'image',
                    'start' => $abs, 'end' => $abs + strlen($tag),
                    'raw' => $tag, 'value' => html_entity_decode($src[2], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'alt' => $alt ? self::plain($alt[2]) : '',
                ];
            } elseif (preg_match('#^<a\b#i', $tag) && ! $inRich($abs)) {
                $href = self::attr($tag, 'href');
                if ($href === null) {
                    continue;
                }
                $url = html_entity_decode($href[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($url === '' || $url === '#' || preg_match('#^javascript:#i', $url)) {
                    continue;
                }
                $items[] = [
                    'key' => $key('link', $url),
                    'type' => 'link',
                    'start' => $abs + $href[0], 'end' => $abs + $href[1],
                    'raw' => $href[2], 'value' => $url,
                    'label' => self::linkLabel($html, $abs + strlen($tag)),
                ];
            }
        }

        // Rubriques : chaque titre ouvre un groupe dans le formulaire.
        usort($items, static fn (array $a, array $b) => $a['start'] <=> $b['start']);
        $group = 'Début de la page';
        foreach ($items as &$item) {
            if (in_array($item['tag'] ?? null, ['h1', 'h2'], true)) {
                $group = mb_strimwidth(self::plain(strip_tags($item['value'])), 0, 80, '…');
            }
            $item['group'] = $group;
        }
        unset($item);

        return $items;
    }

    /** @return array{0:int|null,1:int|null} */
    private static function mainRange(string $html, bool $forTranslation = false): array
    {
        if ($forTranslation) {
            // Traduction : toute la page visible (en-tête + corps + pied).
            if (preg_match('#<body\b[^>]*>#i', $html, $mb, PREG_OFFSET_CAPTURE)) {
                $bstart = $mb[0][1] + strlen($mb[0][0]);
                $bend = strripos($html, '</body>');

                return [$bstart, $bend !== false && $bend > $bstart ? $bend : strlen($html)];
            }

            return [0, strlen($html)];
        }
        if (preg_match('#<main\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
            $start = $m[0][1] + strlen($m[0][0]);
            $end = strripos($html, '</main>');

            return [$start, $end !== false && $end > $start ? $end : strlen($html)];
        }
        if (preg_match('#<body\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE)) {
            $start = $m[0][1] + strlen($m[0][0]);
            $end = strripos($html, '</body>');

            return [$start, $end !== false && $end > $start ? $end : strlen($html)];
        }

        return [null, null];
    }

    /** @return list<array{0:int,1:int}> */
    private static function blockedRanges(string $html, int $from, int $to, bool $forTranslation = false): array
    {
        $ranges = [];
        $region = substr($html, $from, $to - $from);

        foreach (self::PROTECTED_TAGS as $tag) {
            if (preg_match_all('#<'.$tag.'\b[^>]*>.*?</'.$tag.'\s*>#is', $region, $mm, PREG_OFFSET_CAPTURE)) {
                foreach ($mm[0] as [$text, $offset]) {
                    $ranges[] = [$from + $offset, $from + $offset + strlen($text)];
                }
            }
        }
        if (preg_match_all('#<!--.*?-->#s', $region, $mm, PREG_OFFSET_CAPTURE)) {
            foreach ($mm[0] as [$text, $offset]) {
                $ranges[] = [$from + $offset, $from + $offset + strlen($text)];
            }
        }
        // Blocs alimentés par la base ou les sites partenaires : exclus de
        // l'édition, mais inclus en traduction (scripts/SVG restent masqués).
        if (! $forTranslation && preg_match_all('#<([a-z][a-z0-9]*)\b[^>]*\bdata-ba-dynamique\b[^>]*>#i', $region, $mm, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
            foreach ($mm as $m) {
                $start = $from + $m[0][1];
                $end = self::closingTagEnd($html, strtolower($m[1][0]), $start + strlen($m[0][0]), $to);
                $ranges[] = [$start, $end];
            }
        }

        return $ranges;
    }

    /** Fin de la balise fermante correspondant à <$tag> ouverte juste avant $pos. */
    private static function closingTagEnd(string $html, string $tag, int $pos, int $limit): int
    {
        $depth = 1;
        $pattern = '#<(/?)'.$tag.'\b[^>]*>#i';
        while ($depth > 0 && preg_match($pattern, $html, $m, PREG_OFFSET_CAPTURE, $pos)) {
            $pos = $m[0][1] + strlen($m[0][0]);
            if ($pos > $limit) {
                return $limit;
            }
            if (str_ends_with($m[0][0], '/>')) {
                continue;
            }
            $depth += $m[1][0] === '/' ? -1 : 1;
        }

        return $depth === 0 ? $pos : $limit;
    }

    private static function isInlineOnly(string $inner): bool
    {
        if (! preg_match_all('#</?([a-z][a-z0-9-]*)\b#i', $inner, $m)) {
            return true;
        }
        foreach ($m[1] as $tag) {
            if (! in_array(strtolower($tag), self::INLINE_TAGS, true)) {
                return false;
            }
        }

        return true;
    }

    /** Images du site (pas celles des sites partenaires ni de la base des restaurants). */
    private static function isOwnImage(string $src): bool
    {
        $src = html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (str_starts_with($src, 'data:') || str_contains($src, '/storage/')) {
            return false;
        }
        if (preg_match('#^(https?:)?//#i', $src)) {
            $host = (string) parse_url($src, PHP_URL_HOST);
            $own = (string) parse_url(function_exists('config') ? (string) config('app.url') : '', PHP_URL_HOST);

            return $own !== '' && strcasecmp($host, $own) === 0;
        }

        return true;
    }

    /** @return array{0:int,1:int,2:string}|null début et fin de la valeur dans la balise, valeur brute */
    private static function attr(string $tag, string $name): ?array
    {
        if (! preg_match('#\s'.preg_quote($name, '#').'\s*=\s*(["\'])(.*?)\1#is', $tag, $m, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        return [$m[2][1], $m[2][1] + strlen($m[2][0]), $m[2][0]];
    }

    /** Remplace la valeur d'un attribut existant (valeur déjà échappée). */
    private static function setAttr(string $tag, string $name, string $escaped): string
    {
        $range = self::attr($tag, $name);
        if ($range === null) {
            return $tag;
        }

        return substr($tag, 0, $range[0]).$escaped.substr($tag, $range[1]);
    }

    private static function linkLabel(string $html, int $pos): string
    {
        $close = stripos($html, '</a>', $pos);
        $inner = $close === false ? '' : substr($html, $pos, min($close - $pos, 2000));
        $label = self::plain(strip_tags($inner));

        return $label !== '' ? mb_strimwidth($label, 0, 70, '…') : 'Lien';
    }

    // --------------------------------------------------------------- apply

    /** @return list<array{0:int,1:int,2:string}> */
    private static function replacementsFor(string $html, array $item, array $edit): array
    {
        $value = isset($edit['value']) ? (string) $edit['value'] : null;

        switch ($item['type']) {
            case 'head':
                if ($value === null || trim($value) === '') {
                    return [];
                }
                $escaped = htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $out = [[$item['start'], $item['end'], $item['key'] === 'head:title' ? htmlspecialchars(trim($value), ENT_NOQUOTES | ENT_HTML5, 'UTF-8') : $escaped]];
                $head = substr($html, 0, (int) stripos($html, '</head>'));
                $twins = $item['key'] === 'head:title'
                    ? [['property', 'og:title'], ['name', 'twitter:title']]
                    : [['property', 'og:description'], ['name', 'twitter:description']];
                foreach ($twins as [$attr, $name]) {
                    if ($range = self::metaContent($head, $attr, $name)) {
                        $out[] = [$range[0], $range[1], $escaped];
                    }
                }

                return $out;

            case 'text':
                if ($value === null || trim($value) === '') {
                    return [];
                }

                return [[$item['start'], $item['end'], nl2br(htmlspecialchars(trim($value), ENT_NOQUOTES | ENT_HTML5, 'UTF-8'), false)]];

            case 'rich':
                if ($value === null) {
                    return [];
                }
                $clean = self::sanitiseRich($value);

                return self::plain(strip_tags($clean)) === '' ? [] : [[$item['start'], $item['end'], $clean]];

            case 'link':
                if ($value === null || ! self::isSafeUrl($value)) {
                    return [];
                }

                return [[$item['start'], $item['end'], htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8')]];

            case 'image':
                $tag = $item['raw'];
                if ($value !== null && trim($value) !== '' && $value !== $item['value']) {
                    $url = htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $tag = self::setAttr($tag, 'src', $url);
                    if (self::attr($tag, 'data-src') !== null) {
                        $tag = self::setAttr($tag, 'data-src', $url);
                    }
                    // Les variantes de taille pointent vers l'ancienne photo.
                    $tag = (string) preg_replace('#\s(data-)?srcset\s*=\s*(["\']).*?\2#is', '', $tag);
                    $tag = (string) preg_replace('#\ssizes\s*=\s*(["\']).*?\1#is', '', $tag);
                }
                if (array_key_exists('alt', $edit) && $edit['alt'] !== null) {
                    $alt = htmlspecialchars(trim((string) $edit['alt']), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $tag = self::attr($tag, 'alt') !== null
                        ? self::setAttr($tag, 'alt', $alt)
                        : '<img alt="'.$alt.'"'.substr($tag, 4);
                }

                return $tag === $item['raw'] ? [] : [[$item['start'], $item['end'], $tag]];
        }

        return [];
    }
}
