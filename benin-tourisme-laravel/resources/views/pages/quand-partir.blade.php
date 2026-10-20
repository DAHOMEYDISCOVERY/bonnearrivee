{{-- Page "Quand partir ?" — grille climat 12 mois x 5 regions du Benin.
     Contenu editorial 100% original, base sur les moyennes climatiques
     connues du Benin (saison seche/pluies, harmattan, saison chaude). --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-quand-partir-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    // Grille climat : 5 regions x 12 mois. Niveaux : 'top' (ideal), 'good' (bien),
    // 'ok' (correct), 'wet' (saison des pluies), 'hot' (chaud & sec).
    $months = ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'];
    $regions = [
        ['nom' => 'Cotonou · Littoral',   'zone' => 'Grand Sud (Cotonou, Ouidah, Grand-Popo)', 'climat' => ['top','top','good','wet','wet','wet','ok','ok','wet','good','top','top']],
        ['nom' => 'Porto-Novo · Plateau', 'zone' => 'Sud-Est (Porto-Novo, Ketou, Adjara)',      'climat' => ['top','top','good','wet','wet','wet','ok','ok','wet','good','top','top']],
        ['nom' => 'Abomey · Centre',      'zone' => 'Centre (Abomey, Bohicon, Dassa)',           'climat' => ['top','top','good','good','wet','wet','wet','wet','wet','good','top','top']],
        ['nom' => 'Parakou · Nord-Centre','zone' => 'Nord (Parakou, Djougou, Nikki)',            'climat' => ['top','top','hot','hot','good','wet','wet','wet','wet','good','top','top']],
        ['nom' => 'Atacora · Grand Nord', 'zone' => 'Extreme Nord (Natitingou, Pendjari)',       'climat' => ['top','top','hot','hot','good','wet','wet','wet','good','good','top','top']],
    ];
    $legende = [
        'top'  => ['label' => 'Ideal',       'couleur' => '#00844d'],
        'good' => ['label' => 'Bien',        'couleur' => '#9fc85c'],
        'ok'   => ['label' => 'Correct',     'couleur' => '#fcd116'],
        'wet'  => ['label' => 'Pluies',      'couleur' => '#4f7fbe'],
        'hot'  => ['label' => 'Chaud & sec', 'couleur' => '#e8112d'],
    ];
@endphp

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Quand partir au Benin">
        <img src="/assets/img/atacora-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Quand partir ?</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Climat & saisons</p>
                <h1 class="ba-page-hero__title">Quand partir au Bénin ?</h1>
                <p class="ba-page-hero__lead">Douze mois, cinq régions. Une grille visuelle pour choisir la meilleure période selon votre destination et votre tolérance à la chaleur ou aux averses tropicales.</p>
            </div>
        </div>
    </section>

    <section class="ba-climat">
        <div class="ba-climat__legende" aria-label="Légende">
            @foreach($legende as $key => $l)
                <span class="ba-climat__pill ba-climat__pill--{{ $key }}">
                    <span class="ba-climat__dot" style="--c: {{ $l['couleur'] }}"></span>
                    {{ $l['label'] }}
                </span>
            @endforeach
        </div>

        <div class="ba-climat__table-wrap">
            <table class="ba-climat__table" role="table" aria-label="Grille climat 12 mois par région">
                <thead>
                    <tr>
                        <th scope="col" class="ba-climat__region-head">Région</th>
                        @foreach($months as $m)
                            <th scope="col" class="ba-climat__month">{{ $m }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $r)
                        <tr>
                            <th scope="row" class="ba-climat__region">
                                <span class="ba-climat__region-nom">{{ $r['nom'] }}</span>
                                <span class="ba-climat__region-zone">{{ $r['zone'] }}</span>
                            </th>
                            @foreach($r['climat'] as $i => $niveau)
                                @php $l = $legende[$niveau]; @endphp
                                <td class="ba-climat__cell" style="--c: {{ $l['couleur'] }}"
                                    title="{{ $r['nom'] }} — {{ $months[$i] }} : {{ $l['label'] }}"
                                    aria-label="{{ $months[$i] }} : {{ $l['label'] }}">
                                    <span class="ba-climat__cell-inner"></span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="ba-climat__notes">
            <div class="ba-climat__note">
                <h3>Saison sèche (novembre → mars)</h3>
                <p>La meilleure période pour la plupart des voyages : ciel dégagé, températures agréables (25–32 °C au sud, 20–35 °C au nord), routes praticables. C'est aussi la saison des grands rendez-vous : Vodun Days, La Gaani, Festival des Masques.</p>
            </div>
            <div class="ba-climat__note">
                <h3>Grande saison des pluies (mai → juillet)</h3>
                <p>Averses courtes mais intenses, souvent en fin d'après-midi. Végétation exubérante, cascades pleines, tarifs plus doux dans les lodges. Pistes du Nord parfois coupées quelques heures.</p>
            </div>
            <div class="ba-climat__note">
                <h3>Harmattan (décembre → février au Nord)</h3>
                <p>Vent sec venu du Sahara qui charge l'air de poussière fine. Températures fraîches la nuit dans l'Atacora (jusqu'à 15 °C). Idéal pour randonner, moins pour les photos de paysage à longue distance.</p>
            </div>
            <div class="ba-climat__note">
                <h3>Petite saison des pluies (septembre → octobre)</h3>
                <p>Averses plus rares que la grande saison, végétation encore verte, moins de touristes. Bon compromis pour un séjour équilibré nature + culture.</p>
            </div>
        </div>
    </section>

    <section class="ba-cta-simple">
        <p>Vous savez quand vous voulez venir ?</p>
        <a class="ba-cta-simple__btn" href="/sejourner/toutes-les-infos-pratiques/comment-venir">
            Voir comment venir au Bénin <span aria-hidden="true">→</span>
        </a>
    </section>
</div>
<x-aggregator.ecosysteme-block />
@endsection
