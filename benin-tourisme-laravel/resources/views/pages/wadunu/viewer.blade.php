{{-- Wa Dùnù — viewer du PDF pour un token valide.
     Le PDF est stocke en prive et servi par la route wadunu.catalog.pdf,
     qui verifie a nouveau le token a chaque requete. --}}
@extends('layouts.app')
@section('bodyClass', 'page menus-v2 prod gabarit-basic_page wadunu-viewer')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">

    <section class="wv-hero" aria-label="Votre catalogue Wa Dùnù">
        <div class="wv-hero__inner">
            <p class="wv-hero__eyebrow">
                <span class="wv-hero__pill">Wa Dùnù · Accès abonné</span>
                @if($expiresAt)
                    <span class="wv-hero__validity">
                        Valable jusqu'au <strong>{{ $expiresAt->format('d/m/Y') }}</strong>
                    </span>
                @endif
            </p>
            <h1 class="wv-hero__title">Votre catalogue gourmand du Bénin</h1>
            @if($pdfExists && $pdfUpdatedAt)
                <p class="wv-hero__meta">
                    Édition actualisée le <strong>{{ $pdfUpdatedAt->format('d/m/Y à H\hi') }}</strong>
                    · Poids : <strong>{{ number_format($pdfSizeBytes / 1048576, 1, ',', ' ') }} Mo</strong>
                </p>
            @endif
            <div class="wv-hero__ctas">
                @if($pdfExists)
                    <a class="wv-btn wv-btn--primary" href="{{ route('wadunu.catalog.pdf', ['token' => $token, 'download' => 1]) }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12m0 0l-4-4m4 4l4-4"/><path d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                        Télécharger le PDF
                    </a>
                    <a class="wv-btn wv-btn--ghost" href="{{ route('wadunu.catalog.pdf', $token) }}" target="_blank" rel="noopener">
                        Ouvrir en plein écran <span aria-hidden="true">↗</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    @if($pdfExists)
        <section class="wv-viewer" aria-label="Aperçu du catalogue PDF">
            <div class="wv-viewer__frame">
                <iframe
                    src="{{ route('wadunu.catalog.pdf', $token) }}#toolbar=1&navpanes=0&scrollbar=1&view=FitH"
                    title="Catalogue Wa Dùnù"
                    loading="lazy"
                    allowfullscreen></iframe>
            </div>
            <p class="wv-viewer__fallback">
                Le PDF ne s'affiche pas ?
                <a href="{{ route('wadunu.catalog.pdf', ['token' => $token, 'download' => 1]) }}">Téléchargez-le directement</a>.
            </p>
        </section>
    @else
        <section class="wv-empty" aria-label="Catalogue en cours de préparation">
            <div class="wv-empty__inner">
                <div class="wv-empty__ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M5.6 5.6l2.8 2.8M2 12h4m1.6 6.4l2.8-2.8M12 22v-4m6.4 3.4l-2.8-2.8M22 12h-4m-1.6-6.4l-2.8 2.8"/></svg>
                </div>
                <h2>Catalogue en cours de mise à jour</h2>
                <p>Aucun PDF n'est disponible pour le moment. L'équipe Dahomey Discovery est en train de finaliser une nouvelle édition — revenez sur ce lien dans quelques heures.</p>
                <p class="wv-empty__contact">
                    Un souci ? Écrivez-nous sur
                    <a href="https://wa.me/2290167110220" target="_blank" rel="noopener">WhatsApp</a>.
                </p>
            </div>
        </section>
    @endif

    <section class="wv-info" aria-label="Informations sur votre accès">
        <div class="wv-info__grid">
            <article class="wv-info__card">
                <h3>Mises à jour automatiques</h3>
                <p>Vous consultez toujours la dernière édition en date. Dès que l'équipe met le catalogue à jour, votre lien reflète la nouvelle version — pas besoin de revenir sur le site.</p>
            </article>
            <article class="wv-info__card">
                <h3>Lien personnel</h3>
                <p>Mettez ce lien en favori dans votre navigateur ou renvoyez-le vous par email — c'est <strong>votre</strong> accès. Il fonctionne sur téléphone, tablette et ordinateur.</p>
            </article>
            <article class="wv-info__card">
                <h3>Besoin d'aide ?</h3>
                <p>Une adresse ne répond plus ? Un doute sur un contact ? Écrivez-nous sur <a href="https://wa.me/2290167110220" target="_blank" rel="noopener">WhatsApp</a> — nous répondons dans la journée.</p>
            </article>
        </div>
    </section>
</div>
@endsection
