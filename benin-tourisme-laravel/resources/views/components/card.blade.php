{{--
    Une carte — le composant le plus répandu du site : 613 dans les pages.

    Toutes les cartes du thème partagent la même ossature ; ce qui les distingue
    tient dans leurs classes et dans les éléments de texte qu'elles portent. Une
    carte de rubrique, une fiche d'hébergement et une vignette du mur social sont
    la même structure remplie différemment.

    Le média est passé en contenu, pas en propriété : une carte n'a pas à savoir
    comment une image se charge.

        <x-card classes="card basicCard tpl_02 isRel ratio_4_3" href="/explorez"
                title="Terre de traditions" button="Lire la suite">
            <x-media.figure src="…" alt="…" />
        </x-card>

    Les cartes dont la fiche a été retirée n'ont pas de lien : elles portent
    `unavailable`, qui les rend inertes tout en les laissant lisibles.

    @param string      classes         classes du conteneur
    @param string|null sheetId         identifiant de fiche touristique
    @param string|null postId          identifiant WordPress d'origine
    @param string|null cardPath        gabarit déclaré par le thème
    @param string|null displayOptions  options d'affichage du thème : du JSON déjà
                                       échappé, rendu verbatim
    @param string|null href            destination ; absente, la carte est inerte
    @param string|null target          cible du lien
    @param string|null carte           état de la carte sur le fond cartographique
    @param bool        unavailable     la fiche détaillée n'est pas disponible
    @param string      linkClasses     classes du lien
    @param bool        wrapMedia       enveloppe le média d'un conteneur
    @param string|null sectionClasses  classes du corps ; absentes, pas de corps
    @param bool        equalize        aligne la hauteur des cartes d'une même rangée
    @param string      titlesClasses   classes du bloc de titres
    @param string|null textsClasses    classes du bloc de textes ; absentes, pas de bloc
    @param string|null pretitle        sur-titre
    @param string|null title           titre
    @param string      titleTag        'span' ou 'h3'
    @param string|null type            nature de la fiche (hébergement, activité…)
    @param string|null subtitle        sous-titre
    @param string|null description     description, en HTML
    @param string|null town            commune
    @param string|null button          libellé du bouton d'action
--}}
@props([
    'classes',
    'sheetId' => null,
    'postId' => null,
    'cardPath' => null,
    'displayOptions' => null,
    'href' => null,
    'target' => null,
    'carte' => null,
    'unavailable' => false,
    'linkClasses' => 'card-link',
    'wrapMedia' => false,
    'sectionClasses' => null,
    'equalize' => false,
    'titlesClasses' => 'card-titles',
    'textsClasses' => null,
    'pretitle' => null,
    'title' => null,
    'titleTag' => 'span',
    'type' => null,
    'subtitle' => null,
    'description' => null,
    'town' => null,
    'button' => null,
])
<div class="{{ $classes }}" @if ($sheetId) data-sheetid="{{ $sheetId }}" @endif @if ($postId) data-postid="{{ $postId }}" @endif @if ($cardPath) data-card-path="{{ $cardPath }}" @endif @if ($displayOptions) data-card-display-options="{!! $displayOptions !!}" @endif>
    <a class="{{ $linkClasses }}" @if ($href) href="{{ $href }}" @else aria-disabled="true" @endif @if ($unavailable) data-fiche="indisponible" @endif @if ($carte) data-carte="{{ $carte }}" @endif @if ($target) target="{{ $target }}" @endif>
        @if ($wrapMedia)<div class="cardMediaWrapper">@endif
        {{ $slot }}
        @if ($wrapMedia)</div>@endif
        @if ($sectionClasses)<div class="{{ $sectionClasses }}" @if ($equalize) data-equalizer-watch @endif>@endif
        <div class="{{ $titlesClasses }}">
            @if ($textsClasses)<div class="{{ $textsClasses }}">@endif
            @if ($pretitle !== null)<span class="card-pretitle block-span">{{ $pretitle }}</span>@endif
            @if ($title !== null && $titleTag === 'h3')<h3 class="card-title">{{ $title }}</h3>@endif
            @if ($title !== null && $titleTag === 'span')<span class="card-title block-span">{{ $title }}</span>@endif
            @if ($type !== null)<span class="card-type block-span">{{ $type }}</span>@endif
            @if ($subtitle !== null)<span class="card-subtitle block-span">{{ $subtitle }}</span>@endif
            {{-- La description vient du contenu éditorial et porte sa propre mise en forme. --}}
            @if ($description !== null)<div class="card-description">{!! $description !!}</div>@endif
            @if ($town !== null)<span class="card-town block-span"><span class="wicon wicon-039-pin"></span><small>{{ $town }}</small></span>@endif
            @if ($textsClasses)</div>@endif
        </div>
        @if ($button !== null)<span class="card-button button"><span class="button-label" data-label="{{ $button }}">{{ $button }}</span></span>@endif
        @if ($sectionClasses)</div>@endif
    </a>
</div>
