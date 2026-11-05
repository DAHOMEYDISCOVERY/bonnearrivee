<?php

/*
 * Tâche planifiée pour l'hébergement mutualisé OVH.
 *
 * Les tâches planifiées OVH ne tournent qu'une fois par heure, à une minute
 * qu'on ne choisit pas : le planificateur Laravel (`schedule:run`, prévu pour
 * tourner chaque minute) n'y déclencherait jamais les tâches fixées à 4 h 30,
 * 5 h ou 5 h 30. Ce script les lance lui-même.
 *
 * À déclarer dans l'espace client OVH → Hébergement → Tâches planifiées :
 *   Commande  : bonnearrivee/benin-tourisme-laravel/tools/cron-ovh.php
 *   Langage   : PHP 8.4
 *   Fréquence : toutes les heures, tous les jours
 *
 *   - chaque heure : envoi des e-mails restés en file (liens d'accès Wa Dùnù
 *     dont l'envoi immédiat a échoué) ;
 *   - une fois par nuit, entre 4 h et 5 h (heure du serveur) : blocs
 *     partenaires, index de recherche, contrôle de toutes les pages.
 *
 * Test à la main, en SSH :  php tools/cron-ovh.php --nuit
 */

declare(strict_types=1);

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';
$app = require $root.'/bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$run = static function (string $command, array $arguments = []) use ($kernel): int {
    $start = microtime(true);
    try {
        $status = $kernel->call($command, $arguments);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Tâche planifiée OVH : échec', ['commande' => $command, 'error' => $e->getMessage()]);
        echo "[{$command}] échec : {$e->getMessage()}\n";

        return 1;
    }
    echo '['.$command.'] '.trim($kernel->output()).sprintf(' (%.1f s)', microtime(true) - $start)."\n";

    return $status;
};

$failed = 0;

// Chaque heure : la file des e-mails (s'arrête dès qu'elle est vide).
$failed += $run('queue:work', ['--stop-when-empty' => true, '--max-time' => 300, '--tries' => 3]) === 0 ? 0 : 1;

// Une fois par nuit.
$night = in_array('--nuit', $argv ?? [], true) || (int) date('G') === 4;
if ($night) {
    $failed += $run('aggregator:warm', ['--quiet-summary' => true]) === 0 ? 0 : 1;
    $failed += $run('search:index') === 0 ? 0 : 1;
    $failed += $run('site:verifier') === 0 ? 0 : 1;
}

\Illuminate\Support\Facades\Log::info('Tâche planifiée OVH exécutée', ['nuit' => $night, 'echecs' => $failed]);

// Toujours 0 : OVH désactive une tâche après 10 exécutions en échec. Les
// problèmes sont dans le journal (niveau erreur) et dans le rapport ci-dessus.
exit(0);
