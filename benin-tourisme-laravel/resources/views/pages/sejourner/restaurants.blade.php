{{-- Landing Wa Dùnù — page premium editoriale de vente du catalogue restaurants.
     Design inspire des guides gastronomiques (Michelin, Gault&Millau) + landing
     SaaS moderne (aperçu, teaser, pricing, FAQ, garanties). --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page wadunu-landing')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">

    {{-- ============================================================ --}}
    {{-- HERO CINEMATIQUE                                              --}}
    {{-- ============================================================ --}}
    <section class="wd-hero" aria-label="Wa Dùnù — le guide gourmand du Bénin">
        <img class="wd-hero__bg" src="/assets/img/cuisine-1920x1080.webp" alt="" loading="eager" fetchpriority="high">
        <div class="wd-hero__overlay"></div>
        <div class="wd-hero__container">
            <nav class="wd-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">·</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">·</li>
                    <li aria-current="page">Où manger</li>
                </ol>
            </nav>
            <div class="wd-hero__content">
                <p class="wd-hero__eyebrow">
                    <span class="wd-hero__pill">Wa Dùnù · Édition 2026</span>
                    <span class="wd-hero__by">Édité par <strong>Dahomey Discovery</strong></span>
                </p>
                <h1 class="wd-hero__title">
                    Le carnet d'adresses<br>
                    <span class="wd-hero__title-accent">gourmandes du Bénin</span>
                </h1>
                <p class="wd-hero__lead">
                    {{ $totalRestos }} tables sélectionnées à la main, testées, notées.
                    Adresses précises, contacts, horaires, budgets, conseils de dégustation.
                    Mises à jour continues pendant 6 mois.
                </p>
                <div class="wd-hero__ctas">
                    <button type="button" class="wd-btn wd-btn--primary wd-btn--lg" id="ba-open-buy">
                        Débloquer le catalogue
                        <span class="wd-btn__price">15 000 XOF</span>
                    </button>
                    <a href="#preview" class="wd-btn wd-btn--ghost wd-btn--lg">
                        Voir un aperçu <span aria-hidden="true">↓</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CHIFFRES CLES — bande dediee sous le hero                     --}}
    {{-- ============================================================ --}}
    <section class="wd-stats" id="ba-stats" aria-label="Le catalogue en chiffres">
        <div class="wd-stats__inner">
            <p class="wd-stats__kicker">Édition 2026 · Wa Dùnù</p>
            <div class="wd-stats__grid">
                <div class="wd-stats__item">
                    <strong><span class="wd-count" data-count-to="{{ $totalRestos }}">0</span></strong>
                    <span>Adresses sélectionnées</span>
                </div>
                <div class="wd-stats__item">
                    <strong><span class="wd-count" data-count-to="{{ $totalVilles }}">0</span></strong>
                    <span>Villes couvertes</span>
                </div>
                <div class="wd-stats__item">
                    <strong><span class="wd-count" data-count-to="5">0</span></strong>
                    <span>Univers gourmands</span>
                </div>
                <div class="wd-stats__item">
                    <strong><span class="wd-count" data-count-to="6">0</span><em>mois</em></strong>
                    <span>D'accès actualisé</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- BARRE DE TRUST                                                --}}
    {{-- ============================================================ --}}
    <section class="wd-trust" aria-label="Ce que le catalogue vous garantit">
        <div class="wd-trust__inner">
            <div class="wd-trust__item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 3v6c0 5-3.5 9.3-8 11-4.5-1.7-8-6-8-11V5l8-3z"/><path d="M9 12l2 2 4-4"/></svg>
                <div>
                    <strong>Sélection éditoriale</strong>
                    <span>Chaque adresse est testée par l'équipe Dahomey Discovery</span>
                </div>
            </div>
            <div class="wd-trust__item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.2-8.55"/><path d="M21 4v5h-5"/></svg>
                <div>
                    <strong>Mises à jour continues</strong>
                    <span>Nouvelles adresses, changements de cartes, horaires — tout est actualisé</span>
                </div>
            </div>
            <div class="wd-trust__item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M7 15h4"/></svg>
                <div>
                    <strong>Paiement sécurisé KKiaPay</strong>
                    <span>Cartes internationales, MTN Mobile Money, Moov Money</span>
                </div>
            </div>
            <div class="wd-trust__item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 4l8 8 8-8"/></svg>
                <div>
                    <strong>Lien personnel par email</strong>
                    <span>Un URL unique qui vous suit sur tous vos appareils</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- APERÇU DU CATALOGUE (6 coups de coeur)                        --}}
    {{-- ============================================================ --}}
    <section class="wd-preview" id="preview" aria-label="Aperçu du catalogue">
        <div class="wd-section__head">
            <p class="wd-section__eyebrow">Aperçu gratuit</p>
            <h2 class="wd-section__title">Voici 6 de nos coups de cœur</h2>
            <p class="wd-section__lead">Un aperçu de la qualité éditoriale du catalogue. Les {{ $totalRestos - 6 }} autres adresses vous attendent après l'achat.</p>
        </div>

        @php
            $catImages = [
                'gastronomique' => '/assets/img/cuisine-640x480.webp',
                'maquis'        => '/assets/img/maquis-640x480.webp',
                'cafe_bar'      => '/assets/img/restos-cafe-640x480.webp',
                'fast_food'     => '/assets/img/cuisine-640x480.webp',
                'international' => '/assets/img/restos-inter-640x480.webp',
            ];
        @endphp
        <div class="wd-preview__grid">
            @foreach($previewRestos as $r)
                <article class="wd-card" style="--i: {{ $loop->index }}">
                    @if($r->coup_de_coeur)
                        <span class="wd-card__coeur" aria-label="Coup de cœur Dahomey Discovery">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21s-7.5-4.7-9.8-9.4C.5 7.7 3.2 4 6.6 4c1.9 0 3.6 1 4.4 2.5.8-1.5 2.5-2.5 4.4-2.5 3.4 0 6.1 3.7 4.4 7.6C19.5 16.3 12 21 12 21z"/></svg>
                        </span>
                    @endif
                    <div class="wd-card__media">
                        @if(!empty($r->photos) && count($r->photos))
                            <img src="{{ asset('storage/' . $r->photos[0]) }}" alt="" loading="lazy">
                        @else
                            <img src="{{ $catImages[$r->categorie] ?? '/assets/img/cuisine-640x480.webp' }}" alt="" loading="lazy">
                        @endif
                        <span class="wd-card__cat">{{ \App\Models\Restaurant::CATEGORIES[$r->categorie] ?? $r->categorie }}</span>
                    </div>
                    <div class="wd-card__body">
                        <div class="wd-card__meta-top">
                            <span class="wd-card__ville">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s-8-7.5-8-13a8 8 0 1 1 16 0c0 5.5-8 13-8 13z"/><circle cx="12" cy="9" r="3"/></svg>
                                {{ $r->ville }}@if($r->quartier) · {{ $r->quartier }}@endif
                            </span>
                        </div>
                        <h3 class="wd-card__nom">{{ $r->nom }}</h3>
                        @if($r->sous_titre)
                            <p class="wd-card__sub">{{ $r->sous_titre }}</p>
                        @endif
                        @if(!empty($r->pourquoi_y_aller))
                            <ul class="wd-card__why">
                                @foreach(array_slice($r->pourquoi_y_aller, 0, 2) as $point)
                                    <li>{{ is_array($point) ? ($point['point'] ?? '') : $point }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="wd-card__meta-bottom">
                            <span class="wd-card__budget">{{ $r->budget_label }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- ZONE VERROUILLEE + CTA                                        --}}
    {{-- ============================================================ --}}
    @if(count($previewNames) > 0)
        <section class="wd-locked" aria-label="Les autres adresses verrouillées">
            <div class="wd-locked__inner">
                <div class="wd-locked__lock">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                </div>
                <p class="wd-locked__eyebrow">+ {{ count($previewNames) }} adresses supplémentaires</p>
                <h2>Le reste du catalogue est verrouillé</h2>
                <p class="wd-locked__desc">
                    Vous accédez ci-dessous à un aperçu des noms de restaurants, cafés, maquis et bars
                    du catalogue. Débloquez pour découvrir chaque fiche complète : adresse, contact,
                    spécialité, budget, horaires et le conseil personnalisé de nos guides.
                </p>
                <ul class="wd-locked__names" aria-hidden="true">
                    @foreach($previewNames as $name)
                        <li>{{ $name }}</li>
                    @endforeach
                </ul>
                <button type="button" class="wd-btn wd-btn--primary wd-btn--lg" onclick="document.getElementById('ba-open-buy').click()">
                    Débloquer maintenant · 15 000 XOF
                </button>
                <p class="wd-locked__note">Paiement unique · Accès 6 mois · Mises à jour continues</p>
            </div>
        </section>
    @endif

    {{-- ============================================================ --}}
    {{-- COMMENT ÇA MARCHE                                             --}}
    {{-- ============================================================ --}}
    <section class="wd-how" aria-label="Comment obtenir votre accès">
        <div class="wd-section__head">
            <p class="wd-section__eyebrow">Comment ça marche</p>
            <h2 class="wd-section__title">Votre accès en 3 étapes</h2>
            <p class="wd-section__lead">Simple, sécurisé, immédiat.</p>
        </div>
        <div class="wd-how__grid">
            <article class="wd-step">
                <span class="wd-step__num">01</span>
                <div class="wd-step__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18M7 15h4"/></svg>
                </div>
                <h3>Payez en sécurité</h3>
                <p>Renseignez votre email et réglez 15 000 XOF via KKiaPay — carte bancaire, MTN Mobile Money, Moov Money.</p>
            </article>
            <article class="wd-step">
                <span class="wd-step__num">02</span>
                <div class="wd-step__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 4l8 8 8-8"/></svg>
                </div>
                <h3>Recevez votre lien</h3>
                <p>Vous recevez immédiatement un email avec votre <strong>lien personnel</strong>. Aucun mot de passe à retenir : gardez-le en favori.</p>
            </article>
            <article class="wd-step">
                <span class="wd-step__num">03</span>
                <div class="wd-step__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3 7h7l-6 4 2 8-6-4-6 4 2-8-6-4h7z"/></svg>
                </div>
                <h3>Explorez le catalogue</h3>
                <p>Filtrez par ville, cuisine, budget, coup de cœur. Consultez la fiche complète de chaque adresse. Retrouvez-le sur tous vos appareils.</p>
            </article>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CE QUE VOUS TROUVEZ (5 catégories)                            --}}
    {{-- ============================================================ --}}
    <section class="wd-cats" aria-label="Les catégories du catalogue">
        <div class="wd-section__head">
            <p class="wd-section__eyebrow">Ce que vous trouvez</p>
            <h2 class="wd-section__title">Cinq univers gourmands</h2>
        </div>
        <div class="wd-cats__grid">
            <article class="wd-cat">
                <div class="wd-cat__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 3v18M5 3s3 1 3 5-3 5-3 5"/><path d="M19 3v18M17 3v7a2 2 0 002 2h0a2 2 0 002-2V3"/></svg>
                </div>
                <h3>Gastronomique</h3>
                <p>Les tables signature de Cotonou et Porto-Novo — chefs béninois, cuisine du monde, expériences en salle.</p>
                <span class="wd-cat__count">{{ \App\Models\Restaurant::where('categorie', 'gastronomique')->count() }} adresses</span>
            </article>
            <article class="wd-cat">
                <div class="wd-cat__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12c2-1 3-1 5 0s3 1 5 0 3-1 5 0 3 1 3 1"/><path d="M12 3c2 3 2 6 0 9M8 5c1 2 1 4 0 6M16 5c1 2 1 4 0 6"/><path d="M4 18h16"/></svg>
                </div>
                <h3>Maquis & cuisine locale</h3>
                <p>Poisson braisé, akassa, wagassi, ablo, atchèkè : la vraie cuisine béninoise, populaire et savoureuse.</p>
                <span class="wd-cat__count">{{ \App\Models\Restaurant::where('categorie', 'maquis')->count() }} adresses</span>
            </article>
            <article class="wd-cat">
                <div class="wd-cat__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 8h1a4 4 0 010 8h-1"/><path d="M3 8h14v9a4 4 0 01-4 4H7a4 4 0 01-4-4V8z"/><line x1="6" y1="2" x2="6" y2="5"/><line x1="10" y1="2" x2="10" y2="5"/><line x1="14" y1="2" x2="14" y2="5"/></svg>
                </div>
                <h3>Cafés & bars</h3>
                <p>Salons de thé, rooftops, bars à cocktails, glaciers artisanaux — où prendre un verre sans se tromper.</p>
                <span class="wd-cat__count">{{ \App\Models\Restaurant::where('categorie', 'cafe_bar')->count() }} adresses</span>
            </article>
            <article class="wd-cat">
                <div class="wd-cat__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 11h16a1 1 0 011 1v0a5 5 0 01-5 5H8a5 5 0 01-5-5v0a1 1 0 011-1z"/><path d="M6 8c0-1 1-2 2-2s1 1 2 1 2-1 3-1 2 1 3 1 2-1 2-1 2 1 2 2"/><line x1="4" y1="20" x2="20" y2="20"/></svg>
                </div>
                <h3>Fast-food</h3>
                <p>Burgers, kebabs, chawarmas, pizzas — les meilleures adresses pour un repas rapide et savoureux.</p>
                <span class="wd-cat__count">{{ \App\Models\Restaurant::where('categorie', 'fast_food')->count() }} adresses</span>
            </article>
            <article class="wd-cat">
                <div class="wd-cat__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/></svg>
                </div>
                <h3>International</h3>
                <p>Cuisines française, italienne, ivoirienne, indienne, libanaise, japonaise — le monde à Cotonou.</p>
                <span class="wd-cat__count">{{ \App\Models\Restaurant::where('categorie', 'international')->count() }} adresses</span>
            </article>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FORMULE D'ACCES — card 2 colonnes editoriale                  --}}
    {{-- ============================================================ --}}
    <section class="wdp" aria-label="Formule d'accès">
        <div class="wdp__wrap">
            <div class="wdp__head-row">
                <p class="wdp__eyebrow">Formule d'accès</p>
                <h2 class="wdp__title">Un paiement unique.<br>Six mois d'accès complet.</h2>
            </div>

            <article class="wdp__card">
                <header class="wdp__bar">
                    <span class="wdp__ribbon">Édition 2026</span>
                    <span class="wdp__badge">Formule unique</span>
                </header>

                <div class="wdp__body">
                    {{-- Colonne gauche : prix + CTA + trust --}}
                    <div class="wdp__left">
                        <div class="wdp__price">
                            <span class="wdp__price-amount">15 000</span>
                            <span class="wdp__price-cur">XOF</span>
                        </div>
                        <p class="wdp__price-eq">≈ 22,87 € · 24,65 USD</p>

                        <div class="wdp__divider" role="presentation"></div>

                        <ul class="wdp__facts">
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span><strong>Accès valable 6 mois</strong><br>à compter de l'achat</span>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-6.2-8.55"/><path d="M21 4v5h-5"/></svg>
                                <span><strong>Aucune reconduction</strong><br>pas de surprise sur votre carte</span>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M7 15h4"/></svg>
                                <span><strong>Paiement sécurisé</strong><br>cartes, MTN &amp; Moov Money</span>
                            </li>
                        </ul>

                        <button type="button" class="wdp__cta" onclick="document.getElementById('ba-open-buy').click()">
                            Activer mon accès
                            <span class="wdp__cta-arrow" aria-hidden="true">→</span>
                        </button>
                        <p class="wdp__cta-note">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l8 3v6c0 5-3.5 9.3-8 11-4.5-1.7-8-6-8-11V5l8-3z"/></svg>
                            Transaction sécurisée · Lien reçu dans la minute
                        </p>
                    </div>

                    {{-- Colonne droite : ce qui est inclus --}}
                    <div class="wdp__right">
                        <h3 class="wdp__right-title">Ce qui est inclus</h3>
                        <ul class="wdp__features">
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>{{ $totalRestos }} fiches complètes</strong>
                                    <span>sur {{ $totalVilles }} villes — adresse, téléphone, horaires, budget</span>
                                </div>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>Mises à jour continues</strong>
                                    <span>nouvelles ouvertures, cartes revisitées, horaires actualisés</span>
                                </div>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>Lien personnel par email</strong>
                                    <span>sur tous vos appareils, sans mot de passe</span>
                                </div>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>Filtres avancés</strong>
                                    <span>ville, catégorie, budget, coup de cœur</span>
                                </div>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>Conseils signés</strong>
                                    <span>Dahomey Discovery — le petit mot qui change le dîner</span>
                                </div>
                            </li>
                            <li>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                                <div>
                                    <strong>Support WhatsApp</strong>
                                    <span>une question&nbsp;? Réponse dans la journée</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- FAQ                                                            --}}
    {{-- ============================================================ --}}
    <section class="wd-faq" aria-label="Questions fréquentes">
        <div class="wd-section__head">
            <p class="wd-section__eyebrow">Questions fréquentes</p>
            <h2 class="wd-section__title">Les questions que l'on nous pose</h2>
        </div>
        <div class="wd-faq__list">
            <details class="wd-faq__item" open>
                <summary>
                    <span>Comment vais-je recevoir mon catalogue ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Immédiatement après le paiement, vous recevez un email contenant votre lien personnel unique. Cliquez dessus pour ouvrir le catalogue. Il vous suffit de le garder en favori dans votre navigateur — pas d'application, pas de mot de passe.</p>
            </details>
            <details class="wd-faq__item">
                <summary>
                    <span>Le catalogue est-il vraiment mis à jour ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Oui. L'équipe éditoriale Dahomey Discovery met le catalogue à jour toute l'année : nouvelles ouvertures, cartes revisitées, horaires modifiés, fermetures. Quand nous ajoutons ou modifions une adresse, votre catalogue reflète le changement <strong>immédiatement</strong>, sans que vous ayez à faire quoi que ce soit.</p>
            </details>
            <details class="wd-faq__item">
                <summary>
                    <span>Puis-je partager mon lien avec quelqu'un ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Techniquement oui, mais c'est déconseillé : le lien est <strong>personnel</strong>. Vos préférences (filtres, favoris) sont sauvegardées par lien. Si vous souhaitez offrir un accès, nous préparons prochainement des cartes-cadeaux dédiées — dites-le nous sur WhatsApp.</p>
            </details>
            <details class="wd-faq__item">
                <summary>
                    <span>Que se passe-t-il après les 6 mois ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Votre lien devient inactif. Vous recevez un email de rappel une semaine avant l'échéance pour renouveler si vous le souhaitez. Pas de reconduction tacite, pas de mauvaise surprise sur votre carte.</p>
            </details>
            <details class="wd-faq__item">
                <summary>
                    <span>Est-ce que les restaurants me paient pour figurer ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Non. La sélection est <strong>strictement éditoriale</strong>. Nous testons chaque adresse en tant que clients anonymes. Aucun restaurateur ne peut acheter sa présence — c'est le seul moyen de garantir un contenu de qualité.</p>
            </details>
            <details class="wd-faq__item">
                <summary>
                    <span>J'ai un problème, comment vous joindre ?</span>
                    <span class="wd-faq__chevron">+</span>
                </summary>
                <p>Écrivez-nous sur <a href="https://wa.me/2290167110220" target="_blank" rel="noopener noreferrer">WhatsApp</a> — nous répondons dans la journée. Ou par email à reservations@dahomeydiscovery.com.</p>
            </details>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- CTA RESTAURATEURS                                             --}}
    {{-- ============================================================ --}}
    <section class="wd-pro" aria-label="Vous êtes restaurateur ?">
        <div class="wd-pro__inner">
            <div class="wd-pro__text">
                <p class="wd-pro__eyebrow">Vous êtes restaurateur ?</p>
                <h2>Rejoignez le catalogue Wa Dùnù</h2>
                <p>La sélection reste strictement éditoriale — mais nous restons ouverts à découvrir de nouvelles adresses. Écrivez-nous, présentez votre établissement, et nous viendrons y goûter.</p>
            </div>
            <a class="wd-btn wd-btn--secondary wd-btn--lg" href="https://wa.me/2290167110220?text=Bonjour%2C%20je%20suis%20restaurateur%20au%20B%C3%A9nin%20et%20je%20souhaite%20proposer%20mon%20%C3%A9tablissement%20au%20catalogue%20Wa%20D%C3%B9n%C3%B9." target="_blank" rel="noopener noreferrer">
                Proposer mon restaurant <span aria-hidden="true">↗</span>
            </a>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- MODALE D'ACHAT                                                --}}
    {{-- ============================================================ --}}
    <div class="ba-buy-modal" id="ba-buy-modal" role="dialog" aria-modal="true" aria-labelledby="ba-buy-title" hidden>
        <div class="ba-buy-modal__backdrop" data-close></div>
        <div class="ba-buy-modal__panel">
            <button type="button" class="ba-buy-modal__close" data-close aria-label="Fermer">&times;</button>
            <h2 id="ba-buy-title">Recevez votre accès en 2 minutes</h2>
            <p class="ba-buy-modal__lead">Renseignez votre email — vous serez redirigé vers le paiement KKiaPay, puis votre lien personnel vous sera envoyé aussitôt.</p>
            <form id="ba-buy-form" class="ba-buy-form">
                <label>Email
                    <input type="email" name="email" required autocomplete="email" placeholder="vous@exemple.com">
                </label>
                <label>Nom (facultatif)
                    <input type="text" name="nom_client" autocomplete="name" placeholder="Prénom + Nom">
                </label>
                <div class="ba-buy-form__price">
                    <span>Total à payer</span>
                    <strong>15 000 XOF</strong>
                </div>
                <button type="submit" class="ba-buy-form__submit">
                    Payer avec KKiaPay <span aria-hidden="true">→</span>
                </button>
                <p class="ba-buy-form__legal">Paiement sécurisé · Visa, Mastercard, MTN Mobile Money, Moov Money</p>
            </form>
            <div class="ba-buy-status" id="ba-buy-status" hidden></div>
        </div>
    </div>
