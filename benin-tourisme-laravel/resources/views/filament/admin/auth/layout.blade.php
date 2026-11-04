{{--
    Mise en page des écrans d'authentification du back-office (connexion,
    mot de passe oublié) : photo et marque à gauche, formulaire à droite ;
    sur mobile, bandeau photo au-dessus du formulaire.
    Les styles sont ici, dans la page : le thème compilé de Filament ne
    contient que ses propres classes.
--}}
@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <style>
        .ba-login { min-height: 100vh; display: grid; grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr); background: #fff; }
        .ba-login__visual { position: relative; overflow: hidden; background: #0b3d2c; color: #fff; }
        .ba-login__visual > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .ba-login__visual::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(11, 61, 44, .25) 0%, rgba(11, 61, 44, .85) 100%); }
        .ba-login__brand { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; justify-content: space-between; padding: 40px 48px; }
        .ba-login__brand-logo { display: block; align-self: flex-start; height: 72px; width: auto; max-width: none; filter: brightness(0) invert(1); }
        .ba-login__brand-title { margin: 0 0 12px; font-size: clamp(1.75rem, 2.6vw, 2.5rem); font-weight: 700; line-height: 1.15; letter-spacing: -.01em; }
        .ba-login__brand-text { margin: 0; max-width: 34ch; font-size: 1rem; line-height: 1.6; color: rgba(255, 255, 255, .88); }
        .ba-login__flag { display: flex; gap: 0; width: 80px; height: 4px; margin-bottom: 20px; }
        .ba-login__flag span { flex: 1; }
        .ba-login__main { display: flex; flex-direction: column; justify-content: center; padding: 48px clamp(24px, 6vw, 88px); }
        .ba-login__panel { width: 100%; max-width: 400px; margin: 0 auto; }
        .ba-login__panel .fi-simple-header { align-items: flex-start; text-align: left; }
        .ba-login__panel .fi-simple-header-heading { text-align: left; font-size: 1.75rem; }
        .ba-login__panel .fi-simple-header-subheading { text-align: left; font-size: .95rem; line-height: 1.5; }
        .ba-login__notice { margin: 0; padding: 12px 14px; border-radius: 8px; background: #fff7e0; color: #7a5a00; font-size: .9rem; line-height: 1.45; }
        .ba-login__note { margin: 8px 0 0; font-size: .8rem; line-height: 1.5; color: #6b7280; }
        .ba-login__footer { margin-top: 40px; display: flex; flex-wrap: wrap; gap: 8px 20px; font-size: .85rem; }
        .ba-login__footer a { color: #00a05d; text-decoration: none; font-weight: 500; }
        .ba-login__footer a:hover { text-decoration: underline; }
        @media (max-width: 900px) {
            .ba-login { grid-template-columns: 1fr; }
            .ba-login__visual { min-height: 220px; }
            .ba-login__brand { padding: 24px; justify-content: flex-end; gap: 16px; }
            .ba-login__brand-logo { position: absolute; top: 20px; left: 24px; height: 52px; }
            .ba-login__brand-text { display: none; }
            .ba-login__main { padding: 32px 24px 40px; justify-content: flex-start; }
        }
    </style>

    <div class="ba-login">
        <aside class="ba-login__visual" aria-hidden="true">
            <img src="/assets/img/porte-non-retour-ouidah.jpg" alt="">
            <div class="ba-login__brand">
                <img class="ba-login__brand-logo" src="/assets/site/divers/app/themes/benin/src/img/logo/logo.png" alt="">
                <div>
                    <div class="ba-login__flag"><span style="background:#00a05d"></span><span style="background:#fcd116"></span><span style="background:#e8112d"></span></div>
                    <p class="ba-login__brand-title">Bonne Arrivée</p>
                    <p class="ba-login__brand-text">Le portail du tourisme au Bénin. Mettez à jour les pages, le menu et les adresses : chaque enregistrement est publié aussitôt.</p>
                </div>
            </div>
        </aside>

        <main class="ba-login__main">
            <div class="ba-login__panel">
                {{ $slot }}

                <nav class="ba-login__footer" aria-label="Liens">
                    <a href="/">← Retour au site</a>
                    <a href="/mentions-legales">Mentions légales</a>
                </nav>
            </div>
        </main>
    </div>
</x-filament-panels::layout.base>
