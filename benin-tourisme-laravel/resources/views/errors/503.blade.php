{{-- Page 503 : maintenance planifiée (mode maintenance de Laravel).
     Volontairement autonome, sans layout : la maintenance coupe aussi
     les vues Blade qui dépendent de services (session, config), et un
     `@extends` peut échouer. On garde le HTML minimal aux couleurs du site. --}}
<!doctype html>
<html lang="fr-FR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Maintenance en cours | Bonne Arrivée</title>
<meta name="description" content="Le site fait l'objet d'une maintenance planifiée. Il sera de nouveau disponible dans quelques minutes.">
<meta name="robots" content="noindex,follow">
<style>
:root{--vert:#00a05d;--jaune:#fcd116;--rouge:#e8112d;--sombre:#0b3d2c}
*,*::before,*::after{box-sizing:border-box}
html,body{margin:0;padding:0;height:100%}
body{font-family:Montserrat,Helvetica,Arial,sans-serif;color:#333;background:#fff;display:flex;flex-direction:column;min-height:100vh}
main{flex:1;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:2rem;text-align:center}
h1{font-size:2rem;color:var(--sombre);margin:0 0 1rem}
p{max-width:52ch;line-height:1.6;color:#555;margin:0 0 2rem}
.drapeau{display:flex;width:180px;height:8px;margin:1.5rem auto;border-radius:2px;overflow:hidden}
.drapeau i{flex:1}
.drapeau .v{background:var(--vert)}.drapeau .j{background:var(--jaune)}.drapeau .r{background:var(--rouge)}
</style>
</head>
<body>
    <main>
        <p style="text-transform:uppercase;letter-spacing:.1em;color:#888;font-size:.85rem;margin-bottom:.5rem">Bonne Arrivée</p>
        <h1>Maintenance en cours</h1>
        <p>Le site fait l'objet d'une maintenance planifiée.<br>
        Il sera de nouveau disponible dans quelques minutes. Merci de votre patience.</p>
        <div class="drapeau" aria-hidden="true"><i class="v"></i><i class="j"></i><i class="r"></i></div>
    </main>
</body>
</html>
