{{-- Vue generique pour les pages editees via Filament, mode BRUT.

     Le controleur bascule ici quand la page a use_override=true, un
     content_override non vide, et wrap_content=false (le defaut moderne).

     Les sections critiques (headGlobals, headSchema, styles, scripts, ...)
     sont ré-extraites de la vue d'origine : sans ca, window.globals n'est
     plus injecte dans le <head> et les scripts Woody plantent sur
     ReferenceError, cassant toute l'interface (CSS absent, carte vide, etc.). --}}
@php
    use App\Models\Page;
    $__origSections = $originalView ? Page::extractAllSections((string) $originalView) : [];
@endphp

@extends('layouts.app')

@section('bodyClass', $__origSections['bodyClass'] ?? 'page prod gabarit-basic_page has-custom-content has-raw-content')
@section('mainAttributes'){!! $__origSections['mainAttributes'] ?? 'role="main" class="content-type-page basic_page" id="main-content"' !!}@endsection

@if (!empty($__origSections['headGlobals']))
    @section('headGlobals'){!! $__origSections['headGlobals'] !!}@endsection
@endif
@if (!empty($__origSections['headSchema']))
    @section('headSchema'){!! $__origSections['headSchema'] !!}@endsection
@endif
@if (!empty($__origSections['headAlternate']))
    @section('headAlternate'){!! $__origSections['headAlternate'] !!}@endsection
@endif
@if (!empty($__origSections['styles']))
    @section('styles'){!! $__origSections['styles'] !!}@endsection
@endif
@if (!empty($__origSections['scripts']))
    @section('scripts'){!! $__origSections['scripts'] !!}@endsection
@endif
@if (!empty($__origSections['afterFooter']))
    @section('afterFooter'){!! $__origSections['afterFooter'] !!}@endsection
@endif
@if (!empty($__origSections['aside']))
    @section('aside'){!! $__origSections['aside'] !!}@endsection
@endif

@section('content')
{!! \Illuminate\Support\Facades\Blade::render($customContent ?? '', [
    'title'       => $title ?? '',
    'description' => $description ?? '',
    'activeMenu'  => $activeMenu ?? null,
]) !!}
@endsection
