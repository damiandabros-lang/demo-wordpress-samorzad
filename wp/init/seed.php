<?php
/**
 * Treści demonstracyjne. Uruchamiane raz: wp eval-file seed.php
 * Wszystkie nazwy, osoby i dane są fikcyjne.
 */
if (get_option('isv_seeded')) { echo "Treści już istnieją – pomijam.\n"; return; }

function isv_page($title, $slug, $content, $parent = 0, $order = 0, $nav = false, $meta = []) {
    $id = wp_insert_post(['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $slug,
        'post_content' => $content, 'post_parent' => $parent, 'menu_order' => $order, 'comment_status' => 'closed', 'ping_status' => 'closed']);
    if ($nav) update_post_meta($id, '_isv_in_nav', '1');
    foreach ($meta as $k => $v) update_post_meta($id, $k, $v);
    return $id;
}
$demo = '<p class="notice"><strong>Treść demonstracyjna.</strong> Dane na tej stronie są fikcyjne i służą wyłącznie pokazaniu układu serwisu.</p>';

/* ---------- GMINA ---------- */
$gmina = isv_page('Gmina', 'gmina', '<p>Serwis Gminy Przykładowo to projekt pokazowy Isvero Studio. Pokazuje, jak może wyglądać nowoczesna, szybka i dostępna strona samorządu zgodna z WCAG 2.1 na poziomie AA: z czytelną nawigacją, obsługą klawiaturą, trybem wysokiego kontrastu, powiększaniem tekstu, Biuletynem Informacji Publicznej oraz podserwisami szkoły i biblioteki.</p><p>Serwis działa na WordPressie z lekkim, autorskim motywem, bez kreatorów stron i zbędnych skryptów.</p>', 0, 1, true);
isv_page('O gminie', 'o-gminie', $demo . '<p>Gmina Przykładowo to fikcyjna gmina wiejska licząca około 8 500 mieszkańców, położona w dolinie fikcyjnej rzeki Przykładówki. W skład gminy wchodzi 14 sołectw.</p><h2>Władze gminy</h2><ul><li>Wójt Gminy – Jan Przykładowy (osoba fikcyjna)</li><li>Przewodnicząca Rady Gminy – Anna Wzorcowa (osoba fikcyjna)</li></ul><h2>Dane podstawowe</h2><div class="table-wrap"><table><caption>Gmina w liczbach (dane fikcyjne)</caption><thead><tr><th scope="col">Wskaźnik</th><th scope="col">Wartość</th></tr></thead><tbody><tr><th scope="row">Powierzchnia</th><td>112 km²</td></tr><tr><th scope="row">Liczba mieszkańców</th><td>8 512</td></tr><tr><th scope="row">Liczba sołectw</th><td>14</td></tr></tbody></table></div>', $gmina, 1);
isv_page('Podatki i opłaty', 'podatki-i-oplaty', $demo . '<h2>Terminy płatności</h2><ul><li>Podatek od nieruchomości, rolny i leśny – raty do 15 marca, 15 maja, 15 września i 15 listopada.</li><li>Opłata za gospodarowanie odpadami – do 15. dnia każdego miesiąca.</li></ul><h2>Rachunek bankowy</h2><p>Numer rachunku do wpłat: <strong>00 0000 0000 0000 0000 0000 0000</strong> (numer fikcyjny).</p><h2>Stawki</h2><div class="table-wrap"><table><caption>Stawki podatku od nieruchomości na 2027 r. (dane fikcyjne)</caption><thead><tr><th scope="col">Przedmiot opodatkowania</th><th scope="col">Stawka</th></tr></thead><tbody><tr><th scope="row">Budynki mieszkalne</th><td>0,95 zł/m²</td></tr><tr><th scope="row">Budynki związane z działalnością gospodarczą</th><td>28,00 zł/m²</td></tr><tr><th scope="row">Grunty pozostałe</th><td>0,55 zł/m²</td></tr></tbody></table></div>', $gmina, 2);
isv_page('Odpady komunalne', 'odpady', $demo . '<p>Harmonogram odbioru odpadów dla sołectw Gminy Przykładowo w grudniu 2026 r.</p><div class="table-wrap"><table><caption>Harmonogram odbioru odpadów – grudzień 2026 (dane fikcyjne)</caption><thead><tr><th scope="col">Rejon</th><th scope="col">Zmieszane</th><th scope="col">Segregowane</th><th scope="col">Bio</th></tr></thead><tbody><tr><th scope="row">Rejon I (Przykładowo, Wzorcowo)</th><td>3 i 17 grudnia</td><td>10 grudnia</td><td>3 grudnia</td></tr><tr><th scope="row">Rejon II (Testowice, Próbna Wola)</th><td>4 i 18 grudnia</td><td>11 grudnia</td><td>4 grudnia</td></tr><tr><th scope="row">Rejon III (pozostałe sołectwa)</th><td>5 i 19 grudnia</td><td>12 grudnia</td><td>5 grudnia</td></tr></tbody></table></div><h2>PSZOK</h2><p>Punkt Selektywnej Zbiórki Odpadów Komunalnych, ul. Przykładowa 20 – czynny we wtorki i soboty, 8:00–15:00.</p>', $gmina, 3);
isv_page('Kalendarz wydarzeń', 'kalendarz-wydarzen', $demo . '<ul class="post-list"><li><h2>Jarmark Bożonarodzeniowy</h2><p class="meta"><time datetime="2026-12-13T12:00">13 grudnia 2026, 12:00</time> · Rynek w Przykładowie</p><p>Stoiska lokalnych twórców, kolędy i warsztaty dla dzieci.</p></li><li><h2>Sesja Rady Gminy</h2><p class="meta"><time datetime="2026-12-18T10:00">18 grudnia 2026, 10:00</time> · Sala obrad Urzędu Gminy</p><p>Uchwalenie budżetu na 2027 r. Transmisja online z napisami.</p></li><li><h2>Noworoczny bieg przełajowy</h2><p class="meta"><time datetime="2027-01-06T11:00">6 stycznia 2027, 11:00</time> · Stadion gminny</p><p>Zapisy w Gminnym Ośrodku Kultury.</p></li></ul>', $gmina, 4);

