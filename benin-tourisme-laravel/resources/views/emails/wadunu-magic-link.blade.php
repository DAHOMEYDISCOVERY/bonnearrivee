<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Wa Dùnù — Votre accès</title>
</head>
<body style="margin:0;padding:0;background:#f6f4ef;font-family:'Segoe UI',Roboto,Arial,sans-serif;color:#0b3d2c">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f4ef;padding:32px 16px">
    <tr><td align="center">
    <table role="presentation" width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(11,61,44,.08)">
        <tr><td style="background:#00844d;padding:24px 32px;color:#fff">
            <div style="font-size:.78rem;letter-spacing:.2em;text-transform:uppercase;font-weight:700;opacity:.85">Wa Dùnù · Édition 2025</div>
            <div style="font-size:1.6rem;font-weight:800;margin-top:8px">Votre accès catalogue est prêt</div>
        </td></tr>
        <tr><td style="padding:32px">
            <p style="margin:0 0 16px;font-size:1rem;line-height:1.6">Bonjour,</p>
            <p style="margin:0 0 20px;font-size:1rem;line-height:1.6">
                Merci pour votre achat. Vous avez maintenant accès au carnet d'adresses gourmandes du Bénin
                — <strong>{{ \App\Models\Restaurant::published()->count() }} restaurants</strong> soigneusement sélectionnés par l'équipe éditoriale de Dahomey Discovery.
            </p>
            <div style="text-align:center;margin:32px 0">
                <a href="{{ $magicLink }}"
                   style="display:inline-block;padding:14px 32px;background:#00844d;color:#fff;text-decoration:none;border-radius:999px;font-weight:700;font-size:1rem;box-shadow:0 6px 18px rgba(0,132,77,.35)">
                    Ouvrir mon catalogue
                </a>
            </div>
            <p style="margin:0 0 12px;font-size:.9rem;line-height:1.55;color:#555">
                Ce lien est <strong>personnel</strong> : ne le partagez pas. Il vous suffit de le garder en favori
                pour retrouver vos adresses à tout moment sur n'importe quel appareil.
            </p>
            <div style="background:#f8f6f0;border-left:3px solid #fcd116;padding:14px 18px;margin:20px 0;border-radius:6px">
                <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.14em;color:#00844d;font-weight:700;margin-bottom:6px">Durée d'accès</div>
                <div style="font-size:.9rem;color:#333;line-height:1.5">
                    Accès valable jusqu'au <strong>{{ $expiresAt }}</strong>. Le lien lui-même se renouvelle
                    automatiquement tous les 30 jours tant que votre abonnement est actif.
                </div>
            </div>
            <p style="margin:24px 0 0;font-size:.85rem;color:#6a6a6a;line-height:1.55">
                Une question ? Répondez simplement à cet email ou contactez-nous sur
                <a href="https://wa.me/2290167110220" style="color:#00844d">WhatsApp</a>.
            </p>
        </td></tr>
        <tr><td style="background:#0b3d2c;padding:16px 32px;color:rgba(255,255,255,.75);font-size:.72rem;text-align:center">
            Wa Dùnù · Voyagez avec goût · Édité par <strong style="color:#fff">Dahomey Discovery</strong>
        </td></tr>
    </table>
    </td></tr>
</table>
</body>
</html>
