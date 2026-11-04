// Contrôle navigateur avant / après chaque mise en ligne (audit, section J).
//
// Pour chaque page représentative, à 360, 768 et 1366 px :
//   - aucune erreur JavaScript ;
//   - pas de défilement horizontal ;
//   - aucune balise Blade non compilée (<x-…) dans la page ;
//   - aucune image du site en erreur ;
//   - le bouton « Menu » ouvre bien le menu.
//
// Installation, une fois :   npm i -D playwright && npx playwright install chromium
// Lancer en local :          node tools/smoke-navigateur.mjs http://127.0.0.1:8000
// Lancer sur le site :       node tools/smoke-navigateur.mjs https://bonnearrivee.bj
//
// Code de sortie 0 = tout est bon, 1 = au moins un défaut (liste affichée).

import { chromium } from 'playwright';

const base = (process.argv[2] || 'http://127.0.0.1:8000').replace(/\/$/, '');
const pages = [
    '/', '/explorez', '/explorez/terre-de-traditions', '/explorez/terre-de-traditions/le-vodun',
    '/explorez/naturellement-sauvage/les-chutes-de-tanougou', '/explorez/les-cinq-benin/porto-novo',
    '/explorez/la-vie-semerveille-au-benin', '/activites', '/activites/sejour-sur-le-lac', '/sejourner',
    '/sejourner/hebergements/hotels', '/sejourner/toutes-les-infos-pratiques/comment-venir',
    '/sejourner/restaurants', '/benin-en-fete', '/benin-en-fete/vodun-days', '/quand-partir',
    '/a-propos', '/carte-du-benin', '/checklist', '/recherche?q=vodun', '/mentions-legales',
];
const widths = [[360, 780], [768, 1024], [1366, 768]];

const browser = await chromium.launch();
const problems = [];

for (const [width, height] of widths) {
    const context = await browser.newContext({ viewport: { width, height } });
    const page = await context.newPage();
    let errors = [];
    const brokenImages = new Set();
    page.on('pageerror', (e) => errors.push(e.message));
    page.on('response', (r) => {
        if (r.request().resourceType() === 'image' && r.url().startsWith(base) && r.status() >= 400) {
            brokenImages.add(`${r.status()} ${r.url().replace(base, '')}`);
        }
    });

    for (const path of pages) {
        errors = [];
        brokenImages.clear();
        const where = `${path} @${width}px`;
        try {
            const response = await page.goto(base + path, { waitUntil: 'load', timeout: 45000 });
            if (!response || response.status() !== 200) {
                problems.push(`${where} : HTTP ${response ? response.status() : '—'}`);
                continue;
            }
            await page.waitForTimeout(800);

            const report = await page.evaluate(() => ({
                overflow: document.documentElement.scrollWidth - window.innerWidth,
                blade: document.documentElement.outerHTML.replace(/<script[\s\S]*?<\/script>/gi, '').includes('<x-'),
            }));
            if (report.overflow > 1) problems.push(`${where} : défilement horizontal de ${report.overflow} px`);
            if (report.blade) problems.push(`${where} : balise Blade <x-…> visible`);
            for (const e of errors) problems.push(`${where} : erreur JS « ${e} »`);
            for (const img of brokenImages) problems.push(`${where} : image ${img}`);

            if (path === '/') {
                const button = page.locator('.woody-component-headnavs button.menu-icon').first();
                await button.click();
                await page.waitForTimeout(700);
                const open = await page.evaluate(() => document.body.classList.contains('menu-is-open'));
                if (!open) problems.push(`${where} : le bouton « Menu » n'ouvre pas le menu`);
                await button.click();
                await page.waitForTimeout(400);
            }
        } catch (e) {
            problems.push(`${where} : ${e.message.split('\n')[0]}`);
        }
    }
    await context.close();
}

await browser.close();

if (problems.length) {
    console.log(`✗ ${problems.length} défaut(s) :`);
    for (const p of problems) console.log('  - ' + p);
    process.exit(1);
}
console.log(`✓ ${pages.length} pages × ${widths.length} largeurs : aucun défaut.`);
