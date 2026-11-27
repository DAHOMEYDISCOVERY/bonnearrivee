{{--
    Météo et heure locale de Cotonou, dans les outils de l'en-tête (à côté de la langue
    et de la recherche). La température vient de \App\Support\Meteo (Open-Meteo, cache
    30 min) ; l'heure est celle du Bénin (UTC+1), avancée par un petit script.
    Si la météo est indisponible, seule l'heure s'affiche.
--}}
@php
    $__loc = app()->getLocale();
    $__m = \App\Support\Meteo::actuelle();
    $__ico = $__m ? \App\Support\Meteo::icone($__m['code'], $__m['jour']) : null;
    $__heure = now(\App\Support\Meteo::TIMEZONE)->format('H:i');
    $__t = [
        'fr' => ['a' => 'À Cotonou', 'h' => 'heure locale'],
        'en' => ['a' => 'In Cotonou', 'h' => 'local time'],
        'es' => ['a' => 'En Cotonú', 'h' => 'hora local'],
        'pt' => ['a' => 'Em Cotonou', 'h' => 'hora local'],
    ][$__loc] ?? ['a' => 'À Cotonou', 'h' => 'heure locale'];
    $__titre = $__t['a'].' : '.($__m ? $__m['temp'].' °C, '.mb_strtolower(\App\Support\Meteo::libelle($__ico, $__loc)).' — ' : '').$__t['h'].' '.$__heure;
@endphp
<li class="tool ba-meteo-tool">
    <div class="ba-meteo" title="{{ $__titre }}" role="group" aria-label="{{ $__titre }}" data-ba-meteo data-tz="{{ \App\Support\Meteo::TIMEZONE }}" data-prefixe="{{ $__t['a'] }}" data-h="{{ $__t['h'] }}"@if ($__m) data-temp="{{ $__m['temp'] }}"@endif>
@if ($__m)
        <span class="ba-meteo__temp" aria-hidden="true">
            <svg class="ba-meteo__ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($__ico)
@case('soleil')
                <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
@break
@case('lune')
                <path d="M20 14.5A8 8 0 0 1 9.5 4a8 8 0 1 0 10.5 10.5z"/>
@break
@case('eclaircie')
                <path d="M8 3v1.5M3.5 8H2M4.3 4.3l1 1M12.7 4.3l-1 1"/><path d="M5.6 10.4A3.5 3.5 0 0 1 11.6 6.6"/><path d="M8.5 20h9a3.5 3.5 0 0 0 .4-7 5 5 0 0 0-9.6 1.2A3 3 0 0 0 8.5 20z"/>
@break
@case('pluie')
                <path d="M7 15a4 4 0 0 1-.3-8 5.5 5.5 0 0 1 10.6 1.5A3.5 3.5 0 0 1 17.5 15"/><path d="M8 18l-1 2.5M12 18l-1 2.5M16 18l-1 2.5"/>
@break
@case('orage')
                <path d="M7 15a4 4 0 0 1-.3-8 5.5 5.5 0 0 1 10.6 1.5A3.5 3.5 0 0 1 17.5 15"/><path d="M12.5 13l-2 4h3l-2 4"/>
@break
@case('brume')
                <path d="M4 9h16M3 13h18M5 17h14"/>
@break
@default
                <path d="M7 18a4.5 4.5 0 0 1-.3-9 6 6 0 0 1 11.4 1.7A3.7 3.7 0 0 1 17.5 18z"/>
@endswitch
            </svg>
            <span class="ba-meteo__t"></span>
        </span>
        <span class="ba-meteo__sep" aria-hidden="true"></span>
@endif
        <time class="ba-meteo__heure" aria-hidden="true"></time>
    </div>
