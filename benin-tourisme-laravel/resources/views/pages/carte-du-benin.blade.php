{{-- Page dédiée à la carte interactive du Bénin. Le composant est autonome :
     ni menu latéral ni bloc éditorial n'entoure la carte, pour lui laisser
     toute la largeur. --}}
@extends('layouts.app')

@section('bodyClass', 'page page-carte-du-benin menus-v2 prod gabarit-carte')
@section('mainAttributes')role="main" class="content-type-page carte-du-benin" id="main-content"@endsection

@section('styles')@verbatim<link rel='stylesheet' id='benin-carte-css' href='/assets/benin-carte.css?v=d067e2a2' media='all' />@endverbatim@endsection
@section('scripts')@verbatim<script src='/assets/benin-carte.js?v=48eb117a'></script>@endverbatim@endsection
@section('headSchema')@verbatim<script type="application/ld+json" class="benin-schema-graph">{"@context":"https:\/\/schema.org","@graph":[{"@type":"WebPage","@id":"https:\/\/www.bonnearrivee.bj\/carte-du-benin","url":"https:\/\/www.bonnearrivee.bj\/carte-du-benin","name":"Carte interactive du Bénin","headline":"Carte interactive du Bénin","inLanguage":"fr-FR","description":"Explorez le Bénin en quatre niveaux : les vols directs depuis Cotonou, l'Afrique de l'Ouest, le golfe de Guinée, et les 12 départements du pays.","isPartOf":{"@id":"https:\/\/www.bonnearrivee.bj\/#website"},"about":{"@id":"https:\/\/www.bonnearrivee.bj\/#destination"}},{"@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"Accueil","item":"https:\/\/www.bonnearrivee.bj\/"},{"@type":"ListItem","position":2,"name":"Carte du Bénin","item":"https:\/\/www.bonnearrivee.bj\/carte-du-benin"}]}]}</script>@endverbatim@endsection

@section('content')
    <h1 class="page-htitle show-for-sr">Carte interactive du Bénin</h1>
    <x-carte-du-benin />
@endsection
