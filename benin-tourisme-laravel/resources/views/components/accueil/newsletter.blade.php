{{-- Section newsletter — layout éditorial 2 colonnes : à gauche un titre
     serif + une accroche, à droite un formulaire d'inscription sobre.
     Conserve le fond bg-primary (vert Dahomey Discovery) du thème Woody. --}}
<div class="page-section page-section-4" id="pageSection-4">
    <section class="section bg-primary benin-newsletter padd-top-md padd-bottom-md marg-top-none marg-bottom-none">
        <div class="grid-container">
            <div class="benin-newsletter__inner">
                <div class="benin-newsletter__lead">
                    <span class="benin-newsletter__eyebrow">L'infolettre</span>
                    <h2 class="benin-newsletter__title">Le Bénin, chaque mois dans votre boîte.</h2>
                    <p class="benin-newsletter__intro">Récits de voyage, agenda culturel et coups de cœur de nos guides. Une seule édition par mois, jamais de spam.</p>
                </div>
                <form action="/sejourner/toutes-les-infos-pratiques/nous-contacter" method="get" class="benin-newsletter__form" aria-label="Formulaire d'inscription à l'infolettre">
                    <label for="benin-nl-email" class="benin-newsletter__label">Votre adresse e-mail</label>
                    <div class="benin-newsletter__row">
                        <input type="email"
                               id="benin-nl-email"
                               name="email"
                               class="benin-newsletter__input"
                               placeholder="prenom.nom@exemple.com"
                               autocomplete="email"
                               required>
                        <button type="submit" class="benin-newsletter__button">
                            <span>S'inscrire</span>
                            <span aria-hidden="true" class="benin-newsletter__arrow">→</span>
                        </button>
                    </div>
                    <p class="benin-newsletter__legal">
                        En vous inscrivant, vous acceptez notre <a href="/mentions-legales">politique de confidentialité</a>. Désabonnement en un clic.
                    </p>
                </form>
            </div>
        </div>
    </section>
</div>
