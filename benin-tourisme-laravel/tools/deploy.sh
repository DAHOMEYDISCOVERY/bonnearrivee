#!/usr/bin/env bash
# Script de deploiement bonnearrivee.bj — a executer sur le serveur OVH apres pull
# Usage : bash tools/deploy.sh
set -e
cd "$(dirname "$0")/.."

echo "===> 1. Dependances Composer (prod, optimisees)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "===> 2. Permissions storage/ et bootstrap/cache"
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

echo "===> 3. Migrations BDD"
php artisan migrate --force --no-interaction

echo "===> 3.1. Seeding user admin"
php artisan db:seed --class=AdminUserSeeder --force --no-interaction

echo "===> 4. Lien symbolique /public/storage -> /storage/app/public"
php artisan storage:link 2>/dev/null || true

echo "===> 5. Caches Laravel (config, routes, vues, events)"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "===> 6. Minification CSS (si modification)"
if [ -f tools/minify-css.sh ]; then
    bash tools/minify-css.sh
fi

echo "===> 7. Queue restart (pour la file de mails magic link)"
php artisan queue:restart 2>/dev/null || true

echo ""
echo "Deploiement termine. N'oublie pas :"
echo "  - Verifier .env (APP_ENV=production, APP_DEBUG=false)"
echo "  - Cron Laravel actif : * * * * * php /path/artisan schedule:run"
echo "  - Queue worker actif pour les mails : queue:work database"
