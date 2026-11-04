#!/usr/bin/env bash
# Publie benin-overrides.css -> benin-overrides.min.css
#
# On édite UNIQUEMENT benin-overrides.css ; le .min est le fichier servi
# (partials/head/*.blade.php) et ne doit jamais être modifié à la main.
#
# Pourquoi une copie et non une vraie minification : l'ancien script (regex)
# supprimait les espaces autour de « + » et « > ». Il cassait ainsi les
# calc(var(--i, 0) * 90ms + 80ms) du fichier (un calc() exige des espaces
# autour de +/-). Apache compresse déjà le CSS en gzip (.htaccess), le gain
# d'une minification serait marginal.
set -e
cd "$(dirname "$0")/.."
cp public/assets/benin-overrides.css public/assets/benin-overrides.min.css
echo "benin-overrides.min.css mis à jour depuis benin-overrides.css"
