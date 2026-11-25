const { chromium } = require('playwright');
const fs = require('fs');
const axeSrc = fs.readFileSync(require.resolve('axe-core/axe.min.js'), 'utf8');
const base = process.argv[2] || 'http://127.0.0.1:8080';
const pages = ['/', '/gmina/odpady/', '/gmina/podatki-i-oplaty/', '/bip/', '/bip/zalatw-sprawe/', '/bip/rejestr-zmian/', '/bip/uchwaly-i-zarzadzenia/', '/szkola/', '/szkola/plan-lekcji/', '/biblioteka/', '/biblioteka/godziny-otwarcia/', '/biblioteka/katalog/?k=test', '/aktualnosci/', '/kontakt/', '/deklaracja-dostepnosci/', '/mapa-strony/', '/?s=gmina', '/nieistnieje/'];
(async () => {
  const b = await chromium.launch();
  let total = 0;
  for (const [label, opts] of [['desktop', { viewport: { width: 1280, height: 900 } }], ['mobile', { viewport: { width: 360, height: 740 }, isMobile: true, hasTouch: true }]]) {
    for (const hc of [false, true]) {
      const ctx = await b.newContext(opts);
      const pg = await ctx.newPage();
      for (const p of pages) {
        await pg.goto(base + p);
        if (hc) await pg.evaluate(() => document.documentElement.classList.add('hc'));
        await pg.addScriptTag({ content: axeSrc });
        const r = await pg.evaluate(async () => await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'best-practice'] }));
        const overflow = await pg.evaluate(() => document.documentElement.scrollWidth > window.innerWidth + 1);
        if (r.violations.length || overflow) {
          console.log(label, hc ? 'HC' : '', p, overflow ? 'H-SCROLL' : '');
          r.violations.forEach(v => { total++; console.log('  ', v.id, v.impact, v.nodes.length, v.nodes[0].target.join(' '), (v.nodes[0].failureSummary||'').slice(0,160).replace(/\n/g,' ')); });
        }
      }
      await ctx.close();
    }
  }
  console.log('TOTAL violations:', total);
  await b.close();
})();
