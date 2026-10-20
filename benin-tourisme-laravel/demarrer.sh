#!/usr/bin/env bash
#
# Démarrage du site Bénin tourisme.
#
# Tout est dans l'archive : les dépendances PHP, les images, les styles. Il n'y a
# ni base de données à installer, ni compte à créer — le contenu vit dans les vues.
# Ce script prépare ce qui manque, puis sert le site sur le port 8010, celui que
# les liens de la carte d'architecture attendent.
#
#     ./demarrer.sh
#
set -euo pipefail
cd "$(dirname "$0")"

PORT=8010
vert=$'\033[32m'; gras=$'\033[1m'; gris=$'\033[90m'; nul=$'\033[0m'

echo
echo "${gras}Bénin tourisme${nul} ${gris}— 89 pages, Laravel${nul}"
echo

# ---------------------------------------------------------------- PHP 8.3+
if ! command -v php >/dev/null 2>&1; then
  echo "PHP est introuvable. Installe PHP 8.3 ou plus récent :"
  echo "  macOS    brew install php"
  echo "  Ubuntu   sudo apt install php8.3-cli php8.3-mbstring php8.3-xml php8.3-sqlite3"
  exit 1
fi
version=$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')
if ! php -r 'exit(PHP_VERSION_ID >= 80300 ? 0 : 1);'; then
  echo "PHP $version est trop ancien : ce projet demande PHP 8.3 ou plus récent."
  exit 1
fi
echo "  PHP $version"

# ----------------------------------------------------- dépendances et clés
if [ ! -d vendor ]; then
  echo "  Installation des dépendances…"
  command -v composer >/dev/null 2>&1 || { echo "  Composer est introuvable : https://getcomposer.org"; exit 1; }
  composer install --no-interaction --quiet
fi

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --quiet
  echo "  Configuration créée (.env)"
fi
grep -q '^APP_KEY=base64:' .env || php artisan key:generate --quiet

mkdir -p storage/framework/{views,cache/data,sessions} storage/logs bootstrap/cache

# Les vues compilées et la configuration en cache portent les chemins de la machine
# qui les a produites : on repart d'une ardoise propre.
php artisan optimize:clear --quiet 2>/dev/null || true

# ------------------------------------------------------------------ départ
carte="file://$(pwd)/docs/atlas.html"
echo
echo "  ${vert}Le site${nul}    http://127.0.0.1:$PORT"
echo "  ${vert}La carte${nul}   $carte"
echo
echo "  ${gris}Ctrl+C pour arrêter.${nul}"
echo

command -v open >/dev/null 2>&1 && (sleep 2 && open "http://127.0.0.1:$PORT") &
php artisan serve --host=127.0.0.1 --port="$PORT"
