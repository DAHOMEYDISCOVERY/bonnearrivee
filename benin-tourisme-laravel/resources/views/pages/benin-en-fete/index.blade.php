{{-- Bénin en Fête — agenda des grands rendez-vous culturels du Bénin. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Bénin en Fête","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":9001,"post_title":"Bénin en Fête","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json" class="benin-schema-graph">{"@context":"https://schema.org","@type":"WebPage","url":"https://www.bonnearrivee.bj/benin-en-fete","name":"Bénin en Fête","description":"Les grands événements culturels du Bénin : Vodun Days, Festival des Masques, La Gaani, JISTNA, Weloveya."}</script>@endverbatim@endsection

@section('content')
@verbatim
<div class="content-wrapper no-terms">

    <section class="benin-fete-section" id="evenements">
        <div class="benin-fete-container">

            <div class="benin-fete-header">
                <div class="benin-fete-header__left">
                    <span class="benin-fete-pretitle">Les événements à découvrir au Bénin</span>
                    <h1 class="benin-fete-title">L'agenda qui rythme<br>la destination</h1>
                </div>
                <p class="benin-fete-lede">
                    Fêtes traditionnelles, grands rendez-vous : la saison culturelle du Bénin ne s'arrête jamais.
                </p>
            </div>

            <div class="benin-events-grid">
                <a href="/benin-en-fete/vodun-days" class="benin-event-card benin-event-card--large">
                    <img src="/assets/img/vodun-1200x900.webp"
                         alt="Vodun Days — ceremonie" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                    <div class="benin-event-card__overlay"></div>
                    <div class="benin-event-card__body">
                        <div class="benin-event-card__date">
                            <span class="benin-event-card__day benin-event-card__day--big">02-09</span>
                            <span class="benin-event-card__month">Janvier 2027</span>
                        </div>
                        <h3 class="benin-event-card__title benin-event-card__title--big">Vodun Days</h3>
                        <p class="benin-event-card__desc">Les Vodun Days, le premier rendez-vous international autour des arts, de la culture et de la spiritualité Vodun.</p>
                        <span class="benin-event-card__cta">En savoir plus →</span>
                    </div>
                </a>

                <a href="/benin-en-fete/weloveya" class="benin-event-card">
                    <img src="/assets/img/danse-1200x900.webp"
                         alt="Weloveya — danses et musique" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                    <div class="benin-event-card__overlay"></div>
                    <div class="benin-event-card__body">
                        <div class="benin-event-card__date">
                            <span class="benin-event-card__day">26-27</span>
                            <span class="benin-event-card__month">Décembre 2026</span>
                        </div>
                        <h3 class="benin-event-card__title">Weloveya</h3>
                        <span class="benin-event-card__cta">En savoir plus →</span>
                    </div>
                </a>

                <a href="/benin-en-fete/festival-des-masques" class="benin-event-card">
                    <img src="/assets/img/gelede-1200x675.webp"
                         alt="Festival des Masques — gelede" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                    <div class="benin-event-card__overlay"></div>
                    <div class="benin-event-card__body">
                        <div class="benin-event-card__date">
                            <span class="benin-event-card__day">07-08</span>
                            <span class="benin-event-card__month">Août 2027</span>
                        </div>
                        <h3 class="benin-event-card__title">Festival des Masques</h3>
                        <span class="benin-event-card__cta">En savoir plus →</span>
                    </div>
                </a>

                <a href="/benin-en-fete/jistna" class="benin-event-card">
                    <img src="/assets/img/jistna-ceremonie-1024x682.webp"
                         alt="JISTNA" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                    <div class="benin-event-card__overlay"></div>
                    <div class="benin-event-card__body">
                        <div class="benin-event-card__date">
                            <span class="benin-event-card__day">22-23</span>
                            <span class="benin-event-card__month">Août 2027</span>
                        </div>
                        <h3 class="benin-event-card__title">JISTNA</h3>
                        <span class="benin-event-card__cta">En savoir plus →</span>
                    </div>
                </a>

                <a href="/benin-en-fete/la-gaani" class="benin-event-card">
                    <img src="/assets/img/la-gaani-1024x682.jpg"
                         alt="La Gaani" loading="lazy" decoding="async" referrerpolicy="no-referrer" />
                    <div class="benin-event-card__overlay"></div>
                    <div class="benin-event-card__body">
                        <div class="benin-event-card__date">
                            <span class="benin-event-card__month">Août 2027</span>
                        </div>
                        <h3 class="benin-event-card__title">La Gaani</h3>
                        <span class="benin-event-card__cta">En savoir plus →</span>
                    </div>
                </a>
            </div>
        </div>

        <img src="/assets/site/img/svg/filigrane-b.svg" alt="" aria-hidden="true" class="benin-fete-watermark" />
    </section>

</div>
@endverbatim
@endsection
