{{-- Page 404 : garde le layout du site pour que l'utilisateur retrouve le
     menu principal et le footer, plutôt que la page vide de Laravel. --}}
@php
    $title = 'Page introuvable | Bonne Arrivée';
    $description = "L'adresse demandée ne correspond à aucune page du site. Reprenez la route vers la page d'accueil ou consultez le sommaire.";
    $activeMenu = null;
    $headProfile = 'standard';
    $customContent = null;
@endphp
@extends('layouts.app')

@section('bodyClass', 'page page-error-404 menus-v2 prod gabarit-page-error')
@section('mainAttributes')role="main" class="content-type-page page-error" id="main-content"@endsection

@section('content')
@verbatim
<div class="content-wrapper">
    <section class="section padd-top-xxl padd-bottom-xxl marg-top-none marg-bottom-none is-full-width bg-secondary">
        <article class="section-layout-wrapper grid-container">
            <div class="grid-x align-center">
                <div class="cell xlarge-8 text-center padd-all-lg">
                    <div class="page-main-titles marg-bottom-md">
                        <span class="page-main-pretitle block-span">Erreur 404</span>
                        <h1 class="page-main-title">La page est introuvable</h1>
                    </div>
                    <div class="page-main-description padd-top-md padd-bottom-lg">
                        <p>L'adresse que vous avez saisie ne correspond à aucune page du site.<br>
                        Elle a peut-être été déplacée, ou le lien qui vous a amené ici est
                        obsolète.</p>
                    </div>
                    <div class="cta-wrapper flex-container align-center wrap" style="gap:12px">
                        <a class="button primary" href="/">Retour à l'accueil</a>
                        <a class="button primary" href="/explorez">Découvrez le Bénin</a>
                        <a class="button primary" href="/carte-du-benin">Carte du Bénin</a>
                        <a class="button primary" href="/sejourner">Organisez votre voyage</a>
                    </div>
                </div>
            </div>
        </article>
    </section>
</div>
@endverbatim
@endsection
