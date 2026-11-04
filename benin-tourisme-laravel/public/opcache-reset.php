<?php

/*
 * Ancien script temporaire de vidage d'OPcache, désactivé le 4 novembre 2026.
 * Aucun code du site ne l'utilise. Ce fichier peut être supprimé
 * (git rm public/opcache-reset.php) ; en attendant, il répond 404 à tout le
 * monde et n'exécute plus rien.
 */

http_response_code(404);
header('Content-Type: text/plain; charset=UTF-8');
echo 'Not Found';