/* ---------- BIP ---------- */
$bipc = '<p>Biuletyn Informacji Publicznej Gminy Przykładowo (wersja demonstracyjna). Układ zgodny z rozporządzeniem w sprawie BIP: menu przedmiotowe, metryczka każdej informacji, rejestr zmian, wyszukiwarka i instrukcja korzystania.</p><p class="notice"><strong>To nie jest prawdziwy BIP.</strong> Prawdziwy Biuletyn jednostki jest dostępny przez stronę główną BIP (bip.gov.pl). Wszystkie informacje tutaj są fikcyjne.</p><h2>Ostatnio opublikowane dokumenty</h2>[dokumenty zestaw="bip"]';
$bip = isv_page('BIP', 'bip', $bipc, 0, 2, true, ['_isv_wytworzyl' => 'Sekretarz Gminy (osoba fikcyjna)']);
isv_page('Załatw sprawę', 'zalatw-sprawe', $demo . '<p>Karty usług opisują, jak załatwić sprawę w urzędzie: wymagane dokumenty, opłaty, terminy i sposób odwołania.</p><h2>Karty usług</h2><ul class="grid" role="list"><li class="card"><h3>Wymiana dowodu osobistego</h3><p>Wniosek online przez gov.pl lub w urzędzie. Bez opłat. Termin: do 30 dni.</p></li><li class="card"><h3>Deklaracja o wysokości opłaty za odpady</h3><p>Złożenie w 14 dni od zamieszkania. Formularz w dziale Dokumenty.</p></li><li class="card"><h3>Zaświadczenie o przeznaczeniu działki</h3><p>Opłata skarbowa 50 zł. Termin: do 7 dni.</p></li></ul>[dokumenty zestaw="bip"]', $bip, 1);
isv_page('Organy gminy', 'organy-gminy', $demo . '<h2>Wójt Gminy</h2><p>Jan Przykładowy (osoba fikcyjna). Przyjęcia interesantów: poniedziałki 12:00–16:00.</p><h2>Rada Gminy</h2><p>Rada Gminy liczy 15 radnych. Sesje odbywają się zgodnie z planem pracy rady.</p>', $bip, 2);
isv_page('Budżet i finanse', 'budzet-i-finanse', $demo . '<p>Uchwały budżetowe, sprawozdania z wykonania budżetu i wieloletnia prognoza finansowa.</p>[dokumenty zestaw="bip"]', $bip, 3);
isv_page('Uchwały i zarządzenia', 'uchwaly-i-zarzadzenia', $demo . '[dokumenty zestaw="bip"]', $bip, 4, false, ['_isv_wytworzyl' => 'Biuro Rady Gminy (fikcyjne)']);
isv_page('Zamówienia publiczne', 'zamowienia-publiczne', $demo . '<p>Plan postępowań o udzielenie zamówień na 2027 r. oraz ogłoszenia o zamówieniach poniżej 130 000 zł.</p><div class="table-wrap"><table><caption>Plan zamówień publicznych 2027 (dane fikcyjne)</caption><thead><tr><th scope="col">Przedmiot</th><th scope="col">Tryb</th><th scope="col">Kwartał</th></tr></thead><tbody><tr><td>Przebudowa drogi gminnej nr 000001</td><td>podstawowy</td><td>II</td></tr><tr><td>Dostawa oleju opałowego</td><td>podstawowy</td><td>III</td></tr></tbody></table></div>', $bip, 5);
isv_page('Rejestr zmian', 'rejestr-zmian', '<p>Rejestr zmian obejmuje wszystkie publikacje i aktualizacje stron Biuletynu.</p>[rejestr_zmian]', $bip, 6);
isv_page('Instrukcja korzystania z BIP', 'instrukcja', '<p>Menu przedmiotowe znajduje się po lewej stronie (na telefonie – nad treścią). Każda informacja ma metryczkę z danymi o autorze, dacie wytworzenia i publikacji. Wyszukiwarka jest dostępna w nagłówku. Serwis obsłużysz w całości klawiaturą: klawisz Tab przenosi między elementami, a pierwszy link „Przejdź do treści” pomija menu.</p>', $bip, 7);
isv_page('Redakcja BIP', 'redakcja', $demo . '<p>Redaktor BIP: Referat Organizacyjny Urzędu Gminy Przykładowo (fikcyjny), e-mail: <a href="mailto:bip@example.org">bip@example.org</a>.</p>', $bip, 8);

