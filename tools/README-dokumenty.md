# Dokumenty demonstracyjne: HTML i PDF

Źródłem sześciu dokumentów jest `generuj_dokumenty.py`. Każdy ma równoważną wersję HTML i PDF. Nie są to obowiązujące uchwały, formularze ani regulaminy.

## Ponowne generowanie

Wymagane: Python 3.10+, Pango i font obsługujący polskie litery (np. Arial lub DejaVu Sans).

```sh
python3 -m venv /tmp/isvero-pdf
/tmp/isvero-pdf/bin/pip install -r tools/requirements-dokumenty.txt
/tmp/isvero-pdf/bin/python tools/generuj_dokumenty.py
```

Na macOS z bibliotekami Homebrew, gdy Pango nie jest wykrywane, ustaw dla polecenia generatora `DYLD_FALLBACK_LIBRARY_PATH=/opt/homebrew/lib`.

WeasyPrint generuje wariant `pdf/ua-1`, język `pl`, metadane tytułu i autora oraz tagi nagłówków i akapitów. Nie oznacza to niezależnej certyfikacji zgodności PDF/UA. Po zmianie treści należy sprawdzić strukturę, kolejność odczytu i renderowanie dokumentów; w przypadku publikacji rzeczywistych dokumentów wykonać pełną weryfikację dostępności.

## Kontrola regresji

```sh
/tmp/isvero-pdf/bin/pip install pypdf==7.1.1
/tmp/isvero-pdf/bin/python tests/dokumenty.py
```

Test sprawdza wszystkie sześć plików: oznaczenie języka, tytuł, `/MarkInfo`, drzewo tagów z H1/H2/P, liczbę stron i kompletność tekstu względem głównej treści HTML. Nie zastępuje walidatora PDF/UA ani testu z czytnikiem ekranu.

Dokumentacja generatora: https://doc.courtbouillon.org/weasyprint/stable/common_use_cases.html#pdf-ua-universal-accessibility
