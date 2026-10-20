{{--
    Une image du site, avec son balisage schema.org et son crédit.

    Ce bloc apparaît 931 fois dans les pages : cartes, vignettes de menu, images de
    couverture, mur social. Il s'y présentait sous dix formes qui ne diffèrent que
    par trois choses — la stratégie de chargement, la présence d'une légende, et
    quelques attributs d'accessibilité.

    Trois stratégies de chargement cohabitent, héritées du thème d'origine :
      lazy    le chargement différé des cartes — une image d'attente, puis l'image
              réelle quand elle approche du regard, et un <noscript> pour les autres
      native  le chargement différé du navigateur, sans jeu de résolutions
      eager   les images de couverture, chargées en priorité

    `tools/roundtrip.php` vérifie figure par figure que le rendu équivaut à celui du
    thème d'origine, avant que la conversion ne remplace quoi que ce soit.

    Chaque directive termine sa ligne : PHP avale le saut de ligne qui suit `?>`,
    si bien que la mise en forme du gabarit ne ressort pas dans le rendu. Deux
    directives collées, elles, ne se compileraient pas — Blade exige une frontière
    de mot avant `@`.

    @param string      src          l'image affichée
    @param string      alt          texte alternatif
    @param string      loading      'lazy' (chargement différé), 'native' ou 'eager'
    @param string|null placeholder  image d'attente du chargement différé
    @param string|null srcset       jeu de résolutions, tel quel
    @param string      classes      classes de la figure
    @param bool        ariaHidden   masque la figure aux lecteurs d'écran
    @param bool        focusable    rend la figure atteignable au clavier
    @param string|null published    date de publication (schema.org)
    @param string|null description  description longue, masquée à l'affichage
    @param string|null caption      légende visible au survol
    @param string|null author       crédit photographique
    @param bool        captionHidden  la légende n'apparaît qu'au survol
    @param bool        nosnippet    exclut la légende des extraits des moteurs
    @param bool        instagram    légende du mur social : icône Instagram, crédit seul
--}}
@props([
    'src',
    'alt' => '',
    'loading' => 'lazy',
    'placeholder' => null,
    'srcset' => null,
    'classes' => 'isRel imageObject',
    'ariaHidden' => false,
    'focusable' => false,
    'published' => null,
    'description' => null,
    'caption' => null,
    'author' => null,
    'captionHidden' => false,
    'nosnippet' => false,
    'instagram' => false,
])
<figure itemscope itemtype="http://schema.org/ImageObject" @if ($ariaHidden) aria-hidden="true" @endif class="{{ $classes }}" @if ($focusable) tabindex="0" @endif>
@if ($loading === 'lazy')<img class="imageObject-img img-responsive a42-ac-replace-img" src="{{ $src }}" @if ($srcset) srcset="{{ $srcset }}" sizes="(max-width: 640px) 100vw, (max-width: 1200px) 50vw, 33vw" @endif alt="{{ $alt }}" loading="lazy" decoding="async" itemprop="thumbnail">@endif
@if ($loading === 'native')<img class="imageObject-img img-responsive" src="{{ $src }}" alt="{{ $alt }}" loading="lazy" decoding="async" itemprop="thumbnail">@endif
@if ($loading === 'eager')<img class="imageObject-img img-responsive a42-ac-replace-img" src="{{ $src }}" alt="{{ $alt }}" @if ($srcset) srcset="{{ $srcset }}" sizes="100vw" @endif fetchpriority="high" decoding="async" itemprop="thumbnail">@endif
@if ($published)<meta itemprop="datePublished" content="{{ $published }}">@endif
@if ($description !== null)<p class="hide imageObject-description spanned" itemprop="description" aria-hidden="true" data-nosnippet>{{ $description }}</p>@endif
@if ($caption !== null || $author !== null)
    <figcaption class="imageObject-caption flex-container align-middle isAbs bottom left{{ $captionHidden ? ' hide' : '' }}" @if ($nosnippet) data-nosnippet @endif>
        <span class="imageObject-icon {{ $instagram ? 'wicon wicon-011-instagram' : 'copyright-icon' }} flex-container align-center align-middle">{{ $instagram ? '' : '©' }}</span>
        @if ($caption !== null)<p class="spanned imageObject-text imageObject-description" itemprop="caption">{{ $caption }}</p>@endif
        @if ($caption !== null && $author !== null)<p class="spanned imageObject-text separator">|</p>@endif
        @if ($author !== null)<p class="spanned imageObject-text imageObject-author" itemprop="copyrightHolder">{{ $author }}</p>@endif
    </figcaption>
@endif
</figure>
