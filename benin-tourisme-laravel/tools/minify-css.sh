#!/usr/bin/env bash
# Minifie benin-overrides.css -> benin-overrides.min.css
# A relancer apres chaque edit du CSS.
set -e
cd "$(dirname "$0")/.."
python << 'PY'
import re
with open("public/assets/benin-overrides.css", encoding='utf-8') as f: css=f.read()
css = re.sub(r'/\*.*?\*/', '', css, flags=re.DOTALL)
css = re.sub(r'\s+', ' ', css)
css = re.sub(r'\s*([{}:;,>+~])\s*', r'\1', css)
css = re.sub(r';}', '}', css)
css = re.sub(r'(?<=[\s:(])0(?:px|em|rem|%|vh|vw|svh|svw|pt)(?=[\s,;)])', '0', css)
css = re.sub(r'(?<=[\s:(,])0\.(\d)', r'.\1', css)
with open("public/assets/benin-overrides.min.css", 'w', encoding='utf-8') as f: f.write(css.strip())
PY
echo "benin-overrides.min.css regenere"
