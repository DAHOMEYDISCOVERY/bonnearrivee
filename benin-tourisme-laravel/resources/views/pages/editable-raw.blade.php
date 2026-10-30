{{-- Vue generique pour les pages editees via Filament, mode BRUT.

     Injecte le HTML brut dans un <main>, sans Blade::render pour eviter
     les recursions de compilation (zend.max_allowed_stack_size reached).

     IMPORTANT : herite de TOUT le harnais tete+pied de la vue d'origine
     (headGlobals, headSchema, styles, scripts, afterFooter). Sans quoi
     le JS Woody plante (window.globals incomplet -> hamburger qui n'ouvre
     plus ses sous-menus en cards, etc.). Les {{ \$title }} et autres
     interpolations connues sont substituees par str_replace. --}}
@extends('layouts.app')

@php
    $__sections = (isset($originalView) && $originalView) ? \App\Models\Page::extractAllSections($originalView) : [];
    $__subst = static function (string $html) use ($title, $description, $activeMenu): string {
        return strtr($html, [
            '{{ $title }}'       => (string) $title,
            '{{ $description }}' => (string) ($description ?? ''),
            '{{ $activeMenu }}'  => (string) ($activeMenu ?? ''),
            '{{$title}}'         => (string) $title,
            '{{$description}}'   => (string) ($description ?? ''),
            '{{$activeMenu}}'    => (string) ($activeMenu ?? ''),
        ]);
    };
@endphp

@section('bodyClass', $__sections['bodyClass'] ?? 'page prod gabarit-basic_page has-custom-content has-raw-content')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('headGlobals')
@if (isset($__sections['headGlobals']) && trim($__sections['headGlobals']) !== '')
{!! $__subst($__sections['headGlobals']) !!}
@else
<script>window.globals={"ancestors":{"chapter1":"","chapter2":null,"chapter3":null},"context":"website","current_lang":"fr","current_locale":"fr","current_locale_region":"fr_FR","env":"prod","languages":["fr","nl"],"page_type":"basic_page","post_id":0,"post_title":"","post_type":"page","site_key":"benin","tags":{"places":[],"seasons":[],"themes":[],"targets":[]},"woody_options_pages":{"search_url":0,"weather_url":0},"woody_timezone":"Europe/Paris","search":{"api":{"uri":"","args":{"size":15,"threshold":0.5}}},"sheet_bordereaux_with_dates":[],"short_date_bordereaux":[],"tmaps":{"url_api":"","url_api_lazy":""},"hawwwai":{"sheet_options":{"nearby_distance_max":5000}}};</script>
@endif
@endsection

@section('headSchema')
@if (isset($__sections['headSchema']) && trim($__sections['headSchema']) !== '')
{!! $__subst($__sections['headSchema']) !!}
@endif
@endsection

@section('headAlternate')
@if (isset($__sections['headAlternate']) && trim($__sections['headAlternate']) !== '')
{!! $__subst($__sections['headAlternate']) !!}
@endif
@endsection

@section('headImageOg')
@if (isset($__sections['headImageOg']) && trim($__sections['headImageOg']) !== '')
{!! $__subst($__sections['headImageOg']) !!}
@endif
@endsection

@section('headImageTwitter')
@if (isset($__sections['headImageTwitter']) && trim($__sections['headImageTwitter']) !== '')
{!! $__subst($__sections['headImageTwitter']) !!}
@endif
@endsection

@section('styles')
@if (isset($__sections['styles']) && trim($__sections['styles']) !== '')
{!! $__subst($__sections['styles']) !!}
@endif
@endsection

@section('scripts')
@if (isset($__sections['scripts']) && trim($__sections['scripts']) !== '')
{!! $__subst($__sections['scripts']) !!}
@endif
@endsection

@section('afterFooter')
@if (isset($__sections['afterFooter']) && trim($__sections['afterFooter']) !== '')
{!! $__subst($__sections['afterFooter']) !!}
@endif
@endsection

@section('aside')
@if (isset($__sections['aside']) && trim($__sections['aside']) !== '')
{!! $__subst($__sections['aside']) !!}
@endif
@endsection

@section('content')
@php($__ba_html = (string) ($customContent ?? ''))
@php($__ba_plain = trim(strip_tags(preg_replace('/<!--.*?-->/s', '', $__ba_html) ?? $__ba_html)))
@php($__ba_hasMedia = (bool) preg_match('/<(img|video|iframe|svg|picture|source|audio)\b/i', $__ba_html))
@if ($__ba_plain !== '' || $__ba_hasMedia)
{!! $__ba_html !!}
@else
<div class="grid-container padd-top-lg padd-bottom-lg">
    <div class="grid-x grid-padding-x align-center">
        <div class="cell large-10 text-center" style="padding:40px 20px;background:#fff8e1;border:1px solid #ffd54f;border-radius:8px;">
            <h2 style="margin:0 0 10px;color:#8a6d3b;">Contenu en cours de redaction</h2>
            <p style="margin:0;color:#6b5c20;">Cette page est actuellement sans contenu. Rendez-vous dans le back-office pour la completer, ou desactivez l'interrupteur "Utiliser le contenu personnalise" pour retrouver la version historique.</p>
        </div>
    </div>
</div>
@endif
@endsection
