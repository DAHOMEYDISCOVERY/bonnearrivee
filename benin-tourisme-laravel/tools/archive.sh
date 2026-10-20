#!/usr/bin/env bash
#
# Fabrique l'archive à partager : benin-tourisme.zip, sur le Bureau.
#
# Les dépendances PHP (`vendor`) partent avec, pour que celui qui reçoit l'archive
# n'ait besoin ni de Composer ni du réseau. Ce qui est écarté est ce qui se
# reconstruit tout seul ou qui n'appartient qu'à cette machine : les caches, les
# journaux, le fichier .env et sa clé, les relevés de tests, les copies d'écran de
# comparaison et les photographies d'origine dont seules les découpes sont servies.
#
#     ./tools/archive.sh
#
set -euo pipefail
cd "$(dirname "$0")/.."

CIBLE="$HOME/Desktop/benin-tourisme.zip"

echo "Nettoyage des caches…"
php artisan optimize:clear --quiet 2>/dev/null || true
rm -f storage/logs/*.log storage/roundtrip-*.json 2>/dev/null || true

echo "Construction de l'archive…"
rm -f "$CIBLE"
racine=$(pwd)
cd ..
zip -r -q "$CIBLE" "$(basename "$racine")" \
  -x "*/.env" \
  -x "*/.git/*" \
  -x "*/node_modules/*" \
  -x "*/storage/framework/views/*" \
  -x "*/storage/framework/cache/*" \
  -x "*/storage/framework/sessions/*" \
  -x "*/storage/logs/*" \
  -x "*/bootstrap/cache/*.php" \
  -x "*/public/assets/img/_src/*" \
  -x "*/tools/captures/*" \
  -x "*/.DS_Store" -x "*/__pycache__/*"

echo
echo "  $CIBLE  ($(du -h "$CIBLE" | cut -f1))"
echo
echo "  Celui qui la reçoit : décompresse, puis  cd $(basename "$racine") && ./demarrer.sh"