</li>
<style>
.ba-meteo{display:inline-flex;align-items:center;gap:.75em;height:29px;padding:0 .3em;background:none;color:#fff;font:600 15px/1 Montserrat,Helvetica,sans-serif;font-variant-numeric:tabular-nums;white-space:nowrap;text-shadow:0 1px 3px rgba(0,0,0,.45)}
/* Sur fond clair, le script reprend la couleur de la loupe et retire l'ombre. */
.ba-meteo.is-sombre{text-shadow:none}
.ba-meteo.is-sombre .ba-meteo__ic{filter:none}
.ba-meteo__temp{display:inline-flex;align-items:center;gap:.35em}
.ba-meteo__ic{width:19px;height:19px;flex:none;color:currentColor;filter:drop-shadow(0 1px 2px rgba(0,0,0,.4))}
.ba-meteo__sep{width:1px;height:15px;background:currentColor;opacity:.5}
.ba-meteo-tool{margin-right:.6rem}
@media (max-width:767.98px){
  .ba-meteo{height:26px;padding:0 .2em;font-size:13px;gap:.5em}
  .ba-meteo__ic{width:15px;height:15px}
  .ba-meteo-tool{margin-right:.35rem}
}
@media (max-width:599.98px){
  .ba-meteo__sep,.ba-meteo__heure{display:none}
  .ba-meteo:not(:has(.ba-meteo__temp)) .ba-meteo__heure{display:inline}
}
/* Téléphones : pas la place à côté du logo, de la langue et de la recherche. */
@media (max-width:479.98px){.ba-meteo-tool{display:none}}
</style>
<script>
(function () {
  var el = document.querySelector('[data-ba-meteo]');
  if (!el || !window.Intl) return;
  var h = el.querySelector('.ba-meteo__heure'), tp = el.querySelector('.ba-meteo__t'), fmt;
  // Valeurs écrites ici (et non dans le HTML) : la couche de traduction ne les voit pas.
  if (tp) tp.textContent = el.getAttribute('data-temp') + '\u00a0°C';
  try { fmt = new Intl.DateTimeFormat('fr-FR', { timeZone: el.getAttribute('data-tz'), hour: '2-digit', minute: '2-digit', hour12: false }); } catch (e) { return; }
  function maj() {
    var t = fmt.format(new Date());
    if (h.textContent !== t) {
      h.textContent = t;
      var titre = el.getAttribute('title') || '';
      titre = titre.replace(/\d{2}:\d{2}$/, t);
      el.setAttribute('title', titre); el.setAttribute('aria-label', titre);
    }
  }
  // Même couleur que la loupe voisine (blanche sur photo, foncée sur fond clair).
  function teinte() {
    var ic = document.querySelector('.woody-component-headnavs .search-icon');
    if (!ic) return;
    var c = getComputedStyle(ic).color, m = c.match(/\d+/g);
    var sombre = !!m && (+m[0] * 299 + +m[1] * 587 + +m[2] * 114) / 1000 < 140;
    el.style.color = c;
    el.classList.toggle('is-sombre', sombre);
    // Le sélecteur de langue voisin suit la même couleur.
    var lg = document.querySelector('.woody-component-headnavs .lang-tool');
    if (lg) { lg.style.color = c; lg.classList.toggle('is-sombre', sombre); }
  }
  teinte();
  document.addEventListener('DOMContentLoaded', teinte);
  window.addEventListener('load', teinte);
  // La loupe change de couleur avec une transition quand l'en-tête change d'état :
  // on relit sa couleur à la fin de la transition (et par sécurité un peu après).
  var tmr = null;
  function plusTard() { requestAnimationFrame(teinte); clearTimeout(tmr); tmr = setTimeout(teinte, 600); }
  window.addEventListener('scroll', plusTard, { passive: true });
  window.addEventListener('resize', plusTard, { passive: true });
  document.addEventListener('transitionend', function (e) {
    if (e.target && e.target.closest && e.target.closest('.woody-component-headnavs, header')) teinte();
  }, true);
  try { new MutationObserver(plusTard).observe(document.body || document.documentElement, { attributes: true, attributeFilter: ['class'] }); } catch (e) {}
  maj();
  setInterval(maj, 15000);
})();
</script>
