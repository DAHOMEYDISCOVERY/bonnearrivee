{{-- Où manger au Bénin — répertoire libre des restaurants (refonte 2026-11).
     Tout est en accès libre : plus de paiement sur cette page. Les fiches
     viennent de l'administration (Restaurants). Les adresses cochées
     « Testé par notre agence » portent une étoile et sont mises en avant.
     L'ancienne page payante est conservée dans restaurants-wadunu-payant.blade.php
     (non reliée), et les liens personnels /mes-restos/… déjà vendus restent actifs. --}}
@extends('layouts.app')

@section('bodyClass', 'page menus-v2 prod gabarit-basic_page rs-page')
@section('mainAttributes')role="main" class="content-type-page basic_page" id="main-content"@endsection

@php
    $restos = $restos ?? collect();
    $cats = \App\Models\Restaurant::CATEGORIES;
    $catImg = [
        'gastronomique' => '/assets/img/cuisine-640x480.webp',
        'maquis'        => '/assets/img/maquis-640x480.webp',
        'cafe_bar'      => '/assets/img/restos-cafe-640x480.webp',
        'fast_food'     => '/assets/img/cuisine-640x480.webp',
        'international' => '/assets/img/restos-inter-640x480.webp',
    ];
    // Photo d'une fiche : fichier envoyé depuis l'administration s'il existe,
    // sinon copie publique (assets/img/restaurants), sinon image de la catégorie.
    $photo = function ($r) use ($catImg) {
        $p = is_array($r->photos) ? ($r->photos[0] ?? null) : null;
        try {
            if ($p && \Illuminate\Support\Facades\Storage::disk('public')->exists($p)) {
                return asset('storage/'.$p);
            }
        } catch (\Throwable $e) {
        }
        foreach (array_filter([$p ? basename($p) : null, \Illuminate\Support\Str::slug($r->nom).'.webp']) as $f) {
            if (is_file(public_path('assets/img/restaurants/'.$f))) {
                return '/assets/img/restaurants/'.$f;
            }
        }

        return null; // pas de vraie photo : visuel de catégorie dessiné
    };
    $catIcon = [
        'gastronomique' => '<path fill="currentColor" d="M7 2v8a2 2 0 0 0 2 2v10h2V12a2 2 0 0 0 2-2V2h-1.5v7h-1V2h-1v7h-1V2zm10 0c-1.7 0-3 2-3 5v6h2v9h2V2z"/>',
        'maquis'        => '<path fill="currentColor" d="M3 11h18a9 9 0 0 1-18 0zm4-7c0 1.5 1 1.5 1 3s-1 1.5-1 3h1.5c0-1.5 1-1.5 1-3S8.5 5.5 8.5 4zm4 0c0 1.5 1 1.5 1 3s-1 1.5-1 3h1.5c0-1.5 1-1.5 1-3s-1-1.5-1-3zm4 0c0 1.5 1 1.5 1 3s-1 1.5-1 3h1.5c0-1.5 1-1.5 1-3s-1-1.5-1-3z"/>',
        'cafe_bar'      => '<path fill="currentColor" d="M4 8h13v5a6 6 0 0 1-6 6h-1a6 6 0 0 1-6-6zm13 1h1.5a2.5 2.5 0 0 1 0 5H17v-2h1.5a.5.5 0 0 0 0-1H17zM3 20h16v2H3zM8 2c0 1.5 1 1.5 1 3s-1 1.5-1 2h1.5c0-.5 1-1 1-2.5S9.5 3 9.5 2zm4 0c0 1.5 1 1.5 1 3s-1 1.5-1 2h1.5c0-.5 1-1 1-2.5s-1-1.5-1-2.5z"/>',
        'fast_food'     => '<path fill="currentColor" d="M3 11a9 7 0 0 1 18 0zm-1 2h20v2H2zm1 4h18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3z"/>',
        'international' => '<path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm6.9 6h-2.9a15.7 15.7 0 0 0-1.4-3.6A8 8 0 0 1 18.9 8zM12 4c.8 1.2 1.5 2.5 1.9 4h-3.8c.4-1.5 1.1-2.8 1.9-4zM4.3 14a8 8 0 0 1 0-4h3.4a16.5 16.5 0 0 0 0 4zm.8 2h2.9c.3 1.3.8 2.5 1.4 3.6A8 8 0 0 1 5.1 16zM8 8H5.1a8 8 0 0 1 4.3-3.6C8.8 5.5 8.3 6.7 8 8zm4 12c-.8-1.2-1.5-2.5-1.9-4h3.8c-.4 1.5-1.1 2.8-1.9 4zm2.3-6H9.7a14.7 14.7 0 0 1 0-4h4.6a14.7 14.7 0 0 1 0 4zm.3 5.6c.6-1.1 1.1-2.3 1.4-3.6h2.9a8 8 0 0 1-4.3 3.6zm1.7-5.6a16.5 16.5 0 0 0 0-4h3.4a8 8 0 0 1 0 4z"/>',
    ];
    // Visuel d'une fiche : sa photo, ou un visuel dessiné aux couleurs de sa catégorie.
    $visuel = function ($r, string $cls = '') use ($photo, $catIcon, $cats) {
        $src = $photo($r);
        if ($src) {
            return '<img src="'.e($src).'" alt="'.e($r->nom).'" loading="lazy" decoding="async"'.($cls ? ' class="'.$cls.'"' : '').'>';
        }
        $cat = $r->categorie;
        return '<div class="rs-ph rs-ph--'.e($cat).'" aria-hidden="true"><svg viewBox="0 0 24 24">'.($catIcon[$cat] ?? $catIcon['gastronomique']).'</svg><span>'.e($cats[$cat] ?? '').'</span></div>';
    };
    $pourquoi = function ($r): array {
        return array_values(array_filter(array_map(
            fn ($p) => trim((string) (is_array($p) ? ($p['point'] ?? '') : $p)),
            is_array($r->pourquoi_y_aller) ? $r->pourquoi_y_aller : []
        )));
    };
    $itineraire = function ($r): string {
        if ($r->gps_lat && $r->gps_lng) {
            return 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode($r->gps_lat.','.$r->gps_lng);
        }

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(trim($r->nom.' '.$r->adresse.' '.$r->quartier.' '.$r->ville.' Bénin'));
    };
    $tel = fn ($r) => $r->telephone ? preg_replace('/[^\d+]/', '', $r->telephone) : null;
    $budgetNiveau = function ($r): string {
        $b = $r->budget_min ?: $r->budget_max;
        if (! $b) {
            return '';
        }

        return $b < 5000 ? 'petit' : ($b <= 15000 ? 'moyen' : 'eleve');
    };
    $testes = $restos->where('coup_de_coeur', true)->values();
    $villes = $restos->pluck('ville')->filter()->unique()->sort()->values();
    $parCat = $restos->countBy('categorie');
    // Carrousel publicitaire (back-office « Campagnes », emplacement « restaurants »).
    $adCampaigns = collect();
    try { if (\Illuminate\Support\Facades\Schema::hasTable('ad_campaigns')) { $adCampaigns = \App\Models\AdCampaign::visible()->where('placement', 'restaurants')->get(); } }
    catch (\Throwable $e) { $adCampaigns = collect(); }
@endphp

@section('content')
<div class="content-wrapper no-terms">
@verbatim
<style>
/* ==========================================================================
   Où manger au Bénin — styles scopés .rs-*
   ========================================================================== */
