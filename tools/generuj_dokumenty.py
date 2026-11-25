#!/usr/bin/env python3
"""Generate the six demo PDFs and equivalent HTML documents from one source.

Install tools/requirements-dokumenty.txt, then run this file from any directory.
WeasyPrint requires Pango and a sans-serif font with Polish glyphs (e.g. DejaVu Sans).
PDF/UA output is requested; this is not a declaration of independently certified compliance.
"""
from html import escape
from pathlib import Path
from weasyprint import HTML

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'wp/theme/isvero-samorzad/assets/docs'
DOCS = [
    ('uchwala-budzetowa-2027-demo', 'Uchwała budżetowa Gminy Przykładowo na rok 2027'),
    ('zarzadzenie-wojta-12-2026-demo', 'Zarządzenie Wójta Gminy Przykładowo nr 12/2026'),
    ('wniosek-dowod-osobisty-demo', 'Wzór wniosku – przykładowy formularz'),
    ('statut-szkoly-demo', 'Statut Szkoły Podstawowej nr 1 w Przykładowie'),
    ('rekrutacja-2027-demo', 'Zasady rekrutacji do klas pierwszych 2027/2028'),
    ('regulamin-biblioteki-demo', 'Regulamin Gminnej Biblioteki Publicznej w Przykładowie'),
]
NOTICE = ('Ten plik jest przykładem dokumentu publikowanego w serwisie demonstracyjnym '
          'przygotowanym przez Isvero Studio. Nie jest dokumentem żadnej instytucji publicznej. '
          '„Gmina Przykładowo” oraz wszystkie nazwy, osoby i dane są fikcyjne.')
CSS = '''
@page { size: A4; margin: 22mm; }
* { box-sizing: border-box; }
html { color: #182536; background: #fff; font-family: Arial, sans-serif; }
body { max-width: 48rem; margin: 0 auto; padding: 1.25rem; font-size: 1rem; line-height: 1.6; overflow-wrap: anywhere; }
h1 { font-size: 1.8rem; line-height: 1.25; color: #083a66; }
h2 { font-size: 1.2rem; line-height: 1.4; margin-top: 1.6rem; }
p { margin: .8rem 0; }
.demo { font-weight: bold; color: #083a66; border-bottom: 2px solid #083a66; padding-bottom: .6rem; }
a { color: #083a66; text-underline-offset: .18em; }
a:focus-visible { outline: 3px solid #083a66; outline-offset: 4px; }
nav { margin-bottom: 1.5rem; }
footer { margin-top: 2rem; border-top: 1px solid #637181; padding-top: .8rem; }
@media print {
 body { max-width: none; padding: 0; font-size: 11pt; line-height: 1.55; }
 h1 { font-size: 22pt; } h2 { font-size: 14pt; break-after: avoid; }
 nav, .screen-only { display: none; }
}
'''


def document(slug, title):
    return f'''<!doctype html>
<html lang="pl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="author" content="Isvero Studio – dokument demonstracyjny">
<title>{escape(title)} (DEMO)</title>
<style>{CSS}</style>
</head>
<body>
<nav aria-label="Nawigacja dokumentu"><a href="/mapa-strony/">Wróć do serwisu demonstracyjnego</a></nav>
<main>
<p class="demo">DOKUMENT DEMONSTRACYJNY</p>
<h1>{escape(title)}</h1>
<p>{escape(NOTICE)}</p>
<h2>§ 1. Treść przykładowa</h2>
<p>Dokument pokazuje sposób publikowania załączników. Nie wywołuje skutków prawnych,
nie ustanawia zasad działania instytucji i nie służy do składania rzeczywistych wniosków.</p>
<h2>§ 2. Sposób odczytu</h2>
<p>Treść jest dostępna w dwóch równoważnych wersjach: jako dokument PDF oraz strona HTML.
PDF zawiera znaczniki struktury nagłówków i akapitów, tytuł oraz oznaczenie języka polskiego.
Wersję HTML można powiększać i czytać bez pobierania pliku.</p>
<p class="screen-only"><a href="{escape(slug)}.pdf">Pobierz tę samą treść jako PDF</a></p>
</main>
<footer><p>Isvero Studio · portfolio · wyłącznie dane fikcyjne</p></footer>
</body>
</html>
'''


if __name__ == '__main__':
    OUT.mkdir(parents=True, exist_ok=True)
    for slug, title in DOCS:
        source = OUT / f'{slug}.html'
        source.write_text(document(slug, title), encoding='utf-8')
        HTML(filename=str(source)).write_pdf(OUT / f'{slug}.pdf', pdf_variant='pdf/ua-1')
        print(f'OK {slug}: HTML + otagowany PDF')
