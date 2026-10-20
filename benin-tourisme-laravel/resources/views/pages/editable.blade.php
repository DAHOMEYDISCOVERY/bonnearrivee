{{-- Vue générique pour les pages éditées via Filament.

     Le contrôleur bascule ici quand la page a `use_override = true` et un
     `content_override` non vide. Le HTML édité par le back-office est rendu
     dans le layout `app` — donc header, footer, gabarit intacts — au sein
     d'un composant `custom-text tpl_02` sobre, cohérent avec le reste du site.

     Le contenu est passé par {!! !!} : c'est du HTML éditorial, on lui fait
     confiance parce qu'il vient d'un back-office authentifié. --}}
@extends('layouts.app')

@section('bodyClass', 'page prod gabarit-basic_page has-custom-content')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-section page-section-0" id="pageSection-0">
        <section class="section padd-top-md padd-bottom-md">
            <header class="section-header-wrapper grid-container grid-default container-min">
                <div class="section-header text-center">
                    <div class="section-titles flex-container flex-dir-column align-middle align-center text-center">
                        <div class="section-texts has-title">
                            <h1 class="section-title">{{ $title }}</h1>
                        </div>
                    </div>
                </div>
            </header>
            <article class="section-layout-wrapper grid-container grid-default">
                <div class="woody-component woody-component-custom-text tpl_02 flex-container flex-dir-column align-center no-sessions">
                    <div class="grid-container padd-top-md padd-bottom-md">
                        <div class="grid-x grid-padding-x align-center">
                            <div class="cell large-10">
                                {!! $customContent !!}
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>
</div>
@endsection
