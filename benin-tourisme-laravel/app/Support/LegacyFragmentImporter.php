<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\SiteFragment;
use App\Models\SiteSetting;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\Schema;

/**
 * Reprend le contenu des anciens blocs HTML « header » et « footer »
 * (table site_fragments) dans le nouveau format structuré (site_settings).
 *
 * Sert une seule fois, au passage vers le nouveau système, pour ne pas
 * perdre ce qui avait été modifié dans le back-office. Les blocs d'origine
 * ne sont ni modifiés ni supprimés.
 */
final class LegacyFragmentImporter
{
    /** @return array{header: bool, footer: bool} ce qui a été importé */
    public static function import(bool $overwrite = false): array
    {
        $done = ['header' => false, 'footer' => false];
        if (! Schema::hasTable('site_fragments') || ! Schema::hasTable('site_settings')) {
            return $done;
        }

        foreach (['header' => SiteLayout::HEADER_KEY, 'footer' => SiteLayout::FOOTER_KEY] as $slug => $key) {
            if (! $overwrite && SiteSetting::query()->where('key', $key)->exists()) {
                continue;
            }
            $html = (string) SiteFragment::query()->where('slug', $slug)->value('content');
            if (trim($html) === '') {
                continue;
            }
            $data = $slug === 'header' ? self::parseHeader($html) : self::parseFooter($html);
            if ($data === null) {
                continue;
            }
            if ($slug === 'header') {
                $data = self::repairMenuLinks($data);
            }
            // On n'enregistre que si le contenu diffère des valeurs du code :
            // sinon le code reste la référence (et ses évolutions s'affichent).
            $default = $slug === 'header' ? SiteLayout::defaultHeader() : SiteLayout::defaultFooter();
            if ($data == $default) {
                continue;
            }
            SiteSetting::write($key, $data);
            $done[$slug] = true;
        }

        return $done;
    }

    /**
     * Liens du menu qui pointaient vers une page trop générale (audit BUG-16) :
     * « Chambres d'hôtes » et « Gîtes » ont leur propre page, et chaque entrée
     * « Où manger » mène à sa catégorie sur la page Restaurants. Seuls ces
     * liens exacts sont corrigés ; un lien choisi autrement reste tel quel.
     *
     * @param  array{menu: list<array<string, mixed>>}  $header
     * @return array{menu: list<array<string, mixed>>}
     */
    public static function repairMenuLinks(array $header): array
    {
        $fixes = [
            '/sejourner/hebergements' => [
                "Chambres d'hôtes" => '/sejourner/hebergements/chambres-dhotes',
                'Gîtes' => '/sejourner/hebergements/gites-et-lodges',
            ],
            '/sejourner/restaurants' => [
                'Restaurants gastronomiques' => '/sejourner/restaurants#gastronomique',
                'Maquis & cuisine locale' => '/sejourner/restaurants#maquis',
                'Cafés & bars' => '/sejourner/restaurants#cafes-bars',
            ],
        ];
        foreach ($header['menu'] as &$entry) {
            foreach ($entry['blocks'] ?? [] as &$block) {
                foreach ($block['items'] ?? [] as &$item) {
                    $url = (string) ($item['url'] ?? '');
                    $title = trim(str_replace('’', "'", (string) ($item['title'] ?? '')));
                    if (isset($fixes[$url][$title])) {
                        $item['url'] = $fixes[$url][$title];
                    }
                }
            }
        }
        unset($entry, $block, $item);

        return $header;
    }

    /** @return array{menu: list<array<string, mixed>>}|null */
    public static function parseHeader(string $html): ?array
    {
        $x = self::xpath($html);
        if ($x === null) {
            return null;
        }

        $menu = [];
        foreach ($x->query('//*[@id="main-menu"]/ul/li[contains(concat(" ", normalize-space(@class), " "), " menu-item ")]') as $li) {
            /** @var DOMElement $li */
            $a = self::first($x, './a[contains(@class,"menuLink")]', $li);
            if (! $a) {
                continue;
            }
            $item = [
                'label' => self::text(self::first($x, './/*[contains(@class,"menuLink-title")]', $a)),
                'url' => $a->getAttribute('href'),
                'post_id' => $a->getAttribute('data-post-id'),
                'blocks' => [],
            ];
            foreach ($x->query('.//ul[contains(@class,"submenu-grid")]/li[contains(@class,"submenu-block")]', $li) as $b) {
                /** @var DOMElement $b */
                $class = ' '.$b->getAttribute('class').' ';
                $type = str_contains($class, 'submenu-block-images-list') ? 'cards'
                    : (str_contains($class, 'submenu-block-links-list') ? 'links' : 'buttons');
                $content = self::first($x, './/ul[contains(@class,"submenu-block-content")]', $b);
                $block = [
                    'type' => $type,
                    'position' => $b->getAttribute('data-position'),
                    'items' => [],
                ];
                $head = self::first($x, './/*[contains(@class,"submenu-block-header")]//a', $b);
                if ($head) {
                    $block['title'] = self::text(self::first($x, './/*[contains(@class,"submenu-title")]', $head));
                    $block['url'] = $head->getAttribute('href');
                    $block['post_id'] = $head->getAttribute('data-post-id');
                }
                $block['columns'] = $content ? $content->getAttribute('data-columns') : '';
                if ($content) {
                    foreach ($x->query('./li', $content) as $it) {
                        $l = self::first($x, './/a', $it);
                        if (! $l) {
                            continue;
                        }
                        $entry = ['url' => $l->getAttribute('href'), 'post_id' => $l->getAttribute('data-post-id')];
                        if ($type === 'cards') {
                            $img = self::first($x, './/img[contains(@class,"lazyload")]', $it) ?? self::first($x, './/img', $it);
                            $meta = self::first($x, './/meta[@itemprop="datePublished"]', $it);
                            $entry += [
                                'title' => self::text(self::first($x, './/*[contains(@class,"card-title")]', $it)),
                                'image' => $img ? ($img->getAttribute('data-src') ?: $img->getAttribute('src')) : '',
                                'alt' => $img ? $img->getAttribute('alt') : '',
                                'published' => $meta ? $meta->getAttribute('content') : '',
                                'description' => self::text(self::first($x, './/p[@itemprop="description"]', $it)),
                                'caption' => self::text(self::first($x, './/figcaption//p', $it)),
                            ];
                        } elseif ($type === 'links') {
                            $entry['title'] = self::text(self::first($x, './/*[contains(@class,"submenu-title")]', $it));
                        } else {
                            $entry['title'] = self::text(self::first($x, './/*[contains(@class,"button-label")]', $it));
                            $entry['style'] = str_contains(' '.$l->getAttribute('class').' ', ' secondary ') ? 'secondary' : 'primary';
                        }
                        // aria-label non repris : il est recalculé depuis le titre.
                        $block['items'][] = $entry;
                    }
                }
                $item['blocks'][] = $block;
            }
            $menu[] = $item;
        }

        return $menu === [] ? null : ['menu' => $menu];
    }

