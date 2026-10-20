{{-- Généré par tools/convert.py — ne pas modifier à la main. --}}
@extends('layouts.app')

@section('bodyClass', 'home page page-id-86 menus-v2 prod gabarit-front_page')
@section('mainAttributes')role="main" class="content-type-page front_page" id="main-content"@endsection


@section('headGlobals')@verbatim<script>window.globals={"ancestors":{"chapter1":"Accueil","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"front_page","post_id":86,"post_title":"Accueil","post_type":"page","post_modified":"2026-03-05 10:33:13+01:00","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":2043,"weather_url":0},"woody_timezone":"Europe\/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":["FMA","ASC"],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}}</script>@endverbatim@endsection
@section('headDataLayer')@verbatim<script>window.dataLayer=window.dataLayer?window.dataLayer:[];window.dataLayer.push({"event":"globals","data":{"env":"prod","site_key":"benin","page":{"id_page":86,"name":"Accueil","page_type":"front_page","tags":{"places":[],"seasons":[],"themes":[],"targets":[]}},"lang":"fr"}});</script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json" class="benin-schema-graph">{"@context":"https:\/\/schema.org","@graph":[{"@type":"WebSite","@id":"https:\/\/www.bonnearrivee.bj\/#website","url":"https:\/\/www.bonnearrivee.bj","name":"Bonne Arriv\u00e9e","description":"Bienvenue sur le site de Dahomey Discovery, \u00e9diteur du portail Bonne Arriv\u00e9e. Ici, la vie s'\u00e9merveille, et mille surprises vous attendent.","publisher":{"@id":"https:\/\/www.bonnearrivee.bj\/#organization"},"potentialAction":[{"@type":"SearchAction","target":"https:\/\/www.bonnearrivee.bj\/rechercher\/?query={search_term_string}","query-input":"required name=search_term_string"}]},{"@type":["Organization","TravelAgency"],"@id":"https:\/\/www.bonnearrivee.bj\/#organization","name":"Dahomey Discovery","legalName":"Dahomey Discovery","alternateName":["Bonne Arriv\u00e9e"],"url":"https:\/\/www.bonnearrivee.bj","foundingDate":"2018","founder":{"@type":"Person","name":"Zoulfati HASSAN DERBY"},"description":"Agence de tourisme r\u00e9ceptive au B\u00e9nin. Circuits sur mesure, h\u00e9bergements, location de v\u00e9hicules, transferts et attractions culturelles. \u00c9diteur du portail Bonne Arriv\u00e9e.","logo":{"@type":"ImageObject","url":"https:\/\/www.bonnearrivee.bj\/assets\/site\/img\/logo\/logo-institutionnel.png","width":852,"height":1145},"address":{"@type":"PostalAddress","streetAddress":"Rond-point Akpakpa, Cin\u00e9 Concorde","addressLocality":"Cotonou","addressCountry":"BJ"},"email":"reservations@dahomeydiscovery.com","contactPoint":[{"@type":"ContactPoint","telephone":"+229-01-67-11-02-20","contactType":"customer service","areaServed":"BJ","availableLanguage":["fr"]},{"@type":"ContactPoint","telephone":"+229-01-54-29-29-29","contactType":"customer support","areaServed":"BJ","availableLanguage":["fr"]}],"sameAs":["https:\/\/www.facebook.com\/dahomeydiscovery","https:\/\/www.instagram.com\/dahomeydiscovery\/","https:\/\/dahomeydiscovery.com"]},{"@type":"TouristDestination","@id":"https:\/\/www.bonnearrivee.bj\/#destination","name":"B\u00e9nin","description":"Le B\u00e9nin s'\u00e9tire du golfe de Guin\u00e9e aux collines de l'Atacora. Palais royaux d'Abomey, cit\u00e9 lacustre de Ganvi\u00e9, parcs du Nord inscrits au patrimoine mondial : le pays r\u00e9unit sur un territoire \u00e9troit une diversit\u00e9 rare en Afrique de l'Ouest.","containedInPlace":{"@type":"Country","name":"B\u00e9nin"},"touristType":["Loisirs","Culture","Nature","Aventure"]}]}</script>@endverbatim@endsection
@section('headAlternate')@verbatim<link rel="alternate" href="/" hreflang="fr" />@endverbatim@endsection
@section('scripts')@verbatim

    <script src="/assets/benin-atlas.js?v=7fb8e61e"></script>
    <script src="/assets/benin-carrousels.js?v=a043a557"></script>
@endverbatim@endsection
@section('styles')@verbatim<link rel='stylesheet' id='benin-atlas-css' href='/assets/benin-atlas.css?v=ed180f70' media='all' />@endverbatim@endsection
@section('content')
    <x-accueil.hero />
    <x-accueil.atlas />
    <x-accueil.regions />
    <x-accueil.activites />
    <x-accueil.actualites />
    <x-aggregator.ecosysteme-block />
    <x-accueil.newsletter />
    <x-accueil.socialwall />
@endsection
