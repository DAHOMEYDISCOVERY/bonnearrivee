<?php

declare(strict_types=1);

/*
 * Banc d'essai des composants Blade.
 *
 * Componentiser du HTML généré n'a de sens que si le composant le rend à
 * l'identique. Ce script lit un fichier JSON de cas — composant, propriétés,
 * fragment attendu — le rend, et compare octet à octet.
 *
 *     php tools/roundtrip.php cas.json
 *
 * Il écrit à côté un fichier « .verdict.json » qui dit, pour chaque cas, s'il
 * passe. `tools/convert.py` s'en sert pour ne remplacer que les fragments dont
 * le composant rend exactement l'original, et laisser les autres tels quels.
 *
 * La sortie résume la couverture et montre le premier échec de chaque famille :
 * c'est ce qui permet de corriger le composant plutôt que de deviner.
 */

require __DIR__.'/../vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Blade;

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$source = $argv[1] ?? null;
if ($source === null || ! is_file($source)) {
    fwrite(STDERR, "Usage : php tools/roundtrip.php <cas.json>\n");
    exit(1);
}

/** @var array<int, array{component: string, props: array<string, mixed>, expected: string}> $cas */
$cas = json_decode(file_get_contents($source), true, flags: JSON_THROW_ON_ERROR);

/**
 * Rend l'appel de composant tel qu'il sera écrit dans la vue.
 *
 * On rend le fragment Blade littéral, et non les propriétés liées à des variables :
 * c'est le texte qui finira dans le fichier qu'il faut éprouver, sérialisation des
 * attributs comprise.
 */
function rendre(string $template): string
{
    return Blade::render($template);
}

$verdicts = [];
$parFamille = [];
$premierÉchec = [];

foreach ($cas as $index => $cas_) {
    try {
        $obtenu = rendre($cas_['template']);
    } catch (Throwable $erreur) {
        $obtenu = '### ERREUR : '.$erreur->getMessage();
    }

    // Une vue Blade se termine par le saut de ligne final de son fichier. Il tombe
    // entre deux balises, là où le blanc n'a aucun effet sur le rendu.
    $obtenu = rtrim($obtenu, "\n");

    $conforme = normalise($obtenu) === normalise($cas_['expected']);
    $verdicts[$index] = $conforme;

    $famille = $cas_['family'] ?? $cas_['component'];
    $parFamille[$famille] ??= ['total' => 0, 'ok' => 0];
    $parFamille[$famille]['total']++;
    $parFamille[$famille]['ok'] += $conforme ? 1 : 0;

    if (! $conforme && ! isset($premierÉchec[$famille])) {
        $premierÉchec[$famille] = ['attendu' => normalise($cas_['expected']), 'obtenu' => normalise($obtenu)];
    }
}

file_put_contents($source.'.verdict.json', json_encode($verdicts));

$total = count($cas);
$ok = count(array_filter($verdicts));
printf("%d cas, %d conformes (%.1f %%)\n\n", $total, $ok, $total > 0 ? 100 * $ok / $total : 0);

foreach ($parFamille as $famille => $compte) {
    printf("  %-28s %4d / %-4d  %5.1f %%\n",
        $famille, $compte['ok'], $compte['total'], 100 * $compte['ok'] / $compte['total']);
}

foreach ($premierÉchec as $famille => $écart) {
    printf("\n--- premier échec : %s\n", $famille);
    printf("    attendu : %s\n", var_export(premierÉcart($écart['attendu'], $écart['obtenu']), true));
}

/**
 * Ramène un document à sa forme comparable.
 *
 * Même contrat que `tools/equivalence.py`, qui le définit et le teste : le blanc
 * entre attributs, le blanc entre balises et l'encodage des entités sont sans
 * effet sur le rendu ; tout le reste compte.
 */
function normaliseBalise(array $trouvé): string
{
    $balise = substr(preg_replace('/\s*\/?>$/', '', $trouvé[0]), 1);
    [$nom, $reste] = array_pad(explode(' ', $balise, 2), 2, '');
    if (trim($reste) === '') {
        return "<{$nom}>";
    }

    preg_match_all('/([a-zA-Z_:][-\w:.]*)(?:="([^"]*)")?/', $reste, $paires, PREG_SET_ORDER);
    $attributs = array_map(
        fn (array $p) => isset($p[2]) ? sprintf('%s="%s"', $p[1], $p[2]) : $p[1],
        array_filter($paires, fn (array $p) => $p[1] !== ''),
    );
    sort($attributs);

    return sprintf('<%s %s>', $nom, implode(' ', $attributs));
}

function normalise(string $html): string
{
    $html = preg_replace_callback(
        '/<[a-zA-Z\/!][^<>]*>/',
        normaliseBalise(...),
        $html,
    );
    $html = trim(preg_replace('/>\s+</', '><', $html));

    return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Isole les 120 caractères autour du premier caractère qui diffère.
 */
function premierÉcart(string $attendu, string $obtenu): array
{
    $limite = min(strlen($attendu), strlen($obtenu));
    $i = 0;
    while ($i < $limite && $attendu[$i] === $obtenu[$i]) {
        $i++;
    }

    return [
        'position' => $i,
        'attendu' => substr($attendu, max(0, $i - 40), 120),
        'obtenu' => substr($obtenu, max(0, $i - 40), 120),
    ];
}

exit($ok === $total ? 0 : 0);