.rs-page .content-wrapper{padding-top:0}
.rs{--rs-ink:#14201a;--rs-soft:#4b5550;--rs-muted:#6f7873;--rs-line:#e7e9e4;--rs-paper:#f7f6f1;--rs-green:var(--benin-green,#00a05d);--rs-dark:var(--benin-dark,#0b3d2c);--rs-gold:#e5a50a;--rs-star:#f5b301;--rs-r:20px;--rs-r-sm:14px;--rs-max:1240px;color:var(--rs-ink)}
.rs *{box-sizing:border-box}
.rs section{scroll-margin-top:96px}
.rs-wrap{max-width:var(--rs-max);margin:0 auto;padding:0 24px}
.rs-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.74rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--rs-green);margin:0 0 12px}
.rs-h2{font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:clamp(1.9rem,3.4vw,2.8rem);line-height:1.12;margin:0 0 12px;color:var(--rs-ink)}
.rs-lead{font-size:1.05rem;line-height:1.65;color:var(--rs-soft);margin:0;max-width:680px}
.rs-head{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:34px}
.rs-star{display:inline-flex;align-items:center;justify-content:center;color:var(--rs-star)}
.rs-star svg{width:1em;height:1em;display:block}

/* ---------- HERO ---------- */
.rs-hero{position:relative;background:var(--rs-dark);color:#fff;overflow:hidden;isolation:isolate}
.rs-hero::before{content:"";position:absolute;inset:0;z-index:-1;background:radial-gradient(900px 500px at 85% 20%,rgba(0,160,93,.35),transparent 60%),radial-gradient(700px 420px at 0% 100%,rgba(252,209,22,.12),transparent 60%)}
.rs-hero__grid{display:grid;grid-template-columns:1.05fr 1fr;gap:56px;align-items:center;padding:40px 0 72px}
.rs-crumb{display:flex;flex-wrap:wrap;gap:8px;list-style:none;margin:0 0 34px;padding:0;font-size:.82rem;color:rgba(255,255,255,.7)}
.rs-crumb a{color:rgba(255,255,255,.85);text-decoration:none}
.rs-crumb a:hover{color:#fff;text-decoration:underline}
.rs-hero .rs-eyebrow{color:var(--benin-yellow,#fcd116)}
.rs-hero__title{font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:clamp(2.6rem,5.6vw,4.6rem);line-height:1.02;letter-spacing:-.01em;margin:0 0 22px;color:#fff}
.rs-hero__title em{font-style:italic;color:var(--benin-yellow,#fcd116)}
.rs-hero__lead{font-size:1.12rem;line-height:1.65;color:rgba(255,255,255,.86);margin:0 0 30px;max-width:540px}
.rs-hero__lead .rs-star{vertical-align:-2px}
.rs-ctas{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:38px}
.rs-btn{display:inline-flex;align-items:center;gap:10px;padding:15px 24px;border-radius:999px;font-weight:600;font-size:.96rem;text-decoration:none;transition:transform .2s ease,background .2s ease,color .2s ease,box-shadow .2s ease;border:0;cursor:pointer}
.rs-btn:hover{transform:translateY(-2px)}
.rs-btn--gold{background:var(--benin-yellow,#fcd116);color:var(--rs-dark)}
.rs-btn--gold:hover{box-shadow:0 12px 30px rgba(252,209,22,.35);color:var(--rs-dark)}
.rs-btn--ghost{background:rgba(255,255,255,.08);color:#fff;box-shadow:inset 0 0 0 1px rgba(255,255,255,.35)}
.rs-btn--ghost:hover{background:rgba(255,255,255,.16);color:#fff}
.rs-stats{display:flex;flex-wrap:wrap;gap:0;border-top:1px solid rgba(255,255,255,.16);padding-top:22px}
.rs-stat{padding-right:28px;margin-right:28px;border-right:1px solid rgba(255,255,255,.16)}
.rs-stat:last-child{border-right:0;margin-right:0;padding-right:0}
.rs-stat b{display:block;font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:2.2rem;line-height:1;color:#fff}
.rs-stat span{font-size:.8rem;letter-spacing:.06em;text-transform:uppercase;color:rgba(255,255,255,.7)}
.rs-mosaic{display:grid;grid-template-columns:1fr 1fr;grid-template-rows:200px 160px 200px;gap:14px}
.rs-mosaic figure{position:relative;margin:0;border-radius:18px;overflow:hidden;background:#123}
.rs-mosaic figure:nth-child(1){grid-row:1/3}
.rs-mosaic figure:nth-child(4){grid-row:2/4}
.rs-mosaic img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .8s ease}
.rs-mosaic figure:hover img{transform:scale(1.05)}
.rs-mosaic figcaption{position:absolute;left:10px;bottom:10px;right:10px;display:flex;align-items:center;gap:6px;width:max-content;max-width:calc(100% - 20px);padding:6px 11px;border-radius:999px;background:rgba(11,20,16,.72);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);font-size:.76rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

/* ---------- BANDEAU ÉTOILE ---------- */
.rs-legend{background:#fff;border-bottom:1px solid var(--rs-line)}
.rs-legend__in{display:grid;grid-template-columns:repeat(3,1fr);gap:0}
.rs-legend__it{display:flex;gap:14px;align-items:flex-start;padding:26px 24px;border-right:1px solid var(--rs-line)}
.rs-legend__it:last-child{border-right:0}
.rs-legend__ic{flex:0 0 44px;height:44px;border-radius:12px;display:grid;place-items:center;background:var(--rs-paper);color:var(--rs-green)}
.rs-legend__ic svg{width:22px;height:22px}
.rs-legend__ic.is-star{background:#fff6d6;color:var(--rs-star)}
.rs-legend__it strong{display:block;font-size:.98rem;margin-bottom:3px}
.rs-legend__it p{margin:0;font-size:.9rem;line-height:1.5;color:var(--rs-soft)}

/* ---------- TESTÉS ---------- */
.rs-tested{background:var(--rs-paper);padding:88px 0}
.rs-tested__grid{display:grid;grid-template-columns:repeat(3,1fr);gap:26px}
.rs-feat{display:flex;flex-direction:column;background:#fff;border-radius:var(--rs-r);overflow:hidden;box-shadow:0 1px 0 var(--rs-line),0 18px 40px -24px rgba(11,61,44,.25);transition:transform .25s ease,box-shadow .25s ease}
.rs-feat:hover{transform:translateY(-4px);box-shadow:0 1px 0 var(--rs-line),0 28px 56px -26px rgba(11,61,44,.35)}
.rs-feat__media{position:relative;aspect-ratio:16/10;background:#e9ece6;overflow:hidden}
.rs-feat__media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.rs-badge{position:absolute;top:14px;left:14px;display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border-radius:999px;background:#fff;color:var(--rs-ink);font-size:.74rem;font-weight:700;box-shadow:0 6px 18px rgba(0,0,0,.15)}
.rs-badge .rs-star{font-size:1rem}
.rs-feat__body{padding:22px 22px 20px;display:flex;flex-direction:column;flex:1}
.rs-meta{display:flex;flex-wrap:wrap;gap:6px 12px;align-items:center;font-size:.8rem;color:var(--rs-muted);margin-bottom:8px}
.rs-meta__cat{font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--rs-green);font-size:.7rem}
.rs-loc{display:inline-flex;align-items:center;gap:5px}
.rs-loc svg{width:13px;height:13px;flex:0 0 13px}
.rs-feat__name{font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:1.45rem;line-height:1.15;margin:0 0 6px;color:var(--rs-ink)}
.rs-feat__sub{margin:0 0 14px;color:var(--rs-soft);font-size:.95rem;line-height:1.5}
.rs-why{list-style:none;margin:0 0 14px;padding:0;display:grid;gap:7px}
.rs-why li{position:relative;padding-left:22px;font-size:.9rem;line-height:1.45;color:var(--rs-ink)}
.rs-why li::before{content:"";position:absolute;left:2px;top:.45em;width:10px;height:10px;border-radius:3px;background:var(--rs-green);transform:rotate(45deg) scale(.8)}
.rs-tip{display:flex;gap:10px;align-items:flex-start;margin:0 0 16px;font-size:.88rem;line-height:1.55;color:var(--rs-ink)}
.rs-tip__ic{flex:0 0 22px;width:22px;height:22px;margin-top:1px;color:var(--rs-green)}
.rs-tip__ic svg{width:22px;height:22px;display:block}
.rs-tip b{display:block;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--rs-green);margin-bottom:2px}
.rs-facts{display:grid;gap:7px;margin:0 0 18px}
.rs-fact{display:flex;align-items:flex-start;gap:8px;font-size:.84rem;line-height:1.45;color:var(--rs-soft)}
.rs-fact svg{flex:0 0 15px;width:15px;height:15px;margin-top:2px;color:var(--rs-green)}
.rs-actions{margin-top:auto;display:flex;flex-wrap:wrap;gap:8px}
.rs-act{display:inline-flex;align-items:center;gap:7px;padding:9px 14px;border-radius:999px;font-size:.84rem;font-weight:600;text-decoration:none;border:1px solid var(--rs-line);color:var(--rs-ink);background:#fff;transition:background .2s,border-color .2s,color .2s}
.rs-act:hover{border-color:var(--rs-green);color:var(--rs-green)}
.rs-act--main{background:var(--rs-green);border-color:var(--rs-green);color:#fff}
.rs-act--main:hover{background:var(--rs-dark);border-color:var(--rs-dark);color:#fff}
.rs-act svg{width:15px;height:15px}

.rs-ph{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;color:rgba(255,255,255,.92);background-image:radial-gradient(rgba(255,255,255,.12) 1px,transparent 1.5px),linear-gradient(135deg,var(--c1),var(--c2));background-size:14px 14px,100% 100%}
.rs-ph svg{width:46px;height:46px;opacity:.95}
.rs-ph span{font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;opacity:.85}
.rs-ph--gastronomique{--c1:#0b3d2c;--c2:#1f7a52}
.rs-ph--maquis{--c1:#8a3b12;--c2:#d0702a}
.rs-ph--cafe_bar{--c1:#3b2a20;--c2:#8a5a3c}
.rs-ph--fast_food{--c1:#9b1426;--c2:#e2453b}
.rs-ph--international{--c1:#123b63;--c2:#2f7fb5}
.rs-feat__media .rs-ph svg{width:64px;height:64px}

/* ---------- UNIVERS ---------- */
.rs-univers{padding:84px 0 30px;background:#fff}
.rs-univers__grid{display:grid;grid-template-columns:repeat(5,1fr);gap:14px}
.rs-uni{position:relative;display:flex;flex-direction:column;justify-content:flex-end;min-height:220px;padding:18px;border-radius:var(--rs-r-sm);overflow:hidden;color:#fff;text-align:left;border:0;cursor:pointer;background:#222;isolation:isolate;font:inherit}
.rs-uni img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:-2;transition:transform .7s ease}
.rs-uni::after{content:"";position:absolute;inset:0;z-index:-1;background:linear-gradient(180deg,rgba(0,0,0,0) 30%,rgba(8,24,17,.85))}
.rs-uni:hover img,.rs-uni:focus-visible img{transform:scale(1.07)}
.rs-uni b{font-size:1.02rem;line-height:1.25}
.rs-uni span{font-size:.8rem;opacity:.85;margin-top:3px}

/* ---------- RÉPERTOIRE ---------- */
.rs-dir{padding:60px 0 96px;background:#fff}
.rs-tools{position:sticky;top:0;z-index:5;display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:14px;margin:0 0 22px;border-radius:var(--rs-r-sm);background:rgba(255,255,255,.94);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);box-shadow:0 0 0 1px var(--rs-line),0 14px 30px -24px rgba(0,0,0,.35)}
.rs-search{position:relative;flex:1 1 260px}
.rs-search svg{position:absolute;left:14px;top:50%;width:18px;height:18px;transform:translateY(-50%);color:var(--rs-muted)}
.rs-search input{width:100%;height:46px;margin:0;padding:0 14px 0 42px;border:1px solid var(--rs-line);border-radius:999px;background:#fff;font:inherit;font-size:.95rem;box-shadow:none}
.rs-search input:focus,.rs-select:focus{outline:none;border-color:var(--rs-green);box-shadow:0 0 0 3px rgba(0,160,93,.15)}
.rs-select{height:46px;margin:0;padding:0 34px 0 14px;border:1px solid var(--rs-line);border-radius:999px;background-color:#fff;font:inherit;font-size:.9rem;width:auto;min-width:150px}
.rs-toggle{display:inline-flex;align-items:center;gap:8px;height:46px;padding:0 16px;border-radius:999px;border:1px solid var(--rs-line);background:#fff;font:inherit;font-size:.9rem;font-weight:600;cursor:pointer;color:var(--rs-ink)}
.rs-toggle[aria-pressed="true"]{background:#fff6d6;border-color:#f3cf5a}
.rs-toggle .rs-star{font-size:1.05rem}
.rs-reset{background:none;border:0;font:inherit;font-size:.86rem;color:var(--rs-muted);text-decoration:underline;cursor:pointer;padding:0 6px}
.rs-count{margin:0 0 18px;font-size:.92rem;color:var(--rs-soft)}
.rs-count b{color:var(--rs-ink)}
.rs-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
.rs-card{display:flex;flex-direction:column;background:#fff;border-radius:var(--rs-r-sm);overflow:hidden;box-shadow:0 0 0 1px var(--rs-line);transition:transform .22s ease,box-shadow .22s ease}
.rs-card:hover{transform:translateY(-3px);box-shadow:0 0 0 1px var(--rs-line),0 18px 36px -22px rgba(11,61,44,.35)}
.rs-card.is-tested{box-shadow:0 0 0 2px #f3cf5a}
.rs-card.is-hidden{display:none}
.rs-card__media{position:relative;aspect-ratio:16/10;background:#eef0ea;overflow:hidden}
.rs-card__media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}
.rs-card__body{padding:16px 18px 18px;display:flex;flex-direction:column;flex:1}
.rs-card__name{display:flex;align-items:center;gap:7px;font-size:1.12rem;font-weight:700;line-height:1.25;margin:0 0 4px;color:var(--rs-ink)}
.rs-card__name .rs-star{font-size:1.05rem;flex:0 0 auto}
.rs-card__sub{margin:0 0 12px;font-size:.88rem;line-height:1.45;color:var(--rs-soft)}
.rs-more{margin:0 0 14px;border-top:1px solid var(--rs-line);padding-top:10px}
.rs-more summary{cursor:pointer;font-size:.84rem;font-weight:600;color:var(--rs-green);list-style:none}
.rs-more summary::-webkit-details-marker{display:none}
.rs-more summary::after{content:" +";}
.rs-more[open] summary::after{content:" –";}
.rs-dl{display:grid;gap:8px;margin:10px 0 0;font-size:.86rem}
.rs-dl div{display:grid;grid-template-columns:92px 1fr;gap:8px}
.rs-dl dt{color:var(--rs-muted);font-weight:600}
.rs-dl dd{margin:0;color:var(--rs-ink)}
.rs-empty{text-align:center;padding:40px 0;color:var(--rs-soft)}
.rs-linkbtn{background:none;border:0;padding:0;font:inherit;color:var(--rs-green);text-decoration:underline;cursor:pointer}
.rs-moreall{display:flex;justify-content:center;margin-top:30px}
.rs-moreall button{padding:14px 26px;border-radius:999px;border:1px solid var(--rs-ink);background:#fff;font:inherit;font-weight:600;cursor:pointer}
.rs-moreall button:hover{background:var(--rs-ink);color:#fff}

/* ---------- RESTAURATEURS ---------- */
.rs-pro{padding:0 0 96px;background:#fff}
.rs-pro__card{display:grid;grid-template-columns:1.4fr 1fr;gap:30px;align-items:center;padding:44px 48px;border-radius:var(--rs-r);background:linear-gradient(120deg,var(--rs-dark),#0f5a3e);color:#fff;position:relative;overflow:hidden}
.rs-pro__card::after{content:"";position:absolute;right:-80px;top:-80px;width:280px;height:280px;border-radius:50%;background:rgba(252,209,22,.12)}
.rs-pro h2{font-family:"Playfair Display",Georgia,serif;font-weight:500;font-size:clamp(1.6rem,2.6vw,2.2rem);margin:0 0 10px;color:#fff}
.rs-pro p{margin:0;color:rgba(255,255,255,.82);line-height:1.6}
.rs-pro__ctas{display:flex;flex-wrap:wrap;gap:10px;justify-content:flex-end;position:relative;z-index:1}

@media (max-width:1100px){
  .rs-univers__grid{grid-template-columns:repeat(3,1fr)}
  .rs-grid{grid-template-columns:repeat(2,1fr)}
  .rs-tested__grid{grid-template-columns:repeat(2,1fr)}
}
@media (max-width:900px){
  .rs-hero__grid{grid-template-columns:1fr;gap:34px;padding:28px 0 52px}
  .rs-mosaic{grid-template-rows:150px 110px 150px}
  .rs-legend__in{grid-template-columns:1fr}
  .rs-legend__it{border-right:0;border-bottom:1px solid var(--rs-line)}
  .rs-legend__it:last-child{border-bottom:0}
  .rs-pro__card{grid-template-columns:1fr;padding:32px 26px}
  .rs-pro__ctas{justify-content:flex-start}
}
@media (max-width:640px){
  .rs-wrap{padding:0 16px}
  .rs-tested__grid{grid-template-columns:1fr}
  .rs-univers__grid{grid-template-columns:repeat(2,1fr)}
  .rs-uni{min-height:150px}
  .rs-grid{grid-template-columns:1fr}
  .rs-stat{padding-right:16px;margin-right:16px}
  .rs-stat b{font-size:1.7rem}
  .rs-tools{position:static}
  .rs-select{flex:1 1 calc(50% - 5px);min-width:0}
  .rs-toggle{flex:1 1 100%;justify-content:center}
  .rs-tested,.rs-univers{padding-top:60px}
  .rs-tested{padding-bottom:60px}
}
@media (prefers-reduced-motion:reduce){.rs *{transition:none!important}}
</style>
@endverbatim

@php
    $svgStar = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2.5l2.9 6 6.6.8-4.9 4.6 1.3 6.6L12 17.3l-5.9 3.2 1.3-6.6L2.5 9.3l6.6-.8z"/></svg>';
    $svgPin = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>';
    $svgTel = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8a15.5 15.5 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.4c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.4 0 .8-.3 1z"/></svg>';
    $svgGo = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M21.7 11.3l-9-9a1 1 0 0 0-1.4 0l-9 9a1 1 0 0 0 0 1.4l9 9a1 1 0 0 0 1.4 0l9-9a1 1 0 0 0 0-1.4zM14 14.5V12h-4v3H8v-4a1 1 0 0 1 1-1h5V7.5l3.5 3.5z"/></svg>';
    $svgWallet = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3 6a2 2 0 0 1 2-2h13v3H5v.5h16V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2zm14 7.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/></svg>';
    $svgInfo = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 15h-2v-6h2zm0-8h-2V7h2z"/></svg>';
    $svgFork = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7 2v8a2 2 0 0 0 2 2v10h2V12a2 2 0 0 0 2-2V2h-1.5v7h-1V2h-1v7h-1V2zm10 0c-1.7 0-3 2-3 5v6h2v9h2V2z"/></svg>';
    $svgClock = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 10.4V6h-2v7.4l5 3 1-1.7z"/></svg>';
    $mosaic = [
        ['/assets/img/restaurants/chablis-restaurant.webp', 'Chablis Restaurant · Cotonou'],
        ['/assets/img/restaurants/la-casa-cielo.webp', 'La Casa Cielo · Fidjrossè'],
        ['/assets/img/restaurants/kole.webp', 'Kolè · Ouidah'],
        ['/assets/img/restaurants/cabane-du-pecheur.webp', 'Cabane du Pêcheur · Cotonou'],
    ];
@endphp

<div class="rs">

  {{-- ================= HERO ================= --}}
  <section class="rs-hero" aria-labelledby="rs-title">
    <div class="rs-wrap">
      <div class="rs-hero__grid">
        <div>
<p class="rs-eyebrow">Guide gourmand · Édition 2026</p>
          <h1 class="rs-hero__title" id="rs-title">Où manger <em>au Bénin</em></h1>
          <p class="rs-hero__lead">Restaurants, maquis, cafés et bars&nbsp;: toutes nos adresses, en accès libre. Celles marquées d’une étoile <span class="rs-star">{!! $svgStar !!}</span> ont été testées sur place par l’équipe de Bonne Arrivée.</p>
          <div class="rs-ctas">
            @if ($testes->count())<a class="rs-btn rs-btn--gold" href="#testes"><span class="rs-star" style="color:inherit">{!! $svgStar !!}</span> Les adresses testées</a>@endif
            <a class="rs-btn rs-btn--ghost" href="#repertoire">Parcourir le répertoire</a>
          </div>
          <div class="rs-stats" data-ba-dynamique>
            <div class="rs-stat"><b>{{ $restos->count() }}</b><span>adresses</span></div>
            <div class="rs-stat"><b>{{ $villes->count() }}</b><span>{{ $villes->count() > 1 ? 'villes' : 'ville' }}</span></div>
            <div class="rs-stat"><b>{{ $testes->count() }}</b><span>testées <span class="rs-star" style="display:inline-flex;font-size:.9em">{!! $svgStar !!}</span></span></div>
          </div>
        </div>
        <div class="rs-mosaic" aria-hidden="true">
          @foreach ($mosaic as [$src, $cap])
            <figure><img src="{{ $src }}" alt="" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async" onerror="this.src='/assets/img/cuisine-640x480.webp'"><figcaption><span class="rs-star">{!! $svgStar !!}</span>{{ $cap }}</figcaption></figure>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @verbatim<style>.rs-crumbbar{background:#fff;border-bottom:1px solid #e7e9e4;padding:.9rem 0}.rs-crumbbar .rs-crumb{justify-content:center;margin:0!important;color:#5d6b64!important;font-size:.82rem}.rs-crumbbar .rs-crumb a{color:#3d4a43!important}.rs-crumbbar .rs-crumb [aria-current]{color:var(--rs-green,#00a05d)!important}</style>@endverbatim
  <div class="rs-crumbbar"><div class="rs-wrap"><nav aria-label="Fil d'Ariane">
            <ol class="rs-crumb">
              <li><a href="/">Accueil</a></li><li aria-hidden="true">/</li>
              <li><a href="/sejourner">Organisez</a></li><li aria-hidden="true">/</li>
              <li aria-current="page">Où manger</li>
            </ol>
          </nav></div></div>

  @verbatim<style>
.rs-ads{background:var(--rs-paper,#f7f6f1);padding:40px 0 44px}
.rs-ads__top{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}
.rs-ads__eyebrow{margin:0;font-size:.74rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--rs-green,#00a05d)}
.rs-ads__tag{font-size:.62rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6f7873;border:1px solid #e7e9e4;border-radius:999px;padding:4px 10px;background:#fff}
.rs-ads__viewport{position:relative;border-radius:18px;overflow:hidden;background:#0b3d2c;box-shadow:0 18px 40px -24px rgba(11,61,44,.45)}
.rs-ads__track{display:flex;transition:transform .6s cubic-bezier(.4,.01,.2,1)}
.rs-ads__slide{position:relative;flex:0 0 100%}
.rs-ads__img{display:block;width:100%;height:clamp(240px,34vw,400px);object-fit:cover}
.rs-ads__scrim{position:absolute;inset:0;background:linear-gradient(90deg,rgba(7,30,22,.92) 0%,rgba(7,30,22,.62) 42%,rgba(7,30,22,.06) 72%,transparent),linear-gradient(0deg,rgba(7,30,22,.5),transparent 55%)}
.rs-ads__panel{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;gap:12px;padding:clamp(22px,4vw,56px);padding-left:clamp(64px,6vw,88px);max-width:min(640px,74%);color:#fff}
.rs-ads__advertiser{margin:0;font-size:.76rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:#fcd116}
.rs-ads__title{margin:0;font-family:"Playfair Display",Georgia,serif;font-weight:600;line-height:1.1;font-size:clamp(1.5rem,3.2vw,2.4rem);color:#fff}
.rs-ads__desc{margin:0;font-size:clamp(.92rem,1.4vw,1.05rem);line-height:1.5;color:rgba(255,255,255,.9);max-width:46ch}
.rs-ads__btn{align-self:flex-start;display:inline-flex;align-items:center;gap:.5em;margin-top:6px;background:#fcd116;color:#1a1405!important;font-weight:600;font-size:.96rem;text-decoration:none!important;padding:.72em 1.4em;border-radius:999px;box-shadow:0 6px 18px rgba(0,0,0,.2);transition:transform .2s}
.rs-ads__btn:hover{transform:translateY(-2px)}
.rs-ads__nav{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:44px;height:44px;border-radius:50%;border:0;cursor:pointer;background:rgba(255,255,255,.92);color:#0b3d2c;font-size:1.6rem;line-height:1;display:grid;place-items:center;box-shadow:0 4px 14px rgba(0,0,0,.2)}
.rs-ads__nav--prev{left:14px}.rs-ads__nav--next{right:14px}
.rs-ads__dots{display:flex;gap:9px;justify-content:center;margin-top:14px}
.rs-ads__dot{width:9px;height:9px;border-radius:50%;border:0;padding:0;cursor:pointer;background:#d9ddd6;transition:all .25s}
.rs-ads__dot[aria-selected="true"]{background:var(--rs-green,#00a05d);width:26px;border-radius:999px}
.rs-ads__empty{border:1px dashed #d9ddd6;border-radius:18px;background:#fff;padding:clamp(24px,4vw,44px);text-align:center}
.rs-ads__empty h3{margin:0 0 8px;font-family:"Playfair Display",Georgia,serif;font-size:clamp(1.2rem,2.4vw,1.6rem);color:#0b3d2c}
.rs-ads__empty p{margin:0 auto 18px;max-width:56ch;color:#4b5550}
.rs-ads__empty a{display:inline-block;background:#0b3d2c;color:#fff!important;text-decoration:none!important;font-weight:600;padding:.7em 1.5em;border-radius:999px}
@media (max-width:640px){.rs-ads__panel{max-width:100%;padding-left:24px;padding-right:24px}.rs-ads__nav{display:none}}
@media (prefers-reduced-motion:reduce){.rs-ads__track{transition:none}}
</style>@endverbatim
  {{-- ================= ESPACE PUBLICITAIRE (carrousel) ================= --}}
  <section class="rs-ads" data-ba-dynamique aria-label="Espace partenaires">
    <div class="rs-wrap">
      <div class="rs-ads__top"><p class="rs-ads__eyebrow">Espace partenaires</p><span class="rs-ads__tag">Publicité</span></div>
      @if ($adCampaigns->count())
        <div class="rs-ads__viewport">
          <div class="rs-ads__track">
            @foreach ($adCampaigns as $k => $c)
              <div class="rs-ads__slide" role="group" aria-roledescription="diapositive" aria-label="{{ $k + 1 }} sur {{ $adCampaigns->count() }}">
                <img class="rs-ads__img" src="{{ $c->image_url }}" alt="{{ $c->image_alt ?: $c->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async">
                <div class="rs-ads__scrim"></div>
                <div class="rs-ads__panel">
                  @if ($c->advertiser)<p class="rs-ads__advertiser">{{ $c->advertiser }}</p>@endif
                  <h3 class="rs-ads__title">{{ $c->title }}</h3>
                  @if ($c->description)<p class="rs-ads__desc">{{ $c->description }}</p>@endif
                  @if ($c->url)<a class="rs-ads__btn" href="{{ $c->url }}" target="_blank" rel="noopener sponsored">{{ $c->button_label ?: 'En savoir plus' }}<span aria-hidden="true">&rarr;</span></a>@endif
                </div>
              </div>
            @endforeach
          </div>
          @if ($adCampaigns->count() > 1)
            <button class="rs-ads__nav rs-ads__nav--prev" type="button" aria-label="Annonce précédente">&lsaquo;</button>
            <button class="rs-ads__nav rs-ads__nav--next" type="button" aria-label="Annonce suivante">&rsaquo;</button>
          @endif
        </div>
        @if ($adCampaigns->count() > 1)<div class="rs-ads__dots" role="tablist" aria-label="Choisir une annonce"></div>@endif
      @else
        <div class="rs-ads__empty">
          <h3>Cet espace accueille les campagnes de nos partenaires</h3>
          <p>Restaurants, maquis, bars, traiteurs : faites découvrir votre établissement aux visiteurs de Bonne Arrivée.</p>
          <a href="/sejourner/toutes-les-infos-pratiques/nous-contacter">Devenir partenaire</a>
        </div>
      @endif
    </div>
  </section>

  {{-- ================= GUIDE EXPRESS : budgets et adresses ================= --}}
  @verbatim<style>
.rs-guide{background:#fff;padding:64px 0 72px}
.rs-guide__lead{max-width:860px;font-size:1.08rem;line-height:1.75;color:var(--rs-soft,#4b5550);margin:0 0 34px}
.rs-guide__tiers{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:34px}
.rs-tier{border-radius:20px;padding:26px 24px;background:var(--rs-paper,#f7f6f1);border-top:5px solid var(--c)}
.rs-tier small{display:block;font-size:.7rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase;color:var(--c)}
.rs-tier h3{font-family:"Playfair Display",Georgia,serif;font-weight:600;font-size:1.45rem;line-height:1.15;margin:.5rem 0 .3rem;color:var(--rs-ink,#14201a)}
.rs-tier b{display:block;font-size:1.05rem;color:var(--rs-ink,#14201a);margin-bottom:.7rem}
.rs-tier p{margin:0;font-size:.92rem;line-height:1.6;color:var(--rs-soft,#4b5550)}
.rs-guide__addr{display:grid;grid-template-columns:1.2fr 1fr;gap:18px}
.rs-addr{border:1px solid var(--rs-line,#e7e9e4);border-radius:20px;padding:24px}
.rs-addr h3{font-family:"Playfair Display",Georgia,serif;font-weight:600;font-size:1.25rem;margin:0 0 .8rem;color:var(--rs-ink,#14201a)}
.rs-addr dl{margin:0;display:grid;gap:.7rem}
.rs-addr dt{font-size:.7rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--rs-green,#00a05d)}
.rs-addr dd{margin:.15rem 0 0;font-size:.93rem;line-height:1.55;color:var(--rs-ink,#14201a)}
@media (max-width:900px){.rs-guide__tiers,.rs-guide__addr{grid-template-columns:1fr}}
</style>@endverbatim
  <section class="rs-guide" aria-labelledby="rs-guide-title">
    <div class="rs-wrap">
      <p class="rs-eyebrow">Le guide express</p>
      <h2 class="rs-h2" id="rs-guide-title">Bien manger au Bénin, à chaque budget</h2>
      <p class="rs-guide__lead">Au Bénin, l’offre de restauration s’étend des maquis populaires aux restaurants locaux et internationaux, à Cotonou comme à Porto-Novo, Ouidah ou Grand-Popo, et jusqu’en bord de mer.</p>
      <div class="rs-guide__tiers">
        <div class="rs-tier" style="--c:#c2562b"><small>Maquis et cuisine locale</small><h3>Les saveurs béninoises</h3><b>≈ 500 à 6 000 F CFA</b><p>Akassa, pâte d’arachide, atassi, gbègiri, poulet bicyclette : les spécialités du pays à petit prix, dans une atmosphère détendue.</p></div>
        <div class="rs-tier" style="--c:#1d5c8a"><small>Gamme moyenne et internationale</small><h3>Le confort en ville</h3><b>≈ 3 000 à 8 500 F CFA</b><p>Très présents à Cotonou (Haie Vive, Cadjèhoun, Fidjrossè) : cuisines africaine, française, italienne, asiatique, libanaise, adaptées aux familles et aux rendez-vous professionnels.</p></div>
        <div class="rs-tier" style="--c:#b8902e"><small>Tables gastronomiques et bord de mer</small><h3>L’océan en toile de fond</h3><b>≈ 10 000 à 25 000 F CFA</b><p>À Fidjrossè et le long de la côte : l’ambiance, la vue sur l’océan, les grillades et les produits de la mer.</p></div>
      </div>
      <div class="rs-guide__addr">
        <div class="rs-addr"><h3>Quelques adresses à Cotonou</h3><dl><div><dt>Cuisine locale et petit budget</dt><dd>Maquis Super Pili Pili (boulevard Saint-Michel), Maquis du Port, Karim 24</dd></div><div><dt>Plage et grillades, à Fidjrossè</dt><dd>La Cabane du Pêcheur, Face à la Mer, Jaaba – Grill &amp; Bar</dd></div><div><dt>International et lounge</dt><dd>L’Orangerie (Cadjèhoun), Le Karé Électrique (Haie Vive), Bangkok Terrasse, Shamiana</dd></div></dl></div>
        <div class="rs-addr"><h3>En dehors de Cotonou</h3><dl><div><dt>Porto-Novo</dt><dd>Restaurant Art Residence, L’Escale Zevougnon, AfricanFoodseum</dd></div><div><dt>Ouidah</dt><dd>Hakuna Matata Restaurant Grillades, Kolè</dd></div><div><dt>Grand-Popo</dt><dd>Boca del Rio</dd></div></dl></div>
      </div>
    </div>
  </section>

  {{-- ================= CE QUE SIGNIFIE L'ÉTOILE ================= --}}
  <section class="rs-legend" aria-label="Comment lire ce guide">
    <div class="rs-wrap rs-legend__in">
      <div class="rs-legend__it"><span class="rs-legend__ic is-star">{!! $svgStar !!}</span><div><strong>L’étoile = testé par notre agence</strong><p>L’équipe de Bonne Arrivée s’y est attablée et la recommande à ses voyageurs.</p></div></div>
      <div class="rs-legend__it"><span class="rs-legend__ic"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 1 3 5v6c0 5.5 3.8 10.7 9 12 5.2-1.3 9-6.5 9-12V5l-9-4zm-1 15-4-4 1.4-1.4L11 13.2l5.6-5.6L18 9l-7 7z"/></svg></span><div><strong>Gratuit, sans inscription</strong><p>Tout le répertoire est consultable librement, sur ordinateur comme sur téléphone.</p></div></div>
      <div class="rs-legend__it"><span class="rs-legend__ic">{!! $svgGo !!}</span><div><strong>Appeler, s’y rendre</strong><p>Chaque fiche donne le contact et l’itinéraire en un clic quand nous les avons.</p></div></div>
    </div>
  </section>

  {{-- ================= TESTÉS PAR L'AGENCE ================= --}}
  @if ($testes->count())
  <section class="rs-tested" id="testes" aria-labelledby="rs-tested-title">
    <div class="rs-wrap">
      <div class="rs-head">
        <div>
          <p class="rs-eyebrow"><span class="rs-star">{!! $svgStar !!}</span> Sélection Bonne Arrivée</p>
          <h2 class="rs-h2" id="rs-tested-title">Testés et approuvés par notre agence</h2>
          <p class="rs-lead">Les tables où nous emmenons nos voyageurs, les yeux fermés.</p>
        </div>
      </div>
      <div class="rs-tested__grid" data-ba-dynamique>
        @foreach ($testes as $r)
          @php $why = array_slice($pourquoi($r), 0, 3); $t = $tel($r); @endphp
          <article class="rs-feat">
            <div class="rs-feat__media">
              {!! $visuel($r) !!}
              <span class="rs-badge"><span class="rs-star">{!! $svgStar !!}</span> Testé par notre agence</span>
            </div>
            <div class="rs-feat__body">
              <div class="rs-meta">
                <span class="rs-meta__cat">{{ $cats[$r->categorie] ?? $r->categorie }}</span>
                <span class="rs-loc">{!! $svgPin !!}{{ $r->ville }}@if ($r->quartier) · {{ $r->quartier }}@endif</span>
              </div>
              <h3 class="rs-feat__name">{{ $r->nom }}</h3>
              @if ($r->sous_titre)<p class="rs-feat__sub">{{ $r->sous_titre }}</p>@endif
              @if (count($why))
                <ul class="rs-why">@foreach ($why as $w)<li>{{ $w }}</li>@endforeach</ul>
              @endif
              @if ($r->conseil_dahomey)
                <div class="rs-tip"><span class="rs-tip__ic">{!! $svgInfo !!}</span><p style="margin:0"><b>Le conseil de l’agence</b>{{ $r->conseil_dahomey }}</p></div>
              @endif
              <div class="rs-facts">
                @if ($r->specialite)<span class="rs-fact">{!! $svgFork !!}<span>{{ $r->specialite }}</span></span>@endif
                @if ($r->budget_min || $r->budget_max)<span class="rs-fact">{!! $svgWallet !!}<span>{{ $r->budget_label }}</span></span>@endif
                @if ($r->horaires)<span class="rs-fact">{!! $svgClock !!}<span>{{ $r->horaires }}</span></span>@endif
              </div>
              <div class="rs-actions">
                @if ($t)<a class="rs-act rs-act--main" href="tel:{{ $t }}">{!! $svgTel !!}Appeler</a>@endif
                <a class="rs-act{{ $t ? '' : ' rs-act--main' }}" href="{{ $itineraire($r) }}" target="_blank" rel="noopener noreferrer">{!! $svgGo !!}Itinéraire</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ================= UNIVERS ================= --}}
  <section class="rs-univers" aria-labelledby="rs-uni-title">
    <div class="rs-wrap">
      <div class="rs-head">
        <div>
          <p class="rs-eyebrow">Par envie</p>
          <h2 class="rs-h2" id="rs-uni-title">Cinq univers gourmands</h2>
        </div>
      </div>
      @php
        $uniImg = [
          'gastronomique' => '/assets/img/restaurants/chablis-restaurant.webp',
          'maquis'        => '/assets/img/maquis-640x480.webp',
          'cafe_bar'      => '/assets/img/restaurants/kole.webp',
          'fast_food'     => '/assets/img/restos-inter-640x480.webp',
          'international' => '/assets/img/restaurants/la-casa-cielo.webp',
        ];
      @endphp
      <div class="rs-univers__grid" data-ba-dynamique>
        @php $ancres = ['gastronomique' => 'gastronomique', 'maquis' => 'maquis', 'cafe_bar' => 'cafes-bars', 'fast_food' => 'fast-food', 'international' => 'international']; @endphp
        @foreach ($cats as $key => $label)
          <button type="button" class="rs-uni" id="{{ $ancres[$key] ?? $key }}" data-goto-cat="{{ $key }}">
            <img src="{{ $uniImg[$key] ?? '/assets/img/cuisine-640x480.webp' }}" alt="" loading="lazy" decoding="async" onerror="this.src='/assets/img/cuisine-640x480.webp'">
            <b>{{ $label }}</b>
            <span>{{ $parCat[$key] ?? 0 }} {{ ($parCat[$key] ?? 0) > 1 ? 'adresses' : 'adresse' }}</span>
          </button>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ================= RÉPERTOIRE ================= --}}
  <section class="rs-dir" id="repertoire" aria-labelledby="rs-dir-title">
    <div class="rs-wrap">
      <div class="rs-head">
        <div>
          <p class="rs-eyebrow">Toutes les adresses</p>
          <h2 class="rs-h2" id="rs-dir-title">Le répertoire complet</h2>
          <p class="rs-lead">Cherchez par nom, ville, quartier ou plat. Les adresses testées par l’agence apparaissent en premier.</p>
        </div>
      </div>

      <div class="rs-tools" role="search">
        <label class="rs-search">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="m21 20-4.3-4.3a8 8 0 1 0-1.4 1.4L20 21zM4 10a6 6 0 1 1 12 0 6 6 0 0 1-12 0z"/></svg>
          <input id="rs-q" type="search" placeholder="Un nom, un quartier, un plat…" aria-label="Rechercher un restaurant">
        </label>
        <select id="rs-ville" class="rs-select" aria-label="Filtrer par ville"><option value="">Toutes les villes</option>@foreach ($villes as $v)<option value="{{ $v }}">{{ $v }}</option>@endforeach</select>
        <select id="rs-cat" class="rs-select" aria-label="Filtrer par catégorie"><option value="">Toutes les envies</option>@foreach ($cats as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select>
        <select id="rs-budget" class="rs-select" aria-label="Filtrer par budget"><option value="">Tous budgets</option><option value="petit">Moins de 5 000 FCFA</option><option value="moyen">5 000 – 15 000 FCFA</option><option value="eleve">Plus de 15 000 FCFA</option></select>
        <button id="rs-star" class="rs-toggle" type="button" aria-pressed="false"><span class="rs-star">{!! $svgStar !!}</span> Testés seulement</button>
        <button id="rs-reset" class="rs-reset" type="button">Réinitialiser</button>
      </div>

      <p class="rs-count" aria-live="polite"><b id="rs-n">{{ $restos->count() }}</b> <span id="rs-n-label">{{ $restos->count() > 1 ? 'adresses' : 'adresse' }}</span></p>

      <div id="rs-grid" class="rs-grid" data-ba-dynamique>
        @foreach ($restos as $r)
          @php
            $t = $tel($r);
            $why = $pourquoi($r);
            $blob = mb_strtolower(implode(' ', array_filter([$r->nom, $r->sous_titre, $r->ville, $r->quartier, $r->adresse, $r->specialite, $cats[$r->categorie] ?? '', implode(' ', $why)])));
          @endphp
          <article class="rs-card{{ $r->coup_de_coeur ? ' is-tested' : '' }}" data-ville="{{ $r->ville }}" data-cat="{{ $r->categorie }}" data-star="{{ $r->coup_de_coeur ? '1' : '0' }}" data-budget="{{ $budgetNiveau($r) }}" data-search="{{ $blob }}">
            <div class="rs-card__media">
              {!! $visuel($r) !!}
              @if ($r->coup_de_coeur)<span class="rs-badge"><span class="rs-star">{!! $svgStar !!}</span> Testé par notre agence</span>@endif
            </div>
            <div class="rs-card__body">
              <div class="rs-meta">
                <span class="rs-meta__cat">{{ $cats[$r->categorie] ?? $r->categorie }}</span>
                <span class="rs-loc">{!! $svgPin !!}{{ $r->ville }}@if ($r->quartier) · {{ $r->quartier }}@endif</span>
              </div>
              <h3 class="rs-card__name">@if ($r->coup_de_coeur)<span class="rs-star" title="Testé par notre agence">{!! $svgStar !!}<span class="show-for-sr">Testé par notre agence&nbsp;:</span></span>@endif{{ $r->nom }}</h3>
              @if ($r->sous_titre)<p class="rs-card__sub">{{ $r->sous_titre }}</p>@endif
              @php $hasMore = $r->specialite || $r->adresse || $r->horaires || $r->budget_min || $r->budget_max || count($why) || $r->conseil_dahomey; @endphp
              @if ($hasMore)
                <details class="rs-more">
                  <summary>Infos pratiques</summary>
                  <dl class="rs-dl">
                    @if ($r->specialite)<div><dt>Spécialité</dt><dd>{{ $r->specialite }}</dd></div>@endif
                    @if ($r->budget_min || $r->budget_max)<div><dt>Budget</dt><dd>{{ $r->budget_label }}</dd></div>@endif
                    @if ($r->adresse)<div><dt>Adresse</dt><dd>{{ $r->adresse }}</dd></div>@endif
                    @if ($r->horaires)<div><dt>Horaires</dt><dd>{{ $r->horaires }}</dd></div>@endif
                    @if ($r->telephone)<div><dt>Téléphone</dt><dd>{{ $r->telephone }}</dd></div>@endif
                  </dl>
                  @if (count($why))<ul class="rs-why" style="margin-top:12px">@foreach ($why as $w)<li>{{ $w }}</li>@endforeach</ul>@endif
                  @if ($r->conseil_dahomey)<div class="rs-tip"><span class="rs-tip__ic">{!! $svgInfo !!}</span><p style="margin:0"><b>Le conseil de l’agence</b>{{ $r->conseil_dahomey }}</p></div>@endif
                </details>
              @endif
              <div class="rs-actions">
                @if ($t)<a class="rs-act rs-act--main" href="tel:{{ $t }}">{!! $svgTel !!}Appeler</a>@endif
                <a class="rs-act{{ $t ? '' : ' rs-act--main' }}" href="{{ $itineraire($r) }}" target="_blank" rel="noopener noreferrer">{!! $svgGo !!}Itinéraire</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <p id="rs-empty" class="rs-empty" hidden>Aucune adresse ne correspond à votre recherche. <button type="button" id="rs-empty-reset" class="rs-linkbtn">Tout afficher</button></p>
      <div class="rs-moreall"><button id="rs-more" type="button">Voir plus d’adresses</button></div>
    </div>
  </section>

  {{-- ================= RESTAURATEURS ================= --}}
  <section class="rs-pro" aria-labelledby="rs-pro-title">
    <div class="rs-wrap">
      <div class="rs-pro__card">
        <div>
          <h2 id="rs-pro-title">Vous tenez un restaurant&nbsp;?</h2>
          <p>Proposez votre adresse ou signalez une information à mettre à jour (horaires, téléphone, fermeture). Notre équipe vérifie chaque demande avant publication.</p>
        </div>
        <div class="rs-pro__ctas">
          <a class="rs-btn rs-btn--gold" href="mailto:contact@bonnearrivee.bj?subject=R%C3%A9pertoire%20restaurants%20%E2%80%94%20proposer%20une%20adresse">Proposer mon adresse</a>
          <a class="rs-btn rs-btn--ghost" href="/sejourner/toutes-les-infos-pratiques/nous-contacter">Nous contacter</a>
        </div>
      </div>
    </div>
  </section>

</div>

@verbatim
<script>
/* Où manger — filtres du répertoire (autonome, scopé). */
(function () {
  'use strict';
  var grid = document.getElementById('rs-grid');
  if (!grid) return;
  var cards = Array.prototype.slice.call(grid.querySelectorAll('.rs-card'));
  var q = document.getElementById('rs-q'), fV = document.getElementById('rs-ville'),
      fC = document.getElementById('rs-cat'), fB = document.getElementById('rs-budget'),
      star = document.getElementById('rs-star'), reset = document.getElementById('rs-reset'),
      nEl = document.getElementById('rs-n'), nLbl = document.getElementById('rs-n-label'),
      empty = document.getElementById('rs-empty'), emptyReset = document.getElementById('rs-empty-reset'),
      more = document.getElementById('rs-more');
  var STEP = 12, shown = STEP;
  function norm(s) { return (s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, ''); }
  function apply(resetShown) {
    if (resetShown) shown = STEP;
    var term = norm(q.value.trim()), v = fV.value, c = fC.value, b = fB.value, s = star.getAttribute('aria-pressed') === 'true';
    var ok = cards.filter(function (el) {
      if (v && el.dataset.ville !== v) return false;
      if (c && el.dataset.cat !== c) return false;
      if (b && el.dataset.budget !== b) return false;
      if (s && el.dataset.star !== '1') return false;
      if (term && norm(el.dataset.search).indexOf(term) === -1) return false;
      return true;
    });
    cards.forEach(function (el) { el.classList.add('is-hidden'); });
    ok.slice(0, shown).forEach(function (el) { el.classList.remove('is-hidden'); });
    nEl.textContent = ok.length;
    nLbl.textContent = ok.length > 1 ? 'adresses' : 'adresse';
    empty.hidden = ok.length !== 0;
    more.parentNode.hidden = ok.length <= shown;
  }
  [q, fV, fC, fB].forEach(function (el) {
    el.addEventListener('input', function () { apply(true); });
    el.addEventListener('change', function () { apply(true); });
  });
  star.addEventListener('click', function () {
    star.setAttribute('aria-pressed', star.getAttribute('aria-pressed') === 'true' ? 'false' : 'true');
    apply(true);
  });
  function clearAll() { q.value = ''; fV.value = ''; fC.value = ''; fB.value = ''; star.setAttribute('aria-pressed', 'false'); apply(true); }
  reset.addEventListener('click', clearAll);
  emptyReset.addEventListener('click', clearAll);
  more.addEventListener('click', function () { shown += STEP; apply(false); });
  // Cartes « univers » : filtrent le répertoire puis y descendent.
  Array.prototype.forEach.call(document.querySelectorAll('[data-goto-cat]'), function (btn) {
    btn.addEventListener('click', function () {
      clearAll(); fC.value = btn.getAttribute('data-goto-cat'); apply(true);
      document.getElementById('repertoire').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
  apply(true);
  // Liens du menu (/sejourner/restaurants#maquis…) : ouvrent le répertoire filtré.
  var ancres = { 'gastronomique': 'gastronomique', 'maquis': 'maquis', 'cafes-bars': 'cafe_bar', 'fast-food': 'fast_food', 'international': 'international' };
  function depuisAncre() {
    var h = (location.hash || '').slice(1);
    if (!ancres[h]) return;
    clearAll(); fC.value = ancres[h]; apply(true);
    setTimeout(function () { document.getElementById('repertoire').scrollIntoView({ block: 'start' }); }, 60);
  }
  window.addEventListener('hashchange', depuisAncre);
  depuisAncre();
})();
</script>
@endverbatim
</div>
@verbatim<script>
/* Où manger — carrousel des campagnes partenaires (autonome). */
(function () {
  var ads = document.querySelector('.rs-ads'); if (!ads) return;
  var track = ads.querySelector('.rs-ads__track');
  var slides = track ? Array.prototype.slice.call(track.children) : [];
  var dotsWrap = ads.querySelector('.rs-ads__dots');
  if (slides.length < 2 || !dotsWrap) return;
  var i = 0, timer = null, paused = false;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  slides.forEach(function (_, n) {
    var d = document.createElement('button'); d.className = 'rs-ads__dot'; d.type = 'button';
    d.setAttribute('role', 'tab'); d.setAttribute('aria-label', 'Annonce ' + (n + 1));
    d.addEventListener('click', function () { go(n); restart(); }); dotsWrap.appendChild(d);
  });
  var dots = Array.prototype.slice.call(dotsWrap.children);
  function go(n) { i = (n + slides.length) % slides.length; track.style.transform = 'translateX(' + (-i * 100) + '%)'; dots.forEach(function (d, k) { d.setAttribute('aria-selected', k === i ? 'true' : 'false'); }); }
  function start() { if (reduce || paused) return; timer = setInterval(function () { go(i + 1); }, 6000); }
  function stop() { if (timer) { clearInterval(timer); timer = null; } }
  function restart() { stop(); start(); }
  var p = ads.querySelector('.rs-ads__nav--prev'), q = ads.querySelector('.rs-ads__nav--next');
  if (p) p.addEventListener('click', function () { go(i - 1); restart(); });
  if (q) q.addEventListener('click', function () { go(i + 1); restart(); });
  ['mouseenter', 'focusin'].forEach(function (e) { ads.addEventListener(e, function () { paused = true; stop(); }); });
  ['mouseleave', 'focusout'].forEach(function (e) { ads.addEventListener(e, function () { paused = false; start(); }); });
  document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });
  go(0); start();
})();
</script>@endverbatim
@endsection
