<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Évite l'affichage de l'ancienne mise en page sur les pages dont un texte long est
 * remis en forme par public/assets/ba-recit.js (configurations <script class="ba-recit-cfg">).
 *
 * Le script ne s'exécute qu'après la lecture complète de la page : entre-temps, le texte
 * d'origine (sans design) restait visible. Ici, côté serveur, on repère le bloc que le
 * script va remplacer (même règle que lui : le plus long élément correspondant à « src »,
 * puis son conteneur « hide ») et on le masque dès le premier affichage par une règle CSS
 * placée dans l'en-tête. ba-recit.js lève ce masquage dès qu'il a fini (classe
 * « ba-recit-pret ») ; par sécurité il est levé de toute façon au bout de 5 secondes.
 * Sans JavaScript, rien n'est masqué (la règle dépend de la classe « ba-js »).
 */
final class RecitPreHide
{
    public static function inject(string $html): string
    {
        if (! str_contains($html, 'ba-recit-cfg')) {
            return $html;
        }
        try {
            $css = Cache::remember('recit-prehide:v1:'.md5($html), 3600, static fn (): string => self::css($html));
        } catch (\Throwable $e) {
            report($e);

            return $html;
        }
        if ($css === '' || ! preg_match('#<head\b[^>]*>#i', $html, $hm, PREG_OFFSET_CAPTURE)) {
            return $html;
        }
        $tag = '<style id="ba-recit-attente">'.$css.'</style>'
            .'<script>(function(d){d.classList.add("ba-js");setTimeout(function(){d.classList.add("ba-recit-pret")},5000)})(document.documentElement);</script>';
        $at = $hm[0][1] + strlen($hm[0][0]);

        return substr($html, 0, $at).$tag.substr($html, $at);
    }

    /**
     * Rechargement d'une page sur laquelle on était descendu : le navigateur remet aussitôt
     * la position de lecture, alors que les scripts n'ont pas encore mis les textes en forme
     * (on voyait l'ancienne présentation, puis la vue sautait). On garde la page en haut le
     * temps de la mise en forme, puis on revient d'un coup à la position enregistrée.
     */
    public static function defilement(string $html): string
    {
        if (str_contains($html, 'id="ba-defilement"') || ! preg_match('#<head\b[^>]*>#i', $html, $hm, PREG_OFFSET_CAPTURE)) {
            return $html;
        }
        $js = '<script id="ba-defilement">(function(){try{'
            .'if(!("scrollRestoration" in history)||!window.sessionStorage)return;'
            .'var k="ba-y:"+location.pathname,d=document.documentElement;'
            .'history.scrollRestoration="manual";'
            .'addEventListener("pagehide",function(){try{sessionStorage.setItem(k,String(Math.round(scrollY)))}catch(e){}});'
            .'var n=performance.getEntriesByType?performance.getEntriesByType("navigation")[0]:null;'
            .'if(location.hash||!n||(n.type!=="reload"&&n.type!=="back_forward"))return;'
            .'var y=+sessionStorage.getItem(k)||0;if(y<50)return;'
            .'var fait=false;function aller(){if(fait)return;fait=true;try{scrollTo({top:y,left:0,behavior:"instant"})}catch(e){scrollTo(0,y)}}'
            .'function attendre(){'
            // Page avec mise en forme ba-recit : on attend qu'elle soit prête (au plus 4 s).
            .'if(document.querySelector("script.ba-recit-cfg")&&!d.classList.contains("ba-recit-pret")){'
            .'var mo=new MutationObserver(function(){if(d.classList.contains("ba-recit-pret")){mo.disconnect();setTimeout(aller,60);}});'
            .'mo.observe(d,{attributes:true,attributeFilter:["class"]});setTimeout(aller,4000);}'
            .'else setTimeout(aller,60);}'
            .'if(document.readyState==="complete")attendre();else addEventListener("load",attendre);'
            .'}catch(e){}})();</script>';
        $at = $hm[0][1] + strlen($hm[0][0]);

        return substr($html, 0, $at).$js.substr($html, $at);
    }

