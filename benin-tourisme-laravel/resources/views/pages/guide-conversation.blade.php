{{-- Guide de conversation minimal fon / yoruba / francais pour touristes.
     Contenu editorial 100% original, base sur les usages courants au Benin. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-guide-conv-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $categories = [
        [
            'titre' => 'Les indispensables',
            'phrases' => [
                ['fr' => 'Bonjour',            'fon' => 'Ku dabo (le matin) · Kudo (en journée)', 'yoruba' => 'Ekaaro (matin) · Ekaasan (jour)'],
                ['fr' => 'Bonsoir',            'fon' => 'Kudo hwelekwe',                          'yoruba' => 'Ekaale'],
                ['fr' => 'Comment vas-tu ?',   'fon' => 'A do gan-ji a ?',                        'yoruba' => 'Bawo ni ?'],
                ['fr' => 'Ça va bien, merci',  'fon' => 'Un do gan-ji',                           'yoruba' => 'Mo wa daadaa, o se'],
                ['fr' => 'Merci',              'fon' => 'Awanou',                                 'yoruba' => 'O se · A dupe'],
                ['fr' => 'Merci beaucoup',     'fon' => 'Awanou tawun',                           'yoruba' => 'O se pupo'],
                ['fr' => 'S\'il vous plaît',   'fon' => 'Kabla',                                  'yoruba' => 'Jowo'],
                ['fr' => 'Excusez-moi',        'fon' => 'Miwansou',                               'yoruba' => 'Ma binu · Pele'],
                ['fr' => 'Au revoir',          'fon' => 'Edabo · Miloa',                          'yoruba' => 'O dabo'],
            ],
        ],
        [
            'titre' => 'Se présenter, converser',
            'phrases' => [
                ['fr' => 'Je m\'appelle …',    'fon' => 'Nyi ce nyi …',                           'yoruba' => 'Oruko mi ni …'],
                ['fr' => 'Comment vous appelez-vous ?', 'fon' => 'Nyi towe nyi eton ?',           'yoruba' => 'Kini oruko re ?'],
                ['fr' => 'Je viens de France',  'fon' => 'Un gosin France',                        'yoruba' => 'Mo ti wa lati France'],
                ['fr' => 'Je ne comprends pas', 'fon' => 'Un se nu ma',                            'yoruba' => 'N ko gbo'],
                ['fr' => 'Parlez-vous français ?','fon' => 'A do frangbe a ?',                     'yoruba' => 'So Frenshi ?'],
                ['fr' => 'Oui',                 'fon' => 'Eeo',                                    'yoruba' => 'Beeni'],
                ['fr' => 'Non',                 'fon' => 'Eon',                                    'yoruba' => 'Rara'],
            ],
        ],
        [
            'titre' => 'Au marché et au restaurant',
            'phrases' => [
                ['fr' => 'Combien ça coûte ?',  'fon' => 'Enu ka nabi ?',                          'yoruba' => 'Elo ni ?'],
                ['fr' => 'C\'est trop cher',    'fon' => 'E vevie dinding',                        'yoruba' => 'O gbowo ju'],
                ['fr' => 'Baisser le prix ?',   'fon' => 'Kpede na mi',                            'yoruba' => 'Din owo ku'],
                ['fr' => 'De l\'eau, s\'il vous plaît', 'fon' => 'Sin, kabla',                     'yoruba' => 'Omi, jowo'],
                ['fr' => 'C\'est bon (goût)',   'fon' => 'E vivi',                                 'yoruba' => 'O dun'],
                ['fr' => 'Sans piment',         'fon' => 'Ma do takin',                            'yoruba' => 'Laisi ata'],
                ['fr' => 'L\'addition, svp',    'fon' => 'Na wema wan',                            'yoruba' => 'Bill mi, jowo'],
            ],
        ],
        [
            'titre' => 'Se repérer, se déplacer',
            'phrases' => [
                ['fr' => 'Où est … ?',          'fon' => '… tin fite ?',                           'yoruba' => 'Nibo ni … wa ?'],
                ['fr' => 'À gauche',            'fon' => 'Do amyi',                                'yoruba' => 'Osi'],
                ['fr' => 'À droite',            'fon' => 'Do disi',                                'yoruba' => 'Otun'],
                ['fr' => 'Tout droit',          'fon' => 'Do zinzin',                              'yoruba' => 'Taara'],
                ['fr' => 'Arrêtez ici',         'fon' => 'Nomi tofito',                            'yoruba' => 'Duro nibi'],
                ['fr' => 'C\'est loin ?',       'fon' => 'E lin a ?',                              'yoruba' => 'O jina ?'],
                ['fr' => 'Un taxi',             'fon' => 'Zemidjan (moto) · Taxi',                 'yoruba' => 'Taksi'],
            ],
        ],
    ];
@endphp

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Guide de conversation">
        <img src="/assets/img/marche-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Guide de conversation</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Parler local</p>
                <h1 class="ba-page-hero__title">Guide de conversation Fon / Yoruba</h1>
                <p class="ba-page-hero__lead">Le français est la langue officielle du Bénin. Mais glisser un mot en fon ou en yoruba fait toujours sourire et ouvre des portes — voici les phrases qui comptent.</p>
            </div>
        </div>
    </section>

    <section class="ba-page-chapo">
        <p>Le <strong>fon</strong> est parlé au sud (Cotonou, Abomey, Ouidah), le <strong>yoruba</strong> au sud-est (Porto-Novo, Ketou). Le <strong>bariba</strong> et le <strong>dendi</strong> dominent au nord, mais le français reste toujours compris. La prononciation est approchée : demandez à un local de vous corriger, il en sera ravi.</p>
    </section>

    @foreach($categories as $ci => $cat)
        <section class="ba-conv-cat">
            <h2 class="ba-conv-cat__title"><span class="ba-conv-cat__num">{{ str_pad((string)($ci+1), 2, '0', STR_PAD_LEFT) }}</span> {{ $cat['titre'] }}</h2>
            <div class="ba-conv-table-wrap">
                <table class="ba-conv-table">
                    <thead>
                        <tr>
                            <th scope="col">Français</th>
                            <th scope="col">Fon</th>
                            <th scope="col">Yoruba</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cat['phrases'] as $p)
                            <tr>
                                <td class="ba-conv-fr">{{ $p['fr'] }}</td>
                                <td class="ba-conv-lang">{{ $p['fon'] }}</td>
                                <td class="ba-conv-lang">{{ $p['yoruba'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach

    <section class="ba-cta-simple">
        <p>Envie d'entendre ces phrases prononcées correctement ?</p>
        <a class="ba-cta-simple__btn" href="https://wa.me/2290167110220" target="_blank" rel="noopener noreferrer">
            En parler avec un guide Dahomey Discovery <span aria-hidden="true">↗</span>
        </a>
    </section>
</div>
<x-aggregator.ecosysteme-block />
@endsection
