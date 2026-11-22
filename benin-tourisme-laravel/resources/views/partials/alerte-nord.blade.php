{{--
    Encart « Avant de partir dans le Nord ».

    Affiché sur les pages qui présentent les parcs de la Pendjari et du W comme
    une destination, pour que le visiteur connaisse les conseils officiels avant
    de préparer un séjour. Les pages Grand Nord, parc W, Pendjari et Atacora ont
    déjà leur propre avertissement : elles ne sont pas dans la liste.

    Le bloc est rendu en fin de <main> (il reste lisible sans JavaScript), puis
    remonté juste après l'en-tête de la page.
--}}
@php
    $baAlerteNordPages = [
        '/',
        'activites',
        'activites/les-operateurs-de-safari',
        'activites/safari-et-randonnee',
        'explorez/les-cinq-benin',
        'explorez/naturellement-sauvage',
        'explorez/naturellement-sauvage/la-faune-emblematique',
        'explorez/naturellement-sauvage/la-faune-emblematique/lhippopotame',
        'explorez/naturellement-sauvage/la-faune-emblematique/observer-les-elephants',
        'explorez/naturellement-sauvage/la-faune-emblematique/observer-les-oiseaux',
        'explorez/naturellement-sauvage/les-incontournables',
        'offres/pendjari-safari-tours-natitingou-bj-3313618',
    ];
    $baAlerteNordChemin = trim(request()->path(), '/');
    $baAlerteNordChemin = $baAlerteNordChemin === '' ? '/' : $baAlerteNordChemin;
@endphp
@if (in_array($baAlerteNordChemin, $baAlerteNordPages, true))
<aside class="ba-alerte-nord" id="ba-alerte-nord" role="note" aria-labelledby="ba-alerte-nord-t">
    <div class="ba-alerte-nord__in">
        <b class="ba-alerte-nord__ic" aria-hidden="true">!</b>
        <div>
            <p class="ba-alerte-nord__t" id="ba-alerte-nord-t">Avant de partir dans le Nord</p>
            <p>Les parcs de la Pendjari et du W, et leurs abords, sont aujourd’hui formellement déconseillés par plusieurs pays, dont la France, le Royaume-Uni et le Canada. Vérifiez les conseils aux voyageurs avant tout projet dans le nord du pays.</p>
            <p class="ba-alerte-nord__l"><a href="/sejourner/toutes-les-infos-pratiques/securite">Notre page sécurité</a><a href="https://www.diplomatie.gouv.fr/fr/conseils-aux-voyageurs/conseils-par-pays-destination/benin/" target="_blank" rel="noopener">France Diplomatie</a></p>
        </div>
    </div>
</aside>
<style>
.ba-alerte-nord{padding:1.5rem 1rem;background:transparent}
.ba-alerte-nord__in{display:grid;grid-template-columns:auto 1fr;gap:1.1rem;align-items:start;max-width:1100px;margin:0 auto;padding:1.25rem 1.5rem;border-radius:18px;background:#8f2318;color:#fff;box-shadow:0 18px 40px -28px rgba(60,10,5,.7)}
.ba-alerte-nord__ic{display:grid;place-items:center;width:2.6rem;height:2.6rem;border-radius:50%;background:#fff;color:#8f2318;font:800 1.5rem/1 "Montserrat",Arial,sans-serif}
.ba-alerte-nord p{margin:0;color:#fff;font-size:.98rem;line-height:1.6}
.ba-alerte-nord p+p{margin-top:.45rem}
.ba-alerte-nord__t{font-weight:800;font-size:1.08rem!important;letter-spacing:.01em}
.ba-alerte-nord__l{display:flex;flex-wrap:wrap;gap:.4rem 1.4rem}
.ba-alerte-nord__l a{color:#fff!important;font-weight:700;text-decoration:underline!important;text-underline-offset:3px}
@media (max-width:640px){.ba-alerte-nord__in{grid-template-columns:1fr;padding:1.1rem 1.2rem}}
</style>
<script>
(function () {
    var box = document.getElementById('ba-alerte-nord');
    var main = box && box.closest('main');
    if (!main) return;
    var cible = main.querySelector('.page-section');
    if (cible && cible.parentNode) cible.parentNode.insertBefore(box, cible);
})();
</script>
@endif
