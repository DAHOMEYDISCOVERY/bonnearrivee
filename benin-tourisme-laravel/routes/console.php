<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * `aggregator:warm` reste dispo pour un pré-chauffe manuel post-déploiement,
 * mais n'est plus planifié : le cache utilise désormais un pattern
 * stale-while-revalidate qui rafraîchit tout seul en tâche de fond après
 * chaque réponse HTTP, sans dépendance à un scheduler externe.
 */

/*
 * Index de recherche plein texte (audit BUG-12) : texte réellement affiché de
 * chaque page, modifications « Textes et images » comprises. Lancé au
 * déploiement (tools/deploy.sh) et chaque nuit ; chaque enregistrement au
 * back-office met aussi à jour la page concernée.
 */
Artisan::command('search:index', function () {
    $start = microtime(true);
    foreach (\App\Support\SearchIndex::LOCALES as $loc) {
        // L'index anglais n'est construit que si des traductions existent ;
        // il reprend le texte affiché une fois traduit (TranslationStore).
        if ($loc !== 'fr' && \App\Support\TranslationStore::paths($loc) === []) {
            $this->line("  [{$loc}] aucune traduction : index ignoré.");
            continue;
        }
        $count = \App\Support\SearchIndex::build(null, function (string $path, bool $ok) use ($loc) {
            if (! $ok) {
                $this->warn("  [{$loc}] page illisible : {$path}");
            }
        }, $loc);
        $this->info(sprintf('[%s] %d pages indexées.', $loc, $count));
    }
    $this->info(sprintf('Terminé en %.1f s.', microtime(true) - $start));
})->purpose('Construit l\'index de recherche (fr + langues traduites) à partir du texte affiché');

/*
 * Contrôle de toutes les pages du site, telles qu'un visiteur les voit
 * (modifications du back-office comprises). Lancé au déploiement et chaque
 * nuit ; toute page en défaut est notée dans le journal (niveau erreur).
 */
Artisan::command('site:verifier', function () {
    $pages = \App\Support\SitePages::all();
    $problems = [];
    foreach ($pages as $page) {
        $problem = \App\Support\PublishGuard::problem($page['path']);
        if ($problem !== null) {
            $problems[] = $problem;
            $this->error('  ✗ '.$problem);
        }
    }
    if ($problems === []) {
        $this->info(count($pages).' pages vérifiées : toutes s\'affichent correctement.');

        return 0;
    }
    $this->warn(count($problems).' page(s) en défaut sur '.count($pages).'.');

    return 1;
})->purpose('Vérifie que chaque page du site s\'affiche, modifications du back-office comprises');

/*
 * Tâches planifiées. Nécessite, sur le serveur, la tâche cron Laravel :
 *   * * * * * cd /chemin/du/site && php artisan schedule:run >> /dev/null 2>&1
 * Le rafraîchissement en tâche de fond des blocs partenaires dépend de
 * fastcgi_finish_request, absent sous certains Apache (audit BUG-10) :
 * le pré-chargement de nuit garantit des blocs à jour dans tous les cas.
 */
\Illuminate\Support\Facades\Schedule::command('aggregator:warm --quiet-summary')->dailyAt('04:30')->withoutOverlapping();
\Illuminate\Support\Facades\Schedule::command('site:verifier')->dailyAt('05:30')->withoutOverlapping();
// L'index de recherche passe APRÈS la traduction (trad:sync 05:45) pour que
// l'index anglais reflète les traductions du jour même, pas de la veille.
\Illuminate\Support\Facades\Schedule::command('search:index')->dailyAt('06:15')->withoutOverlapping();

/*
 * Traductions : chaque nuit, les nouveaux contenus sont traduits (DeepL) et
 * publiés dans les langues cibles. Incrémental ; ne fait rien si DEEPL_API_KEY
 * est absente (le contenu non traduit retombe simplement sur le français).
 */
\Illuminate\Support\Facades\Schedule::command('trad:sync')->dailyAt('05:45')->withoutOverlapping();
