{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}
{{--
    Gabarit commun aux 87 pages issues du thème Woody.

    Les liaisons ci-dessous sont reprises telles quelles du miroir WordPress, y
    compris ses irrégularités : le <header> y referme deux <div> ouverts avant lui,
    si bien que le document compte deux </div> de plus que de <div>. Les navigateurs
    l'admettent, et le rendu en dépend. Ne pas « corriger » sans revalider avec
    tools/compare.py.

    Quatre points d'insertion sont ouverts aux pages :
      mainAttributes — attributs du <main> ; les fiches offre n'ont ni role ni id
      styles       — feuille propre à la page, dans le <head>
      aside        — colonne latérale, après le <main> (fiches offre)
      afterFooter  — scripts après le pied de page (fiches offre)
      scripts      — scripts de fin de document
--}}
<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>@include('partials.head.' . $headProfile)</head>
<body class="@yield('bodyClass')">@verbatim
            
        
            
    
    @endverbatim<div class="main-wrapper flex-container flex-dir-column">@verbatim
                                @endverbatim@include('partials.headnavs')
@include('partials.header')@verbatim

            </div>





                        
        <div class="site-content">
                           
    @endverbatim<main @yield('mainAttributes')>@yield('content')</main>@verbatim
                
                                                @endverbatim@yield('aside')@verbatim<div class="site-footer-container">
    @endverbatim@include('partials.footer')@verbatim
</div>
                                            </div>

            </div>

            
            @endverbatim@yield('afterFooter')
@include('partials.overlays')
@include('partials.site-utils')
@yield('scripts')
@verbatim
<script>
/* Hover-prefetch : quand le pointeur survole un lien interne (ou qu'il obtient
   le focus clavier), on precharge la cible en tache de fond. Le clic devient
   quasi instantane sans surcharger le reseau au chargement de la page. */
(function () {
    if (!('IntersectionObserver' in window)) return;
    var prefetched = new Set();
    var origin = location.origin;

    function prefetch(href) {
        if (!href || prefetched.has(href)) return;
        try {
            var url = new URL(href, origin);
            if (url.origin !== origin) return;
            // exclure les liens d'ancres, fichiers, download, etc.
            if (/\.(jpg|jpeg|png|webp|gif|svg|pdf|zip|mp4|mp3)(\?|$)/i.test(url.pathname)) return;
            prefetched.add(href);
            var l = document.createElement('link');
            l.rel = 'prefetch';
            l.href = url.href;
            l.as = 'document';
            document.head.appendChild(l);
        } catch (e) { /* URL invalide */ }
    }

    function handler(e) {
        var a = e.target.closest && e.target.closest('a[href]');
        if (!a) return;
        var href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
        if (a.target === '_blank' || a.hasAttribute('download')) return;
        prefetch(a.href);
    }

    // Hover souris + focus clavier declenchent le prefetch
    document.addEventListener('pointerenter', handler, { capture: true, passive: true });
    document.addEventListener('focusin', handler, { capture: true, passive: true });
    // Sur mobile (touchstart), on prefetch immediatement avant que le doigt ne se leve
    document.addEventListener('touchstart', handler, { capture: true, passive: true });
})();
</script>
@endverbatim
</body>
</html>
