#!/usr/bin/env bash
# A relancer apres chaque edit de .env, routes/web.php, config/*, ou vues Blade
set -e
cd "$(dirname "$0")/.."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo "Laravel caches regeneres"
