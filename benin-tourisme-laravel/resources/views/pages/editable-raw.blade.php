{{-- Vue generique pour les pages editees via Filament, mode BRUT.

     Injecte le HTML brut dans un <main>, sans Blade::render pour eviter
     les recursions de compilation (zend.max_allowed_stack_size reached).

     Fournit un stub minimal de window.globals pour que les scripts Woody
     (eskalad, main.mjs) ne plantent pas sur 'globals is not defined' quand
     la page override la vue d'origine (qui portait sa propre version). --}}
@extends('layouts.app')

@section('bodyClass', 'page prod gabarit-basic_page has-custom-content has-raw-content')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')
<script>window.globals={"ancestors":{"chapter1":"","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":0,"post_title":"","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":0,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":[],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}};</script>
@endsection

@section('content')
{!! $customContent ?? '' !!}
@endsection
