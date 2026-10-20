{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}
@verbatim<woody-search-engine role="dialog" aria-modal="false" aria-live="polite" aria-labelledby="Recherche" aria-describedby="Recherchez un contenu sur le site" class="search-results-wrapper overlayed-results">
    <button class="search-results-close flex-container align-middle marg-top-sm marg-right-sm" tabindex="0" aria-label="Fermer le menu">
    <span class="wicon wicon-029-croix-fermer"></span>
    <span class="show-for-medium">Fermer</span>
</button>
     <woody-search-engine-input role="search" class="search-input-wrapper flex-container align-stretch grid-gap-sm wrap isRel">
    <div class="input-group isRel">
    <input class="input-group-field search-input results-input" title="Que recherchez-vous ?" type="text" placeholder="Que recherchez-vous ?" data-results="">
    <span class="input-group-label close-toggler" data-search-action="clear" tabindex="0">
       <span class="wicon wicon-029-croix-fermer"></span>
    </span>
    <button class="input-group-label results-toggler submit eskalad" title="Lancer la recherche" aria-label="Lancer la recherche" data-search-action="submit" data-event="click_recherche" data-label="" data-name="Recherche (desktop)">
        <span class="wicon wicon-024-loupe search-icon" aria-hidden="true"></span>
    </button>
    </div>
<woody-search-engine-input-dropdown class="input-dropdown isRel isAbs no-suggest-pages" data-height tabindex="0">
    <div class="input-dropdown-popular">
    <span class="input-dropdown-label popular">Recherches populaires</span>
    <ul data-woody-search-popular-list class="dropdown-popular-list flex-container flex-dir-column list-unstyled padd-bottom-sm">
        <li data-woody-search-popular-list-item-skeleton data-woody-search-popular-list-item class="dropdown-popular-item is-hidden" tabindex="0">
            <span data-woody-search-popular-list-item-label class="dropdown-popular-label">{% label %}</span>
        </li>
    </ul>
</div>
    </woody-search-engine-input-dropdown>
