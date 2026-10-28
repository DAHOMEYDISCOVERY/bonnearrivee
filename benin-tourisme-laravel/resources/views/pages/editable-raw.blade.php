{{-- Vue generique pour les pages editees via Filament, mode BRUT.

     Le controleur bascule ici quand la page a use_override=true, un
     content_override non vide, et wrap_content=false (le defaut moderne).

     Le HTML edite est injecte DIRECTEMENT dans <main>, sans aucun wrapper
     woody-component. L'admin voit et sauvegarde exactement ce que le site
     affiche — classes, structures, sections, SVG inclus. Les directives Blade
     comme {{ $title }} ou @if sont interpretees. --}}
@extends('layouts.app')

@section('bodyClass', 'page prod gabarit-basic_page has-custom-content has-raw-content')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
{!! \Illuminate\Support\Facades\Blade::render($customContent ?? '', [
    'title'       => $title ?? '',
    'description' => $description ?? '',
    'activeMenu'  => $activeMenu ?? null,
]) !!}
@endsection
