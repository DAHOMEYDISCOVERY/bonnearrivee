{{-- Checklist voyage : 5 categories, chaque case cochee est stockee
     dans localStorage. Barre de progression + bouton reset. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-checklist-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $groupes = [
        [
            'titre' => 'Documents',
            'icone' => 'doc',
            'items' => [
                'Passeport valide (min. 6 mois après retour)',
                'e-Visa Bénin imprimé (evisa.gouv.bj)',
                'Certificat de vaccination fièvre jaune',
                'Billet d\'avion + billet de retour',
                'Attestation d\'hébergement (hôtel ou invitation)',
                'Permis de conduire international (si location)',
                'Attestation assurance voyage + assistance rapatriement',
            ],
        ],
        [
            'titre' => 'Santé & pharmacie',
            'icone' => 'health',
            'items' => [
                'Traitement antipaludique (Malarone / Doxycycline — prescription)',
                'Répulsif anti-moustiques 30 %+ DEET',
                'Trousse premiers secours (pansements, désinfectant, thermomètre)',
                'Anti-diarrhéiques + réhydratation (sels type SRO)',
                'Crème solaire indice 50+',
                'Traitements habituels + ordonnance',
                'Petit stock de masques (pollution urbaine à Cotonou)',
            ],
        ],
        [
            'titre' => 'Argent & paiements',
            'icone' => 'money',
            'items' => [
                'Carte bancaire Visa/Mastercard (Amex peu accepté)',
                'Prévenir sa banque du voyage au Bénin',
                'Espèces en euros à changer sur place (bureaux à Cotonou)',
                'Petites coupures XOF pour taxis, moto-taxis, marchés',
                'Application mobile money (MTN Mobile Money, Moov)',
            ],
        ],
        [
            'titre' => 'Bagages & équipement',
            'icone' => 'bag',
            'items' => [
                'Vêtements légers en coton (respirants, séchage rapide)',
                'Un pull léger (nuits en Atacora, clim des hôtels)',
                'Chaussures fermées + sandales ou tongs',
                'Chapeau, lunettes de soleil',
                'Vêtement de pluie léger (si mai–juillet)',
                'Adaptateur prise C/E (idem France)',
                'Batterie externe + câbles',
                'Petit sac à dos pour excursions',
                'Gourde ou bouteille filtrante',
            ],
        ],
        [
            'titre' => 'À prévoir au départ',
            'icone' => 'clock',
            'items' => [
                'Copie numérique de tous les documents (mail à soi-même)',
                'Contact ambassade de France à Cotonou noté',
                'Numéro assistance carte bancaire noté',
                'Contact urgence Dahomey Discovery : +229 01 67 11 02 20',
                'Prévenir un proche de ses dates et itinéraire',
                'Vérifier retour à J-1 sur l\'appli de la compagnie aérienne',
            ],
        ],
    ];
    $totalItems = 0;
    foreach ($groupes as $g) $totalItems += count($g['items']);
@endphp

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Checklist voyage">
        <img src="/assets/img/aeroport-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Checklist voyage</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">À ne rien oublier</p>
                <h1 class="ba-page-hero__title">Checklist voyage au Bénin</h1>
                <p class="ba-page-hero__lead">Trente-cinq points cochables pour préparer votre séjour sereinement. Votre progression est sauvegardée automatiquement sur votre appareil.</p>
            </div>
        </div>
    </section>

    <section class="ba-checklist">
        <div class="ba-checklist__bar" role="status" aria-live="polite">
            <div class="ba-checklist__bar-track">
                <div class="ba-checklist__bar-fill" style="width: 0%"></div>
            </div>
            <div class="ba-checklist__bar-info">
                <span class="ba-checklist__bar-count"><span data-checked="0">0</span> / {{ $totalItems }}</span>
                <button type="button" class="ba-checklist__reset" data-total="{{ $totalItems }}">Tout décocher</button>
            </div>
        </div>

        @foreach($groupes as $gi => $g)
            <div class="ba-checklist__group">
                <h2 class="ba-checklist__group-titre">
                    <span class="ba-checklist__group-num">{{ str_pad((string)($gi+1), 2, '0', STR_PAD_LEFT) }}</span>
                    {{ $g['titre'] }}
                </h2>
                <ul class="ba-checklist__list">
                    @foreach($g['items'] as $ii => $item)
                        @php $id = 'ck-' . $gi . '-' . $ii; @endphp
                        <li class="ba-checklist__item">
                            <input type="checkbox" id="{{ $id }}" class="ba-checklist__cb" data-key="{{ $id }}">
                            <label for="{{ $id }}" class="ba-checklist__lbl">
                                <span class="ba-checklist__check" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5L20 7"/></svg>
                                </span>
                                <span class="ba-checklist__text">{{ $item }}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </section>
</div>
<x-aggregator.ecosysteme-block />

<script>
(function () {
    var STORAGE_KEY = 'ba-checklist-v1';
    var cbs = document.querySelectorAll('.ba-checklist__cb');
    var fill = document.querySelector('.ba-checklist__bar-fill');
    var countEl = document.querySelector('.ba-checklist__bar-count [data-checked]');
    var resetBtn = document.querySelector('.ba-checklist__reset');
    var total = cbs.length;

    function loadState() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : {};
        } catch (e) { return {}; }
    }
    function saveState(state) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch (e) {}
    }
    function refresh() {
        var checked = 0;
        cbs.forEach(function (cb) { if (cb.checked) checked++; });
        if (countEl) countEl.textContent = String(checked);
        if (fill) fill.style.width = total ? (checked / total * 100) + '%' : '0%';
    }

    var state = loadState();
    cbs.forEach(function (cb) {
        if (state[cb.dataset.key]) cb.checked = true;
        cb.addEventListener('change', function () {
            state[cb.dataset.key] = cb.checked ? 1 : 0;
            if (!cb.checked) delete state[cb.dataset.key];
            saveState(state);
            refresh();
        });
    });
    if (resetBtn) resetBtn.addEventListener('click', function () {
        if (!confirm('Tout décocher et vider la sauvegarde ?')) return;
        cbs.forEach(function (cb) { cb.checked = false; });
        try { localStorage.removeItem(STORAGE_KEY); } catch (e) {}
        refresh();
    });
    refresh();
})();
</script>
@endsection