/* ---------- SZKOŁA ---------- */
$szk = isv_page('Szkoła', 'szkola', '<p>Podserwis Szkoły Podstawowej nr 1 im. Fikcyjnego Patrona w Przykładowie (szkoła fikcyjna). Pokazuje układ strony szkolnej: aktualności, plan lekcji, kadrę, rekrutację i dokumenty.</p><h2>Aktualności szkoły</h2>[aktualnosci n="3" kategoria="szkola"]', 0, 3, true);
isv_page('O szkole', 'o-szkole', $demo . '<p>Szkoła liczy 312 uczniów w 16 oddziałach. Dysponuje pracownią informatyczną, biblioteką szkolną i boiskiem wielofunkcyjnym.</p>', $szk, 1);
$plan = '<div class="table-wrap"><table><caption>Plan lekcji klasy 4a (dane fikcyjne)</caption><thead><tr><th scope="col">Godzina</th><th scope="col">Poniedziałek</th><th scope="col">Wtorek</th><th scope="col">Środa</th><th scope="col">Czwartek</th><th scope="col">Piątek</th></tr></thead><tbody>'
 . '<tr><th scope="row">8:00–8:45</th><td>Język polski</td><td>Matematyka</td><td>Przyroda</td><td>Język angielski</td><td>Matematyka</td></tr>'
 . '<tr><th scope="row">8:55–9:40</th><td>Matematyka</td><td>Język polski</td><td>Historia</td><td>Język polski</td><td>Informatyka</td></tr>'
 . '<tr><th scope="row">9:50–10:35</th><td>Język angielski</td><td>Przyroda</td><td>Matematyka</td><td>Wychowanie fizyczne</td><td>Język polski</td></tr>'
 . '<tr><th scope="row">10:55–11:40</th><td>Wychowanie fizyczne</td><td>Plastyka</td><td>Język polski</td><td>Muzyka</td><td>Godzina wychowawcza</td></tr></tbody></table></div>';
