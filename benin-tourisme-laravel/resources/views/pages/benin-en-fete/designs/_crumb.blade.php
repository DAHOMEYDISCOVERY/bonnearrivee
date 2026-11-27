{{-- Fil d'Ariane : toujours sous l'image d'en-tête, jamais dessus. --}}
<div class="ba-crumbbar">
    <nav aria-label="Fil d'ariane">
        <ol>
            <li><a href="/">Accueil</a></li>
            <li aria-hidden="true">/</li>
            <li><a href="/benin-en-fete">Bénin en Fête</a></li>
            <li aria-hidden="true">/</li>
            <li aria-current="page">{{ $event['title'] }}</li>
        </ol>
    </nav>
</div>
{{--
    Texte de présentation de l'évènement. Dans « Textes et images », il a été saisi dans le
    premier élément du fil d'Ariane (à la place de « Accueil »), faute d'autre champ. On le
    sort du fil d'Ariane pour l'afficher en vraie section (début + « Lire la suite » en
    fenêtre), et « Accueil » redevient un lien. Le texte se modifie toujours au même endroit.
--}}
<style>
.bf-apropos{padding:clamp(28px,4vw,48px) 0 clamp(8px,2vw,16px)}
.bf-apropos__in{max-width:920px;margin:0 auto;padding:0 clamp(18px,4vw,32px)}
.bf-apropos__k{display:block;margin-bottom:.6rem;font:800 .78rem/1.2 Montserrat,Helvetica,Arial,sans-serif;letter-spacing:.16em;text-transform:uppercase;color:#00754a}
.bf-apropos__lead{margin:0;font:500 clamp(1.05rem,1.6vw,1.25rem)/1.65 Montserrat,Helvetica,Arial,sans-serif;color:#1d2420}
.bf-apropos .ba-lp-plus{margin-top:1.1rem}
</style>
<script data-i18n-skip>
(function () {
  var bar = document.querySelector('.ba-crumbbar');
  var li = bar && bar.querySelector('ol > li:first-child');
  if (!li) return;
  var norm = function (s) { return (s || '').replace(/\s+/g, ' ').trim(); };
  if (norm(li.textContent).length < 60) return; // fil d'Ariane normal
  var lang = (document.documentElement.lang || 'fr').slice(0, 2).toLowerCase();
  var T = {
    fr: { home: 'Accueil', k: 'L’essentiel', plus: 'Lire la suite' },
    en: { home: 'Home', k: 'At a glance', plus: 'Read more' },
    es: { home: 'Inicio', k: 'Lo esencial', plus: 'Leer más' },
    pt: { home: 'Início', k: 'O essencial', plus: 'Ler mais' }
  }[lang] || { home: 'Accueil', k: 'L’essentiel', plus: 'Lire la suite' };

  // Paragraphes : les sauts de ligne saisis deviennent des paragraphes.
  var tmp = document.createElement('div');
  tmp.innerHTML = li.innerHTML.replace(/<br\s*\/?>/gi, '\n');
  var paras = tmp.textContent.split(/\n+/).map(function (p) {
    return norm(p).replace(/([a-zà-ÿ0-9)])\.([A-ZÀ-Ý])/g, '$1. $2'); // « Bénin.Sur » → « Bénin. Sur »
  }).filter(Boolean);
  if (!paras.length) return;

  // « Accueil » redevient un lien.
  li.innerHTML = '';
  var a = document.createElement('a');
  a.href = lang === 'fr' ? '/' : '/' + lang;
  a.textContent = T.home;
  li.appendChild(a);

  // Début du texte : les deux premières phrases (ou le premier paragraphe s'il est court).
  var phrases = paras[0].match(/[^.!?]+[.!?]+(\s|$)|[^.!?]+$/g) || [paras[0]];
  var lead = norm(phrases.slice(0, 2).join(' '));
  if (lead.length > 420) lead = norm(phrases[0]);
  var reste = lead !== norm(paras.join(' '));

  var sec = document.createElement('section');
  sec.className = 'bf-apropos';
  sec.innerHTML = '<div class="bf-apropos__in"><span class="bf-apropos__k"></span><p class="bf-apropos__lead"></p></div>';
  sec.querySelector('.bf-apropos__k').textContent = T.k;
  sec.querySelector('.bf-apropos__lead').textContent = lead;
  if (reste) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'ba-lp-plus';
    b.setAttribute('aria-haspopup', 'dialog');
    b.innerHTML = '<span></span><svg width="14" height="14" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    b.firstChild.textContent = T.plus;
    b.addEventListener('click', function () {
      var h1 = document.querySelector('main h1');
      if (window.baLire) window.baLire(h1 ? norm(h1.textContent) : '', paras.join('\n\n'), b);
    });
    sec.querySelector('.bf-apropos__in').appendChild(b);
  }
  bar.parentNode.insertBefore(sec, bar.nextSibling);
})();
</script>
