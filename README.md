# DEMO: serwis gminy, BIP, szkoły i biblioteki na WordPressie

> **To jest projekt pokazowy (portfolio) Isvero Studio, przygotowany w listopadzie 2026 r.**
> Nie jest to strona żadnej instytucji publicznej ani realizacja dla klienta. „Gmina Przykładowo”, osoby, dokumenty i dane są fikcyjne.

**Demo na żywo:** https://demo-samorzad.148.113.245.121.sslip.io (noindex – nieindeksowane do czasu akceptacji)

## Co pokazuje
- **Serwis gminy**: strona główna z kafelkami „Załatw sprawę”, aktualności i ogłoszenia, podatki i opłaty, harmonogram odbioru odpadów, kalendarz wydarzeń, kontakt z formularzem (w demo nic nie wysyła).
- **BIP**: menu przedmiotowe, metryczka każdej informacji (kto wytworzył, kto wprowadził, daty, liczba wersji), rejestr zmian, dokumenty PDF z opisem formatu i rozmiaru, instrukcja korzystania, redakcja.
- **Szkoła**: aktualności, plan lekcji (dostępna tabela), kadra, rekrutacja, dokumenty.
- **Biblioteka**: wyszukiwarka katalogu (atrapa integracji z MAK+/Koha), nowości, godziny otwarcia, wydarzenia, regulamin.
- **Deklaracja dostępności** wg wzoru do ustawy z 4.04.2019 r., polityka prywatności, mapa strony, wyszukiwarka, strona 404.

## Dostępność (WCAG 2.1 AA)
Link „Przejdź do treści”, widoczny fokus, pełna obsługa klawiaturą (menu mobilne z `aria-expanded`, Escape zamyka), landmarki i hierarchia nagłówków, tabele z `caption` i `scope`, przewijane tabele dostępne z klawiatury, kontrast ≥ 4,5:1, tryb wysokiego kontrastu, 3 rozmiary tekstu, etykiety formularzy, `prefers-reduced-motion`, brak przewijania w poziomie przy 320 px.

Wyniki testów (25.11.2026, lokalnie):
- axe-core 4.10 (reguły WCAG 2.0/2.1 A+AA + best practices): **0 naruszeń** na 18 podstronach × desktop/mobile × zwykły/wysoki kontrast,
- Lighthouse 12 (mobile): wydajność 100, dostępność 100, dobre praktyki 100 (SEO obniżone celowo przez noindex).

Testy automatyczne nie zastępują pełnego audytu z udziałem użytkowników – zob. repo `demo-wcag-audyt`.

## Technologia
WordPress (oficjalny obraz `wordpress:php8.3-apache`), PHP 8.3, MariaDB 11.4, lekki autorski motyw klasyczny (bez page builderów i jQuery na froncie), mu-plugin z zabezpieczeniami (noindex, brak XML-RPC, brak enumeracji użytkowników, brak wysyłki e-maili, nagłówki bezpieczeństwa), WP-CLI do jednorazowego załadowania treści demo, Docker Compose pod Coolify, codzienna kopia bazy i `wp-content` (14 dni).

```
docker-compose.yml            # wordpress + db + backup
wp/Dockerfile           # obraz WordPress z motywem, mu-pluginem i WP-CLI
wp/theme/isvero-samorzad
wp/mu-plugins/isvero-demo-guard.php
wp/init/seed.php        # treści demo (fikcyjne)
deploy/                 # wdrożenie przez API Coolify (GitHub Actions)
```

## Uruchomienie lokalne
```bash
DB_PASSWORD=$(openssl rand -hex 16) DB_ROOT_PASSWORD=$(openssl rand -hex 16) \
WP_ADMIN_PASSWORD=$(openssl rand -hex 16) WP_SITE_URL=http://localhost:8080 \
docker compose -f docker-compose.yml -f docker-compose.local.yml up --build
```

## Kopia zapasowa i odtworzenie
Usługa `backup` raz na dobę zapisuje `db-RRRRMMDD-GGMM.sql.gz` i `wp-content-….tar.gz` w wolumenie `backups` (rotacja 14 dni). Odtworzenie:
```bash
gunzip -c db-….sql.gz | docker compose exec -T db mariadb -u wordpress -p"$DB_PASSWORD" wordpress
docker compose cp wp-content-….tar.gz wordpress:/tmp/ && docker compose exec wordpress tar xzf /tmp/wp-content-….tar.gz -C /var/www/html
```
Kopia poza serwerem (np. OVH Object Storage przez backup S3 w Coolify) – do włączenia w panelu.

## Licencja
Kod motywu: GPL-2.0-or-later (jak WordPress). Treści demo: fikcyjne, do swobodnego użytku.
