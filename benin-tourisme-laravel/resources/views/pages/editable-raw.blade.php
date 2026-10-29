{{-- Vue generique pour les pages editees via Filament, mode BRUT.

     Simple : injecte le HTML brut dans un <main>, sans Blade::render pour
     eviter les recursions de compilation qui ont deja casse tout le site
     (zend.max_allowed_stack_size reached). Les directives Blade dans
     content_override ne sont PAS evaluees - c est volontaire. --}}
@extends('layouts.app')

@section('bodyClass', 'page prod gabarit-basic_page has-custom-content has-raw-content')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
{!! $customContent ?? '' !!}
@endsection
