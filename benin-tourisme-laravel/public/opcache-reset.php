<?php
/**
 * Script a appeler via browser pour purger OPcache sur OVH.
 * URL : https://bonnearrivee.bj/opcache-reset.php?token=TOKEN_SECRET
 *
 * OVH active OPcache en production et ne le purge PAS automatiquement
 * apres un git pull. Resultat : les modifs PHP (Resources Filament,
 * Models, Controllers) ne sont pas prises en compte avant un reset manuel.
 */

$expected = 'ba-reset-2026';
if (($_GET['token'] ?? '') !== $expected) {
    http_response_code(403);
    echo "Interdit.";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

if (function_exists('opcache_reset')) {
    $r = opcache_reset();
    echo "opcache_reset() -> " . ($r ? "OK" : "echec") . "\n";
} else {
    echo "opcache_reset() non disponible (OPcache absent).\n";
}

if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    if ($status) {
        echo "\nStatut OPcache :\n";
        echo "  activé : " . ($status['opcache_enabled'] ? "oui" : "non") . "\n";
        echo "  memoire : " . round(($status['memory_usage']['used_memory'] ?? 0) / 1024 / 1024, 2) . " MB\n";
        echo "  scripts en cache : " . ($status['opcache_statistics']['num_cached_scripts'] ?? 0) . "\n";
    }
}

echo "\nPurges Laravel :\n";
chdir(dirname(__DIR__));
foreach (['view:clear', 'cache:clear', 'config:clear', 'route:clear'] as $cmd) {
    $out = [];
    @exec("php artisan {$cmd} 2>&1", $out, $code);
    echo "  artisan {$cmd} -> " . (count($out) ? trim(implode(' | ', $out)) : "exit {$code}") . "\n";
}