</div>

<x-aggregator.ecosysteme-block />

{{-- Comptage animé des statistiques du hero --}}
<script>
(function () {
    var els = document.querySelectorAll('.wd-count');
    if (!els.length) return;
    var running = false;
    function animate(el) {
        var target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        var duration = 1400;
        var start = performance.now();
        function tick(now) {
            var t = Math.min(1, (now - start) / duration);
            var eased = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(eased * target).toString();
            if (t < 1) requestAnimationFrame(tick);
            else el.textContent = target.toString();
        }
        requestAnimationFrame(tick);
    }
    function startAll() {
        if (running) return; running = true;
        els.forEach(function (el) { animate(el); });
    }
    var target = document.getElementById('ba-stats') || els[0];
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) { if (e.isIntersecting) { startAll(); io.disconnect(); } });
        }, { threshold: 0.35 });
        io.observe(target);
    } else {
        startAll();
    }
})();
</script>

{{-- Widget KKiaPay + orchestration achat --}}
<script src="https://cdn.kkiapay.me/k.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
(function () {
    const openBtn = document.getElementById('ba-open-buy');
    const modal   = document.getElementById('ba-buy-modal');
    const form    = document.getElementById('ba-buy-form');
    const status  = document.getElementById('ba-buy-status');
    if (!openBtn || !modal || !form) return;
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function open()  { modal.hidden = false; document.body.style.overflow = 'hidden'; }
    function close() { modal.hidden = true;  document.body.style.overflow = ''; }
    function setStatus(msg, type = 'info') {
        status.hidden = false;
        status.className = 'ba-buy-status ba-buy-status--' + type;
        status.textContent = msg;
    }
    openBtn.addEventListener('click', open);
    modal.querySelectorAll('[data-close]').forEach(el => el.addEventListener('click', close));
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) close(); });

    form.addEventListener('submit', async e => {
        e.preventDefault();
        setStatus('Préparation de votre commande…', 'info');
        const fd = new FormData(form);
        try {
            const res = await fetch('/restaurants/order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ email: fd.get('email'), nom_client: fd.get('nom_client') }),
            });
            if (!res.ok) throw new Error('order_failed');
            const data = await res.json();
            if (typeof openKkiapayWidget !== 'function') {
                setStatus('Widget de paiement indisponible. Rechargez la page.', 'error');
                return;
            }
            openKkiapayWidget({
                amount: data.amount, key: data.public_key, sandbox: !!data.sandbox,
                email: data.email, name: data.name || '', data: 'wadunu_order_' + data.order_id,
                position: 'center', callback: data.callback,
                paymentmethod: 'momo,card', theme: '#00844d',
            });
            setStatus('Fenêtre de paiement KKiaPay ouverte…', 'info');
            addKkiapayListener('success', async resp => {
                setStatus('Paiement reçu — validation en cours…', 'info');
                try {
                    const r = await fetch(data.callback + '?transaction_id=' + encodeURIComponent(resp.transactionId), {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    });
                    const j = await r.json();
                    if (r.ok && j.ok) {
                        setStatus('C\'est validé ! Consultez votre boîte email — vous venez d\'y recevoir votre lien personnel Wa Dùnù.', 'success');
                        form.hidden = true;
                    } else {
                        setStatus('Paiement reçu mais validation échouée. Écrivez-nous — nous traiterons manuellement.', 'error');
                    }
                } catch (e) {
                    setStatus('Paiement reçu — nous vous enverrons le lien par email manuellement.', 'error');
                }
            });
            addKkiapayListener('failed', () => setStatus('Paiement échoué ou annulé.', 'error'));
        } catch (err) {
            setStatus('Impossible de préparer la commande. Réessayez ou contactez-nous sur WhatsApp.', 'error');
        }
    });
})();
</script>
@endsection