isv_page('Plan lekcji', 'plan-lekcji', $demo . $plan, $szk, 2);
isv_page('Kadra', 'kadra', $demo . '<ul><li>Dyrektor – Maria Fikcyjna</li><li>Wicedyrektor – Piotr Testowy</li><li>Pedagog szkolny – Ewa Wzorcowa</li></ul><p>Wszystkie osoby są fikcyjne.</p>', $szk, 3);
isv_page('Rekrutacja', 'rekrutacja', $demo . '<p>Rekrutacja do klas pierwszych na rok szkolny 2027/2028 trwa od 1 do 31 marca 2027 r. (termin fikcyjny).</p>[dokumenty zestaw="szkola"]', $szk, 4);
isv_page('Dokumenty szkoły', 'dokumenty-szkoly', '[dokumenty zestaw="szkola"]', $szk, 5);

/* ---------- BIBLIOTEKA ---------- */
$bib = isv_page('Biblioteka', 'biblioteka', '<p>Podserwis Gminnej Biblioteki Publicznej w Przykładowie (biblioteka fikcyjna): katalog, nowości, godziny otwarcia i wydarzenia.</p><h2>Szukaj w katalogu</h2>[katalog]<h2>Aktualności biblioteki</h2>[aktualnosci n="3" kategoria="biblioteka"]', 0, 4, true);
isv_page('Godziny otwarcia', 'godziny-otwarcia', '<div class="table-wrap"><table><caption>Godziny otwarcia biblioteki (dane fikcyjne)</caption><thead><tr><th scope="col">Dzień</th><th scope="col">Wypożyczalnia</th><th scope="col">Czytelnia</th></tr></thead><tbody><tr><th scope="row">Poniedziałek</th><td>10:00–18:00</td><td>10:00–16:00</td></tr><tr><th scope="row">Wtorek–czwartek</th><td>9:00–17:00</td><td>9:00–15:00</td></tr><tr><th scope="row">Piątek</th><td>9:00–15:00</td><td>nieczynna</td></tr><tr><th scope="row">Sobota</th><td>9:00–13:00</td><td>nieczynna</td></tr></tbody></table></div>', $bib, 1);
isv_page('Nowości', 'nowosci', $demo . '<h2>Nowości w zbiorach</h2><ul class="grid" role="list"><li class="card"><h3>„Opowieści znad Przykładówki”</h3><p class="meta">Autor fikcyjny · powieść</p><p>Saga rodzinna z regionu.</p></li><li class="card"><h3>„Ogród na każdą porę roku”</h3><p class="meta">Autor fikcyjny · poradnik</p><p>Praktyczny poradnik ogrodniczy.</p></li><li class="card"><h3>„Kot, który liczył gwiazdy”</h3><p class="meta">Autor fikcyjny · dla dzieci</p><p>Książka z dużą czcionką.</p></li></ul>', $bib, 2);
isv_page('Wydarzenia', 'wydarzenia', $demo . '<ul><li><time datetime="2026-12-05">5 grudnia</time> – spotkanie autorskie (sala czytelni, 17:00)</li><li><time datetime="2026-12-12">12 grudnia</time> – warsztaty kartek świątecznych dla dzieci</li><li><time datetime="2027-01-16">16 stycznia</time> – Dyskusyjny Klub Książki</li></ul>', $bib, 3);
isv_page('Katalog online', 'katalog', '[katalog]', $bib, 4);
isv_page('Regulamin', 'regulamin', '[dokumenty zestaw="biblioteka"]', $bib, 5);

