{{--
    En-tête du site (logo, bouton menu, menu principal et sous-menus).

    - Le BALISAGE est ici : toute modification de ce fichier change le rendu.
    - Le CONTENU (libellés, liens, cartes et leurs images) vient de
      \App\Support\SiteLayout::header() : la version enregistrée dans le
      back-office (page « En-tête et pied de page ») si elle existe, sinon les
      valeurs par défaut de config/site_layout.php.

    Remplace l'ancienne bascule « fragment HTML en base » (site_fragments) :
    celle-ci copiait tout le HTML en base, si bien que les modifications de ce
    fichier n'étaient plus jamais servies.
--}}
@php($__header = \App\Support\SiteLayout::header())
<header role="banner" class="woody-component-header menus-v2 site-header woody-burger" >
    <div class="main-menu-wrapper flex-container align-middle align-center">

                    <button class="menu-icon" data-toggle="main-menu" type="button" aria-label="Ouvrir/Fermer le menu principal" aria-controls="main-menu"><div class="title-bar-title" title="Accès au menu principal"><span class="menu-btn-title" data-menu-close="Menu" data-menu-open="Fermer">Menu</span></div><div class="menu-icon-bars-wrapper"><div class="menu-icon-bar top"></div><div class="menu-icon-bar middle middle-one"></div><div class="menu-icon-bar middle middle-two"></div><div class="menu-icon-bar bottom"></div></div></button>            <div class="is-brand-logo {{ ($activeMenu ?? null) === 'accueil' ? 'is-active' : '' }} ">
    <a class="menuLogo" href="/" target="_self" data-post-id="86">
        <span class="no-visible-text menu-logo-site-name isAbs">Bonne Arrivée</span>
        <span class="brand-logo"><img src="/assets/site/divers/app/themes/benin/src/img/logo/logo.png" alt="Bonne Arrivée — Portail du tourisme au Bénin" /></span>
    </a>
</div>

        <nav role="navigation" class="top-bar title-bar sliding-menu" id="main-menu">
            <ul class="main-menu-list flex-container vertical flex-dir-column">
@foreach ($__header['menu'] as $__item)
                        <li class="menu-item is-submenu-parent{{ ($activeMenu ?? null) !== null && (string) ($activeMenu ?? '') === (string) ($__item['post_id'] ?? '') ? ' is-active' : '' }}" role="button">
                        <a class="menuLink" href="{{ $__item['url'] }}" target="_self" data-post-id="{{ $__item['post_id'] ?? '' }}">
                                                <span class="menuLink-title">{{ $__item['label'] }}</span>
                                                    </a>
@if (! empty($__item['blocks']))
                                                    <div class="submenu-wrapper" aria-expanded="false">
                                <ul class="submenu-grid one-by-one " data-link-parent="{{ $__item['post_id'] ?? '' }}" data-title="{{ $__item['label'] }}" role="menu">
@foreach ($__item['blocks'] as $__block)
@if (($__block['type'] ?? '') === 'cards')
<li class="submenu-block submenu-block-images-list tpl_07 " data-position="{{ $__block['position'] ?? 'grid-col-start-1 grid-col-end-13 grid-row-start-1 grid-row-end-13' }}" role="menuitem" aria-label="menuitem">
        <ul class="submenu-block-content " data-columns="{{ $__block['columns'] ?? '2cols' }}" role="menu">
