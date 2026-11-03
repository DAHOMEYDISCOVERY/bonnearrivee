<?php

/**
 * Reset d'OPcache ponctuel pour debloquer le cache compile PHP sur OVH.
 * A SUPPRIMER APRES USAGE.
 *
 * Usage : https://bonnearrivee.bj/opcache-reset.php?t=ba-reset-2026-11-03-k7m9q4
 */

declare(strict_types=1);

$token = $_GET['t'] ?? '';
$expected = 'ba-reset-2026-11-03-k7m9q4';

if (!hash_equals($expected, $token)) {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit('Forbidden.');
}

header('Content-Type: text/plain; charset=UTF-8');

if (function_exists('opcache_reset')) {
    $ok = opcache_reset();
    echo "opcache_reset() returned: " . ($ok ? 'true (OK, cache vide)' : 'false (echec)') . "\n";
} else {
    echo "opcache_reset() n'est pas disponible sur ce serveur.\n";
    exit;
}

// Vider aussi le cache des statistiques internes
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    if ($status && isset($status['opcache_enabled']) && $status['opcache_enabled']) {
        echo "OPcache est actif.\n";
        if (isset($status['memory_usage']['used_memory'])) {
            echo "Memoire utilisee : " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB\n";
        }
        if (isset($status['opcache_statistics']['num_cached_scripts'])) {
            echo "Scripts caches : " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
        }
    }
}

echo "\nMAINTENANT, SUPPRIMEZ CE FICHIER (ou push le commit de suppression).\n";