    /** @return array<string, mixed>|null */
    public static function parseFooter(string $html): ?array
    {
        $x = self::xpath($html);
        if ($x === null) {
            return null;
        }

        $socials = [];
        foreach ($x->query('//*[contains(@class,"social-networks")]//a') as $a) {
            /** @var DOMElement $a */
            $icon = '';
            $span = self::first($x, './/span[contains(@class,"wicon")]', $a);
            if ($span) {
                foreach (preg_split('/\s+/', $span->getAttribute('class')) ?: [] as $c) {
                    if (str_starts_with($c, 'wicon-')) {
                        $icon = $c;
                    }
                }
            }
            $socials[] = ['icon' => $icon, 'url' => $a->getAttribute('href'), 'label' => $a->getAttribute('aria-label')];
        }

        $paragraphs = iterator_to_array($x->query('//*[contains(@class,"infos-block")]/p'));
        $company = '';
        $address = '';
        $phone = '';
        $email = '';
        foreach ($paragraphs as $i => $p) {
            /** @var DOMElement $p */
            $html = $p->ownerDocument->saveHTML($p) ?: '';
            if (preg_match('/tel:|@/i', $html)) {
                // Téléphone : le texte entre « Tél. » et le premier saut de ligne,
                // lien compris (rattrape un chiffre resté hors du lien).
                $lines = preg_split('/<br\s*\/?>/i', $html) ?: [];
                foreach ($lines as $line) {
                    $plain = trim(html_entity_decode(strip_tags($line)));
                    if ($plain === '') {
                        continue;
                    }
                    if (preg_match('/[\w.+-]+@[\w-]+\.[\w.-]+/', $plain, $m)) {
                        $email = $email ?: $m[0];
                    } elseif (preg_match('/\+?[\d][\d\s.]{6,}/', $plain, $m)) {
                        $phone = $phone ?: trim($m[0]);
                    }
                }
            } elseif ($company === '') {
                $company = self::text($p);
            } else {
                $lines = preg_split('/<br\s*\/?>/i', $html) ?: [];
                $address = implode("\n", array_filter(array_map(
                    static fn ($l) => trim(html_entity_decode(strip_tags($l))),
                    $lines,
                )));
            }
        }

        $buttons = [];
        foreach ($x->query('//*[contains(@class,"buttons-block")]//a') as $a) {
            /** @var DOMElement $a */
            $buttons[] = ['label' => self::text($a), 'url' => $a->getAttribute('href'), 'title' => $a->getAttribute('title')];
        }

        $data = [
            'tagline' => self::text(self::first($x, '//*[contains(@class,"brand-tagline")]')),
            'socials' => $socials,
            'company' => $company,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'buttons' => $buttons,
        ];

        return $data['company'] === '' && $buttons === [] ? null : $data;
    }

    private static function xpath(string $html): ?DOMXPath
    {
        // Retire ce qui n'est pas du HTML : interpolations Blade, verbatim.
        $html = preg_replace('/\{\{.*?\}\}/s', '', $html) ?? $html;
        $html = str_replace(['@verbatim', '@endverbatim'], '', $html);

        $doc = new DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $ok = $doc->loadHTML('<?xml encoding="UTF-8"><body>'.$html.'</body>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return $ok ? new DOMXPath($doc) : null;
    }

    private static function first(DOMXPath $x, string $query, ?\DOMNode $context = null): ?DOMElement
    {
        $n = $x->query($query, $context)->item(0);

        return $n instanceof DOMElement ? $n : null;
    }

    private static function text(?\DOMNode $n): string
    {
        return $n ? trim(preg_replace('/\s+/u', ' ', $n->textContent) ?? '') : '';
    }
}
