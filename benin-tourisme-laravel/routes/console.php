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
