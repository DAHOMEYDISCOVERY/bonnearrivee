<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Traductions des textes affichés par le JavaScript (voir JsStrings), par langue :
 * un dictionnaire « texte français => texte traduit » commun à tout le site.
 *
 * Rempli par trad:import (entrées « __js__ » du fichier de traduction) et
 * stocké dans storage/app/translations/js-<langue>.json (généré sur le serveur,
 * jamais versionné). À l'affichage, ApplyContentLayer n'envoie à la page que
 * les entrées dont elle a besoin, et un petit script remplace ces textes au fur
 * et à mesure que le JavaScript de la page les affiche.
 */
final class JsDictionary
{
    public const PATH = '__js__';

    private static function file(string $locale): string
    {
        return storage_path('app/translations/js-'.preg_replace('/[^a-z]/', '', $locale).'.json');
    }

    /** @return array<string, string> */
    public static function get(string $locale): array
    {
        $file = self::file($locale);
        if (! is_file($file)) {
            return [];
        }
        try {
            return Cache::rememberForever('jsdict:'.$locale.':'.filemtime($file), static function () use ($file): array {
                $data = json_decode((string) file_get_contents($file), true);

                return is_array($data) ? array_filter($data, 'is_string') : [];
            });
        } catch (\Throwable) {
            return [];
        }
    }

    /** @param array<string, string> $entries */
    public static function merge(string $locale, array $entries): int
    {
        $all = self::get($locale);
        $n = 0;
        foreach ($entries as $fr => $tr) {
            $fr = trim((string) $fr);
            $tr = trim((string) $tr);
            if ($fr === '' || $tr === '') {
                continue;
            }
            $all[$fr] = $tr;
            $n++;
        }
        $file = self::file($locale);
        if (! is_dir(dirname($file))) {
            mkdir(dirname($file), 0775, true);
        }
        ksort($all);
        file_put_contents($file, json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
        clearstatcache(true, $file);

        return $n;
    }

    /**
     * Script à placer en tête de page : remplace, dès qu'ils apparaissent, les
     * textes et attributs (alt, aria-label, title, placeholder) identiques à une
     * entrée du dictionnaire. Ne touche à rien d'autre.
     *
     * @param array<string, string> $dict
     */
    public static function script(array $dict): string
    {
        if ($dict === []) {
            return '';
        }
        $json = json_encode($dict, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);

        return '<script data-i18n-skip>(function(){var D='.$json.';'
            .'var A=["alt","aria-label","title","placeholder"],S=/^(SCRIPT|STYLE|NOSCRIPT|TEXTAREA|TEMPLATE)$/;'
            .'function k(v){return v.replace(/\s+/g," ").trim();}'
            .'function t(n){var v=n.nodeValue;if(!v||!/\S/.test(v))return;var p=n.parentNode;if(p&&S.test(p.nodeName))return;var r=D[k(v)];if(r===undefined||r===k(v))return;n.nodeValue=(v.match(/^\s*/)[0])+r+(v.match(/\s*$/)[0]);}'
            .'function a(e){for(var i=0;i<A.length;i++){var v=e.getAttribute(A[i]);if(v){var r=D[k(v)];if(r!==undefined&&r!==k(v))e.setAttribute(A[i],r);}}}'
            .'function w(r){if(r.nodeType===3){t(r);return;}if(r.nodeType!==1||S.test(r.nodeName))return;a(r);var it=document.createTreeWalker(r,5,null),n;while((n=it.nextNode())){if(n.nodeType===3)t(n);else if(!S.test(n.nodeName))a(n);}}'
            .'try{new MutationObserver(function(ms){for(var i=0;i<ms.length;i++){var m=ms[i];if(m.type==="characterData")t(m.target);else if(m.type==="attributes")a(m.target);else for(var j=0;j<m.addedNodes.length;j++)w(m.addedNodes[j]);}}).observe(document.documentElement,{childList:true,subtree:true,characterData:true,attributes:true,attributeFilter:A});}catch(e){}'
            .'function all(){if(document.body)w(document.body);}'
            .'document.addEventListener("DOMContentLoaded",all);window.addEventListener("load",all);'
            .'})();</script>';
    }
}