@foreach ($__block['items'] ?? [] as $__card)
@php($__img = \App\Support\SiteLayout::image($__card['image'] ?? ''))
                    <li class="card overlayedCard submenu-item submenu-item-delay">
            <a class="card-link isRel bg-black" href="{{ $__card['url'] }}" target="_self" data-post-id="{{ $__card['post_id'] ?? '' }}" aria-label="{{ \App\Support\SiteLayout::aria($__card['title']) }}" role="menuitem">
                                                            <figure itemscope itemtype="http://schema.org/ImageObject" aria-hidden="true" class="isRel imageObject cardMediaWrapper"
    ><noscript><img class="imageObject-img img-responsive a42-ac-replace-img" src="{{ $__img['src'] }}" alt="{{ $__card['alt'] ?? '' }}" itemprop="thumbnail"/></noscript><img class="imageObject-img img-responsive lazyload a42-ac-replace-img" src="/assets/site/img/blank/ratio_2_1.webp" data-src="{{ $__img['src'] }}" data-sizes="auto" @if ($__img['srcset']) data-srcset="{{ $__img['srcset'] }}" @endif alt="{{ $__card['alt'] ?? '' }}" itemprop="thumbnail"/>@if (! empty($__card['published']))<meta itemprop="datePublished" content="{{ $__card['published'] }}">@endif<p class="hide imageObject-description spanned" itemprop="description" aria-hidden="true" data-nosnippet>{{ $__card['description'] ?? ($__card['alt'] ?? '') }}</p><figcaption class="imageObject-caption flex-container align-middle isAbs bottom left hide" data-nosnippet><span class="imageObject-icon copyright-icon flex-container align-center align-middle">©</span><p class="spanned imageObject-text imageObject-description" itemprop="caption">{{ $__card['caption'] ?? ($__card['alt'] ?? '') }}</p></figcaption></figure>                                                    <div class="card-section submenu-item-section fullcenterbox">
                    <div class="card-titles"><div class="card-texts flex-container flex-dir-column"><span class="card-title">{{ $__card['title'] }}</span></div></div>                </div>
            </a>
        </li>
@endforeach
        </ul>
    </li>
@elseif (($__block['type'] ?? '') === 'links')
<li class="submenu-block submenu-block-links-list tpl_01 " data-position="{{ $__block['position'] ?? '' }}" role="menuitem" aria-label="menuitem">
@if (! empty($__block['title']))
        <div class="submenu-block-header text-left submenu-item-delay">
                    <a class="submenu-block-link" href="{{ $__block['url'] ?? '#' }}" target="_self" data-post-id="{{ $__block['post_id'] ?? '' }}">
                <div class="submenu-titles"><div class="submenu-texts flex-container flex-dir-column"><span class="submenu-title">{{ $__block['title'] }}</span></div></div>            </a>
            </div>
@endif
    <ul class="submenu-block-content justify-items-left" data-columns="{{ $__block['columns'] ?? '1cols' }}" role="menu">
@foreach ($__block['items'] ?? [] as $__link)
                    <li class="submenu-item submenu-item-delay">
            <a href="{{ $__link['url'] }}" target="_self" data-post-id="{{ $__link['post_id'] ?? '' }}" aria-label="{{ \App\Support\SiteLayout::aria($__link['title']) }}" role="menuitem">
                 <div class="submenu-titles"><div class="submenu-texts flex-container align-middle"><span class="submenu-title">{{ $__link['title'] }}</span></div></div>            </a>
        </li>
@endforeach
        </ul>
    </li>
@elseif (($__block['type'] ?? '') === 'buttons')
<li class="submenu-block submenu-block-buttons-list tpl_01 " data-position="{{ $__block['position'] ?? '' }}" role="menuitem" aria-label="menuitem">
        <ul class="submenu-block-content justify-items-left" data-columns="{{ $__block['columns'] ?? '1cols' }}" role="menu">
@foreach ($__block['items'] ?? [] as $__button)
                    <li class="submenu-item submenu-item-delay">
            <a class="button {{ ($__button['style'] ?? 'primary') === 'secondary' ? 'secondary' : 'primary' }}" href="{{ $__button['url'] }}" target="_self" data-post-id="{{ $__button['post_id'] ?? '' }}" aria-label="{{ \App\Support\SiteLayout::aria($__button['title']) }}" role="menuitem">
                                <span class="button-label" data-label="{{ $__button['title'] }}">{{ $__button['title'] }}</span>
            </a>
        </li>
@endforeach
        </ul>
    </li>
@endif
@endforeach
                                                                    </ul>
                            </div>
@endif
                                            </li>
@endforeach
                            </ul>
                    </nav>
    </div>
</header>