/* ---------- KONTAKT i strony stałe ---------- */
isv_page('Kontakt', 'kontakt', '<h2>Urząd Gminy Przykładowo (fikcyjny)</h2><p>ul. Przykładowa 1, 00-000 Przykładowo<br>Tel.: 00 000 00 00 · E-mail: <a href="mailto:urzad@example.org">urzad@example.org</a><br>Elektroniczna skrzynka podawcza i adres do e-Doręczeń – w prawdziwym wdrożeniu tutaj.</p><p><a href="https://www.openstreetmap.org/" rel="noopener">Zobacz lokalizację na mapie OpenStreetMap</a> (w demo – link ogólny, bez osadzonych skryptów śledzących).</p><h2>Napisz do nas</h2>[formularz_kontaktowy]', 0, 6, true);
$dekl = <<<HTML
<p class="notice"><strong>Wersja demonstracyjna.</strong> Poniższa deklaracja jest przykładem przygotowanym według wzoru do ustawy z dnia 4 kwietnia 2019 r. o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych. Dotyczy fikcyjnej Gminy Przykładowo.</p>
<p id="a11y-wstep"><span id="a11y-podmiot">Gmina Przykładowo</span> zapewnia dostępność swojej strony internetowej zgodnie z przepisami ustawy z dnia 4 kwietnia 2019 r. o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych. Deklaracja dostępności dotyczy strony <a id="a11y-url" href="/">demo-samorzad.isverostudio.com</a>.</p>
<ul><li>Data publikacji strony internetowej: <span id="a11y-data-publikacja">2026-11-25</span>.</li><li>Data ostatniej istotnej aktualizacji: <span id="a11y-data-aktualizacja">2026-11-25</span>.</li></ul>
<h2 id="a11y-status">Stan dostępności cyfrowej</h2>
<p>Strona internetowa jest częściowo zgodna z ustawą o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych (WCAG 2.1, poziom AA) z powodu niezgodności wymienionych poniżej.</p><h3>Treści niedostępne</h3><ul><li>Przykładowe dokumenty PDF w BIP są krótkimi plikami pokazowymi i nie przeszły pełnej weryfikacji dostępności (PDF/UA).</li></ul>
<h2>Przygotowanie deklaracji dostępności</h2>
<ul><li>Deklarację sporządzono dnia: <span id="a11y-data-sporzadzenie">2026-11-25</span>.</li><li>Deklarację sporządzono na podstawie samooceny przeprowadzonej przez wykonawcę (testy automatyczne axe-core i Lighthouse oraz testy ręczne: klawiatura, czytnik ekranu, powiększenie 200%, tryb wysokiego kontrastu).</li></ul>
<h2>Skróty klawiaturowe</h2>
<p>Na stronie nie stosujemy własnych skrótów klawiaturowych. Pierwszy element strony to link „Przejdź do treści”. Menu na telefonie zamyka klawisz Escape.</p>
<h2>Ułatwienia</h2>
<ul><li>przełącznik wysokiego kontrastu i trzy rozmiary tekstu,</li><li>widoczny fokus i logiczna kolejność nawigacji,</li><li>poprawna struktura nagłówków i obszarów strony,</li><li>tabele z nagłówkami i podpisami, opisy alternatywne grafik,</li><li>układ responsywny – bez przewijania w poziomie przy szerokości 320 px.</li></ul>
<h2 id="a11y-kontakt">Informacje zwrotne i dane kontaktowe</h2>
<p>Wszystkie problemy z dostępnością cyfrową tej strony internetowej możesz zgłosić do <span id="a11y-osoba">koordynatora do spraw dostępności (osoba fikcyjna)</span> mailowo: <a id="a11y-email" href="mailto:dostepnosc@example.org">dostepnosc@example.org</a> lub telefonicznie: <span id="a11y-telefon">00 000 00 00</span>.</p>
<p>Każdy ma prawo wystąpić z żądaniem zapewnienia dostępności cyfrowej tej strony internetowej lub jej elementów. Można także zażądać udostępnienia informacji za pomocą alternatywnego sposobu dostępu. Podmiot publiczny zrealizuje żądanie niezwłocznie, nie później niż w ciągu 7 dni od dnia wystąpienia z żądaniem. Jeżeli podmiot publiczny odmówi realizacji żądania, możesz złożyć skargę. Po wyczerpaniu procedury możesz także złożyć wniosek do Rzecznika Praw Obywatelskich.</p>
<h2 id="a11y-architektura">Dostępność architektoniczna</h2>
<p>Budynek urzędu (fikcyjny) ma podjazd dla wózków, windę i toaletę dostosowaną. Do budynku można wejść z psem asystującym. W urzędzie dostępna jest pętla indukcyjna i tłumacz PJM online.</p>
HTML;
isv_page('Deklaracja dostępności', 'deklaracja-dostepnosci', $dekl, 0, 20);
isv_page('Polityka prywatności', 'polityka-prywatnosci', '<p class="notice"><strong>Wersja demonstracyjna.</strong> Serwis nie zbiera danych osobowych: formularze nie wysyłają ani nie zapisują treści, nie używamy analityki ani plików cookie śledzących. Zapamiętujemy w przeglądarce (localStorage) jedynie Twój wybór kontrastu i rozmiaru tekstu.</p><p>W prawdziwym wdrożeniu tutaj znajdzie się klauzula informacyjna RODO administratora danych.</p>', 0, 21);
isv_page('Mapa strony', 'mapa-strony', '[mapa_strony]', 0, 22);
$news = isv_page('Aktualności', 'aktualnosci', '', 0, 5);

