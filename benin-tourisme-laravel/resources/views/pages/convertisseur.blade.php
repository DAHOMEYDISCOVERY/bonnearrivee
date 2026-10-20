{{-- Convertisseur XOF (franc CFA) ↔ EUR / USD / GBP.
     Taux fixe (XOF/EUR = 655,957 — parité Banque de France).
     Les taux USD et GBP sont indicatifs, mis a jour manuellement. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page benin-conv-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@section('content')
<div class="content-wrapper no-terms">
    <section class="ba-page-hero" aria-label="Convertisseur de devises">
        <img src="/assets/img/marche-1920x960.webp" alt="" loading="eager" fetchpriority="high">
        <div class="ba-page-hero__overlay">
            <nav class="ba-page-hero__breadcrumb" aria-label="Fil d'ariane">
                <ol>
                    <li><a href="/">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="/sejourner">Organisez</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page">Convertisseur</li>
                </ol>
            </nav>
            <div class="ba-page-hero__center">
                <p class="ba-page-hero__eyebrow">Argent & devises</p>
                <h1 class="ba-page-hero__title">Convertisseur franc CFA</h1>
                <p class="ba-page-hero__lead">Le Bénin utilise le franc CFA (XOF), commun aux 8 pays de l'UEMOA. Sa parité avec l'euro est fixe. Convertissez en un clic vos euros, dollars ou livres.</p>
            </div>
        </div>
    </section>

    <section class="ba-conv-tool" aria-label="Convertisseur de devises">
        <div class="ba-conv-tool__inner">
            <div class="ba-conv-row">
                <div class="ba-conv-field">
                    <label for="conv-from-amount" class="ba-conv-label">Montant</label>
                    <div class="ba-conv-field__wrap">
                        <input type="number"
                               id="conv-from-amount"
                               class="ba-conv-input"
                               value="100"
                               min="0"
                               step="1"
                               autocomplete="off"
                               inputmode="decimal">
                        <select id="conv-from-currency" class="ba-conv-select" aria-label="Devise source">
                            <option value="EUR" selected>EUR — Euro</option>
                            <option value="XOF">XOF — Franc CFA</option>
                            <option value="USD">USD — Dollar US</option>
                            <option value="GBP">GBP — Livre sterling</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="ba-conv-swap" aria-label="Inverser les devises">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 4l4 4-4 4M17 20l-4-4 4-4M11 8H3M13 16h8"/>
                    </svg>
                </button>
                <div class="ba-conv-field">
                    <label for="conv-to-amount" class="ba-conv-label">Résultat</label>
                    <div class="ba-conv-field__wrap">
                        <input type="text"
                               id="conv-to-amount"
                               class="ba-conv-input ba-conv-input--result"
                               value="65 595,7"
                               readonly>
                        <select id="conv-to-currency" class="ba-conv-select" aria-label="Devise cible">
                            <option value="EUR">EUR — Euro</option>
                            <option value="XOF" selected>XOF — Franc CFA</option>
                            <option value="USD">USD — Dollar US</option>
                            <option value="GBP">GBP — Livre sterling</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="ba-conv-quick" aria-label="Conversions rapides">
                <p class="ba-conv-quick__title">Repères utiles</p>
                <ul class="ba-conv-quick__list">
                    <li><strong>1 000 XOF</strong> ≈ 1,52 € (café ou brochette)</li>
                    <li><strong>5 000 XOF</strong> ≈ 7,62 € (repas dans un maquis)</li>
                    <li><strong>25 000 XOF</strong> ≈ 38 € (nuit dans une auberge)</li>
                    <li><strong>100 000 XOF</strong> ≈ 152 € (nuit hôtel 4★ à Cotonou)</li>
                </ul>
            </div>

            <p class="ba-conv-note">
                <strong>Parité XOF ↔ EUR :</strong> 1 € = 655,957 XOF (fixe, garantie par le Trésor français depuis 1999).
                <br><strong>USD et GBP :</strong> taux indicatifs mis à jour manuellement — vérifiez sur <a href="https://www.xe.com" target="_blank" rel="noopener noreferrer">xe.com</a> avant tout retrait.
            </p>
        </div>
    </section>

    <section class="ba-cta-simple">
        <p>Où retirer du liquide à Cotonou ?</p>
        <a class="ba-cta-simple__btn" href="/sejourner/toutes-les-infos-pratiques/argent-et-paiements">
            Argent & paiements au Bénin <span aria-hidden="true">→</span>
        </a>
    </section>
</div>
<x-aggregator.ecosysteme-block />

<script>
(function () {
    var rates = { EUR: 1, XOF: 655.957, USD: 1.08, GBP: 0.85 };
    var fromA = document.getElementById('conv-from-amount');
    var fromC = document.getElementById('conv-from-currency');
    var toA   = document.getElementById('conv-to-amount');
    var toC   = document.getElementById('conv-to-currency');
    var swap  = document.querySelector('.ba-conv-swap');
    if (!fromA || !fromC || !toA || !toC) return;

    function fmt(n, cur) {
        if (!isFinite(n)) return '0';
        var decimals = (cur === 'XOF') ? 0 : 2;
        return n.toLocaleString('fr-FR', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }
    function convert() {
        var v = parseFloat((fromA.value || '0').replace(',', '.')) || 0;
        var inEur = v / rates[fromC.value];
        var out = inEur * rates[toC.value];
        toA.value = fmt(out, toC.value);
    }
    fromA.addEventListener('input', convert);
    fromC.addEventListener('change', convert);
    toC.addEventListener('change', convert);
    swap.addEventListener('click', function () {
        var tmp = fromC.value;
        fromC.value = toC.value;
        toC.value = tmp;
        convert();
    });
    convert();
})();
</script>
@endsection
