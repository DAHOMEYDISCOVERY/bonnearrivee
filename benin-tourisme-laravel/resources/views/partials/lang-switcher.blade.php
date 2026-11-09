@php
    $__loc = $baLocale ?? app()->getLocale();
    $__fr  = $baUrlFr ?? '/';
    $__en  = $baUrlEn ?? '/en';
    // On passe par /langue/{loc} : le cookie de langue est (re)positionné, donc
    // le choix est conservé sur tout le site et on peut revenir au français.
    $__toFr = '/langue/fr?to=' . rawurlencode($__fr);
    $__toEn = '/langue/en?to=' . rawurlencode($__fr);
@endphp
<div class="ba-lang" data-ba-dynamique>
    <button type="button" class="ba-lang__toggle" aria-haspopup="true" aria-expanded="false" aria-label="{{ __('messages.language') }}">
        <span class="ba-lang__flag" aria-hidden="true">{{ $__loc === 'en' ? '🇬🇧' : '🇫🇷' }}</span>
        <span class="ba-lang__code">{{ strtoupper($__loc) }}</span>
        <span class="ba-lang__caret" aria-hidden="true">▾</span>
    </button>
    <ul class="ba-lang__menu" role="menu">
        <li role="none"><a role="menuitem" href="{{ $__toFr }}" hreflang="fr" class="ba-lang__item{{ $__loc === 'fr' ? ' is-active' : '' }}"><span aria-hidden="true">🇫🇷</span> Français</a></li>
        <li role="none"><a role="menuitem" href="{{ $__toEn }}" hreflang="en" class="ba-lang__item{{ $__loc === 'en' ? ' is-active' : '' }}"><span aria-hidden="true">🇬🇧</span> English</a></li>
    </ul>
</div>
<script>
(function(){
  var w=document.querySelector('.ba-lang'); if(!w) return;
  // Placer le sélecteur à côté de l'outil Recherche quand c'est possible.
  try{
    var tools=document.querySelector('.woody-component-headnavs .tools');
    if(tools){var li=document.createElement('li');li.className='tool lang-tool';li.appendChild(w);tools.insertBefore(li,tools.firstChild);w.classList.add('ba-lang--in-tools');}
  }catch(e){}
  var b=w.querySelector('.ba-lang__toggle');
  b.addEventListener('click',function(e){e.stopPropagation();var o=w.classList.toggle('is-open');b.setAttribute('aria-expanded',o?'true':'false');});
  document.addEventListener('click',function(){w.classList.remove('is-open');b.setAttribute('aria-expanded','false');});
})();
</script>
@if (($baLocale ?? app()->getLocale()) === 'en')
{{-- En anglais : libellés d'interface portés par des attributs (placeholder,
     aria-label…) et libellés du menu de recherche produits par le JavaScript
     Woody — hors de portée du traducteur serveur, traduits côté client. --}}
<script>
(function(){
  // Libellés d'attributs (statiques).
  var ATTR = {'Fermer la recherche':'Close search','Effacer':'Clear','Lancer la recherche':'Search','Recherche':'Search'};
  // Textes insérés dynamiquement par le menu de recherche.
  var TEXT = {
    'Voir tous les résultats sur la page recherche':'See all results on the search page',
    'Voir tous les résultats':'See all results',
    'Aucun résultat':'No results',
    'Fermer':'Close'
  };
  function fixAttrs(root){
    try{
      root.querySelectorAll('[data-menu-open]').forEach(function(el){ el.setAttribute('data-menu-open','Close'); });
      var ph='What are you looking for?';
      root.querySelectorAll('[data-ba-search-overlay] input[type="search"], .woody-component-esSearch-block input[type="search"]').forEach(function(el){ el.setAttribute('placeholder',ph); el.setAttribute('title',ph); });
      root.querySelectorAll('[aria-label],[title],[data-label]').forEach(function(el){
        var a=el.getAttribute('aria-label'); if(a&&ATTR[a]) el.setAttribute('aria-label',ATTR[a]);
        var t=el.getAttribute('title'); if(t&&ATTR[t]) el.setAttribute('title',ATTR[t]);
        var d=el.getAttribute('data-label'); if(d&&ATTR[d]) el.setAttribute('data-label',ATTR[d]);
      });
      // Libellé visible de l'outil Recherche (span rempli par CSS/JS depuis data-label).
      root.querySelectorAll('.woody-component-esSearch-block .button-label, .woody-component-esSearch-block .show-for-sr').forEach(function(el){ if(el.textContent.trim()==='Recherche'){ el.textContent='Search'; } });
    }catch(e){}
  }
  function fixText(node){
    try{
      if(node.nodeType===1 && (!node.children || node.children.length===0)){
        var s=node.textContent;
        for(var k in TEXT){ if(s.indexOf(k)!==-1){ node.textContent=s.split(k).join(TEXT[k]); break; } }
        return;
      }
      if(node.nodeType===1 && node.childNodes){ node.childNodes.forEach(fixText); }
    }catch(e){}
  }
  function run(){
    fixAttrs(document);
    var scope=document.querySelector('[data-ba-search-overlay]')||document.querySelector('.woody-component-esSearch-block')||document.body;
    fixText(scope);
    // Filet : le composant de recherche Woody peut s'initialiser après coup.
    setTimeout(function(){ fixAttrs(document); fixText(scope); }, 500);
    try{
      new MutationObserver(function(muts){
        muts.forEach(function(m){ m.addedNodes && m.addedNodes.forEach(function(n){ if(n.nodeType===1){ fixText(n); fixAttrs(n); } }); });
      }).observe(scope,{childList:true,subtree:true});
    }catch(e){}
  }
  if(document.readyState!=='loading'){ run(); } else { document.addEventListener('DOMContentLoaded',run); }
})();
</script>
@endif
