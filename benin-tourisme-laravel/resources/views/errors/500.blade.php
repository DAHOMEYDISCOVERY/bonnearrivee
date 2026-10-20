{{-- Page 500 : erreur serveur. Même gabarit que la 404, avec un message et
     des CTA neutres — l'utilisateur voit le site, pas une trace Whoops. --}}
@php
    $title = 'Une erreur est survenue | Bonne Arrivée';
    $description = "Le site a rencontré une erreur inattendue. Réessayez dans un instant ou revenez à la page d'accueil.";
    $activeMenu = null;
    $headProfile = 'standard';
    $customContent = null;
@endphp
@extends('layouts.app')

@section('bodyClass', 'page page-error-500 menus-v2 prod gabarit-page-error')
@section('mainAttributes')role="main" class="content-type-page page-error" id="main-content"@endsection

@section('content')
@verbatim
<div class="content-wrapper">
    <section class="section padd-top-xxl padd-bottom-xxl marg-top-none marg-bottom-none is-full-width bg-secondary">
        <article class="section-layout-wrapper grid-container">
            <div class="grid-x align-center">
                <div class="cell xlarge-8 text-center padd-all-lg">
                    <div class="page-main-titles marg-bottom-md">
                        <span class="page-main-pretitle block-span">Erreur serveur</span>
                        <h1 class="page-main-title">Une erreur inattendue est survenue</h1>
                    </div>
                    <div class="page-main-description padd-top-md padd-bottom-lg">
                        <p>Notre équipe technique en a été informée. Réessayez dans un instant,
                        ou reprenez la route vers la page d'accueil.</p>
                    </div>
                    <div class="cta-wrapper flex-container align-center wrap" style="gap:12px">
                        <a class="button primary" href="/">Retour à l'accueil</a>
                        <a class="button primary" href="/sejourner/toutes-les-infos-pratiques/nous-contacter">Nous contacter</a>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
@endverbatim
@endsection