    private static function css(string $html): string
    {
        $doc = self::parse($html);
        if ($doc === null) {
            return '';
        }
        $selecteurs = [];
        foreach (self::query($doc, 'script.ba-recit-cfg') as $s) {
            $cfg = json_decode((string) $s->textContent, true);
            if (! is_array($cfg)) {
                continue;
            }
            $liste = array_is_list($cfg) ? $cfg : [$cfg];
            foreach ($liste as $c) {
                if (! is_array($c) || empty($c['src']) || ! is_string($c['src'])) {
                    continue;
                }
                $best = null;
                $len = -1;
                foreach (self::query($doc, $c['src']) as $e) {
                    if (self::closest($doc, $e, '.bar') !== null) {
                        continue;
                    }
                    $n = strlen((string) $e->textContent);
                    if ($n > $len) {
                        $len = $n;
                        $best = $e;
                    }
                }
                $min = (int) ($c['min'] ?? 600);
                if ($best === null || mb_strlen(trim((string) preg_replace('/\s+/u', ' ', (string) $best->textContent))) < $min * 0.5) {
                    continue;
                }
                $cache = (! empty($c['hide']) && is_string($c['hide'])) ? (self::closest($doc, $best, $c['hide']) ?? $best) : $best;
                $sel = self::chemin($cache);
                if ($sel !== '') {
                    $selecteurs[$sel] = true;
                }
            }
        }
        if ($selecteurs === []) {
            return '';
        }
        $pre = 'html.ba-js:not(.ba-recit-pret) ';

        return $pre.implode(','.$pre, array_keys($selecteurs)).'{display:none!important}';
    }

    /** Analyse HTML5 (PHP 8.4) si possible, sinon l'analyseur classique. */
    private static function parse(string $html): mixed
    {
        if (class_exists(\Dom\HTMLDocument::class)) {
            return \Dom\HTMLDocument::createFromString($html, LIBXML_NOERROR | LIBXML_COMPACT);
        }
        if (! class_exists(\Symfony\Component\CssSelector\CssSelectorConverter::class)) {
            return null;
        }
        $d = new \DOMDocument();
        $prev = libxml_use_internal_errors(true);
        $d->loadHTML('<?xml encoding="utf-8"?>'.$html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_COMPACT);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        return $d;
    }

    /** @return iterable<mixed> */
    private static function query(mixed $doc, string $selecteur): iterable
    {
        try {
            if ($doc instanceof \Dom\HTMLDocument) {
                return $doc->querySelectorAll($selecteur);
            }
            $xp = (new \Symfony\Component\CssSelector\CssSelectorConverter(true))->toXPath($selecteur);

            return (new \DOMXPath($doc))->query($xp) ?: [];
        } catch (\Throwable) {
            return [];
        }
    }

    private static function closest(mixed $doc, mixed $el, string $selecteur): mixed
    {
        try {
            if ($el instanceof \Dom\Element) {
                return $el->closest($selecteur);
            }
            $liste = [];
            foreach (self::query($doc, $selecteur) as $n) {
                $liste[] = $n;
            }
            for ($n = $el; $n instanceof \DOMElement; $n = $n->parentNode) {
                foreach ($liste as $m) {
                    if ($m->isSameNode($n)) {
                        return $n;
                    }
                }
            }
        } catch (\Throwable) {
        }

        return null;
    }

    /** Sélecteur CSS unique : body > tag:nth-of-type(n) > … jusqu'à l'élément. */
    private static function chemin(mixed $el): string
    {
        $parts = [];
        for ($n = $el; $n !== null && isset($n->tagName); $n = $n->parentNode) {
            $tag = strtolower((string) ($n->localName ?? $n->tagName));
            if ($tag === 'body' || $tag === 'html') {
                break;
            }
            $i = 1;
            for ($s = $n->previousSibling; $s !== null; $s = $s->previousSibling) {
                if (isset($s->tagName) && strtolower((string) ($s->localName ?? $s->tagName)) === $tag) {
                    $i++;
                }
            }
            if (! preg_match('/^[a-z][a-z0-9-]*$/', $tag)) {
                return '';
            }
            array_unshift($parts, $tag.':nth-of-type('.$i.')');
        }

        return $parts === [] ? '' : 'body > '.implode(' > ', $parts);
    }
}
