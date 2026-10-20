@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page wadunu-expired-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">
    <section class="wadunu-expired">
        <div class="wadunu-expired__inner">
            <p class="wadunu-expired__eyebrow">Wa Dùnù · Accès</p>
            <h1>Ce lien n'est plus valable</h1>
            <p>Votre accès au catalogue a expiré, ou le lien est incorrect. Prenez un nouvel abonnement pour retrouver toutes les adresses.</p>
            <a class="wadunu-expired__cta" href="/sejourner/restaurants">Reprendre un accès</a>
            <p class="wadunu-expired__note">Une question ? <a href="https://wa.me/2290167110220" target="_blank" rel="noopener noreferrer">Écrivez-nous sur WhatsApp</a>.</p>
        </div>
    </section>
</div>
@endsection