/* ---------- WPISY ---------- */
$cats = [];
foreach (['aktualnosci' => 'Aktualności', 'ogloszenia' => 'Ogłoszenia', 'szkola' => 'Szkoła', 'biblioteka' => 'Biblioteka'] as $slug => $name) {
    $t = term_exists($slug, 'category') ?: wp_insert_term($name, 'category', ['slug' => $slug]);
    $cats[$slug] = (int) (is_array($t) ? $t['term_id'] : $t);
}
$posts = [
    ['Nowy chodnik w Wzorcowie oddany do użytku', 'aktualnosci', '-2 days', 'Zakończyła się budowa 800 m chodnika wzdłuż drogi gminnej. Inwestycja poprawi bezpieczeństwo pieszych, w tym dzieci w drodze do szkoły.'],
    ['Zmiana godzin pracy urzędu w okresie świątecznym', 'ogloszenia', '-3 days', '24 grudnia urząd będzie nieczynny. 31 grudnia urząd pracuje do godziny 12:00.'],
    ['Konsultacje społeczne programu współpracy z organizacjami', 'ogloszenia', '-6 days', 'Zapraszamy do zgłaszania uwag do projektu programu współpracy z organizacjami pozarządowymi na 2027 r. Uwagi można składać do 15 grudnia.'],
    ['Ogłoszenie o naborze na stanowisko urzędnicze', 'ogloszenia', '-8 days', 'Wójt Gminy Przykładowo ogłasza nabór na wolne stanowisko podinspektora ds. inwestycji (ogłoszenie fikcyjne).'],
    ['Uczniowie SP 1 laureatami konkursu matematycznego', 'szkola', '-4 days', 'Troje uczniów naszej szkoły zdobyło tytuł laureata w wojewódzkim konkursie matematycznym (wydarzenie fikcyjne).'],
    ['Dzień otwarty dla przyszłych pierwszoklasistów', 'szkola', '-10 days', 'Zapraszamy rodziców i dzieci na dzień otwarty w sobotę 17 stycznia 2027 r.'],
    ['Nowa kolekcja książek z dużą czcionką', 'biblioteka', '-5 days', 'Biblioteka powiększyła zbiory o 120 tytułów z dużą czcionką oraz 40 audiobooków.'],
    ['Ferie z biblioteką – zapisy', 'biblioteka', '-12 days', 'Ruszają zapisy na zajęcia feryjne dla dzieci w wieku 7–12 lat.'],
];
foreach ($posts as [$t, $c, $when, $body]) {
    wp_insert_post(['post_type' => 'post', 'post_status' => 'publish', 'post_title' => $t, 'post_date' => date('Y-m-d H:i:s', strtotime($when . ' 09:00')),
        'post_content' => '<p class="notice"><strong>Wiadomość demonstracyjna.</strong> Wydarzenie i dane są fikcyjne.</p><p>' . $body . '</p>',
        'post_excerpt' => $body, 'post_category' => [$cats[$c]], 'comment_status' => 'closed']);
}
wp_delete_post(1, true); // „Witaj, świecie!”
wp_delete_post(2, true); // przykładowa strona
wp_delete_post(3, true); // przykładowa polityka prywatności (szkic)

update_option('show_on_front', 'page');
update_option('page_on_front', $gmina);
update_option('page_for_posts', $news);
update_option('blog_public', 0);
update_option('blogname', 'Gmina Przykładowo – DEMO');
update_option('blogdescription', 'Projekt pokazowy Isvero Studio. Nie jest to strona żadnej instytucji publicznej.');
update_option('posts_per_page', 10);
update_option('default_comment_status', 'closed');
update_option('permalink_structure', '/%postname%/');
update_option('timezone_string', 'Europe/Warsaw');
update_option('date_format', 'j F Y');
update_option('time_format', 'H:i');
update_option('isv_seeded', gmdate('c'));
flush_rewrite_rules();
echo "Treści demo utworzone.\n";