</woody-search-engine-input>
            <woody-search-engine-filters class="search-tabs-wrapper search-filters-wrapper has-filter-types">
        <div class="search-tabs-container search-filters-container flex-container">
            <div class="search-results-filters-title">Filtrer la recherche</div>
                                <div class="search-results-filters search-results-filters-types">
                    <span class="search-results-filters-label">Types de page</span>
                <ul class="flex-container list-unstyled wrap">
                            <li
                    data-woody-filter-group="types"
                    data-woody-filter-key="all_posts"
                                            data-woody-filter-count="total&#x2F;count"
                                        data-woody-filter-match=""
                    class="search-tab-title is-active"
                    tabindex="0"
                    aria-live="polite" 
                    aria-atomic="true" 
                    role="status"
                >Tout<span class="filter_count"></span></li>
                            <li
                    data-woody-filter-group="types"
                    data-woody-filter-key="page/basic_page"
                                            data-woody-filter-count="types&#x2F;page&#x2F;subtypes&#x2F;basic_page&#x2F;count"
                                        data-woody-filter-match="&#x7B;&quot;type&quot;&#x3A;&quot;page&quot;,&quot;subtype&quot;&#x3A;&quot;basic_page&quot;&#x7D;"
                    class="search-tab-title"
                    tabindex="0"
                    aria-live="polite" 
                    aria-atomic="true" 
                    role="status"
                >Articles<span class="filter_count"></span></li>
                            <li
                    data-woody-filter-group="types"
                    data-woody-filter-key="sheet/FMA"
                                            data-woody-filter-count="types&#x2F;sheet&#x2F;subtypes&#x2F;FMA&#x2F;count"
                                        data-woody-filter-match="&#x7B;&quot;type&quot;&#x3A;&quot;sheet&quot;,&quot;subtype&quot;&#x3A;&quot;FMA&quot;&#x7D;"
                    class="search-tab-title"
                    tabindex="0"
                    aria-live="polite" 
                    aria-atomic="true" 
                    role="status"
                >Agenda<span class="filter_count"></span></li>
                            <li
                    data-woody-filter-group="types"
                    data-woody-filter-key="page/playlist_tourism"
                                            data-woody-filter-count="types&#x2F;page&#x2F;subtypes&#x2F;playlist_tourism&#x2F;count"
                                        data-woody-filter-match="&#x7B;&quot;type&quot;&#x3A;&quot;page&quot;,&quot;subtype&quot;&#x3A;&quot;playlist_tourism&quot;&#x7D;"
                    class="search-tab-title"
                    tabindex="0"
                    aria-live="polite" 
                    aria-atomic="true" 
                    role="status"
                >Listes<span class="filter_count"></span></li>
                    </ul>
    </div>

                                </div>
            </woody-search-engine-filters>
     <woody-search-engine-results class="search-outcome-wrapper">
    <script type="text/template" class="search-results-model-template">
    <div class="cell is-loading">
        <div class="card basicCard tpl_01 isRel ratio_16_9" ><a class="card-link flex-container flex-dir-column" href="/" target="_self" rel="nofollow"><figure itemscope itemtype="http://schema.org/ImageObject" aria-hidden="true" class="isRel imageObject cardMediaWrapper"
    ><noscript><img class="imageObject-img img-responsive a42-ac-replace-img" src="/assets/site/addons/woody-addon-search/img/no-image.jpg" alt="no image" itemprop="thumbnail"/></noscript><img class="imageObject-img img-responsive lazyload a42-ac-replace-img" src="/assets/site/img/blank/ratio_16_9.webp" data-src="/assets/site/addons/woody-addon-search/img/no-image.jpg" data-sizes="auto" data-srcset="/assets/site/addons/woody-addon-search/img/no-image.jpg 360w, /assets/site/addons/woody-addon-search/img/no-image.jpg 640w, /assets/site/addons/woody-addon-search/img/no-image.jpg 1200w, /assets/site/addons/woody-addon-search/img/no-image.jpg 1920w" alt="no image" itemprop="thumbnail"/></figure><div class="card-section" data-equalizer-watch><div class="card-titles has-date"><div class="card-tripInfos-wrapper isAbs padd-all-sm"><div class="tripInfos-price"><span class="prefix"><small>à partir de</small></span><span class="value fat-text">{% price %}
                                <span class="unit euro">{% currency %}</span></span></div></div><div class="card-date"><span class="date-part card-startDate"><span class="card-startDate-day">{% start_date_day %}</span><span class="card-startDate-month">{% start_date_month %}</span><span class="card-startDate-year">{% start_date_year %}</span></span><span class="card-dateDivider wicon wicon-025-suivant"></span><span class="date-part card-endDate"><span class="card-endDate-day">{% end_date_day %}</span><span class="card-endDate-month">{% end_date_month %}</span><span class="card-endDate-year">{% end_date_year %}</span></span></div><div class="card-texts has-title has-subtitle has-desc"><span class="card-title block-span">{% title %}</span><span class="card-subtitle block-span">{% subtitle %}</span><div class="card-ratings">{% sheet_rating %}</div><div class="card-description">{% description %}</div><span class="card-town block-span"><span class="wicon wicon-039-pin"></span><small>{% town %}</small></span></div><div class="card-iti"><span class="card-iti-length"><small>{% iti_length %}</small></span><ul class="card-iti-infos list-unstyled text-darkgray"><li class="iti-infos"><div class="iti-infos-type"><small class="iti-infos-time">{% iti_duration %}</small><small class="iti-infos-level">{% iti_difficulty %}</small></div></li></ul></div></div></div></a></div>    </div>
</script>
<div class="flex-container align-center">
    <div class="search-results-container grid-x grid-padding-x grid-padding-y medium-up-2 large-up-3 xlarge-up-4 xxlarge-up-5"></div>
</div>
<div class="search-no-result" aria-live="polite" aria-atomic="true" role="alert">Aucun résultat</div>
<div data-woody-infinite-scroll-target data-woody-infinite-scroll-root-selector="woody-search-engine" data-woody-infinite-scroll-root-margin="400px" style="height:20px;width:100%;background:transparent;z-index:-1;"></div>
    <button class="search-button-scroll-top" data-woody-scroll-top>
    <div class="search-button-wrapper flex-container align-middle">
                    <div class="search-pagination" data-woody-search-pagination>
                <span class="search-pagination-min" data-woody-search-pagination-min></span>
                <span class="search-pagination-sep">/</span>
                <span class="search-pagination-max" data-woody-search-pagination-max></span>
            </div>
                            <span class="wicon wicon-095-fleche-retour-haut"></span>
                    </div>
</button>
</woody-search-engine-results>
</woody-search-engine>

    <div aria-live="polite" aria-labelledby="Sélecteur de langue" aria-describedby="Choisissez une langue pour naviguer sur le site" class="reveal full lang_switcher-reveal" data-reveal data-animation-in="fade-in" data-animation-out="fade-out" role="dialog" tabindex="0" aria-expanded="false">
    <div class="flex-container align-center align-middle">
        <ul class="lang_switcher-langs list-unstyled">
                                </ul>
        <button class="close-button" data-close aria-label="Fermer" type="button">
            <small>Fermer</small>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

    
        <script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"\/*"},{"not":{"href_matches":["\/wp\/wp-*.php","\/wp\/wp-admin\/*","\/app\/uploads\/benin\/*","\/app\/*","\/app\/plugins\/*","\/app\/themes\/benin\/*","\/app\/themes\/benin\/*","\/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>@endverbatim