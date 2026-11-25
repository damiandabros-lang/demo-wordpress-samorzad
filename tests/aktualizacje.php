<?php
/** Test migracji trwałych treści: stare instalacje, runda 2 i powtórne uruchomienie. */
$old = '<p>Strona internetowa jest zgodna z ustawą o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych (WCAG 2.1, poziom AA).</p>';
$r2 = '<p>Strona internetowa jest częściowo zgodna z ustawą o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych (WCAG 2.1, poziom AA) z powodu niezgodności wymienionych poniżej.</p><h3>Treści niedostępne</h3><ul><li>Przykładowe dokumenty PDF w BIP i dziale szkoły nie mają znaczników struktury (tagów), które pozwalają technologiom asystującym rozpoznawać strukturę dokumentu. Nie potwierdzono zgodności tych plików z PDF/UA.</li></ul>';
$posts = [];
foreach ([$old, $r2, 'Opłata skarbowa 50 zł. ogłoszenia o zamówieniach poniżej 130 000 zł.', 'w sobotę 17 stycznia 2027 r.'] as $i => $t) {
    $posts[] = (object)['ID' => $i, 'post_content' => $t, 'post_excerpt' => $t];
}
$writes = 0;
function get_posts($args) { return $GLOBALS['posts']; }
function wp_update_post($data) {
    $GLOBALS['writes']++;
    foreach ($GLOBALS['posts'] as $p) if ($p->ID === $data['ID']) {
        $p->post_content = $data['post_content']; $p->post_excerpt = $data['post_excerpt'];
    }
}
require __DIR__ . '/../wp/init/aktualizacje.php';
if ($writes !== 4) throw new RuntimeException('Nie zmigrowano wszystkich rekordów');
foreach ([0, 1] as $i) {
    if (!str_contains($posts[$i]->post_content, 'pełnej wersji HTML') || str_contains($posts[$i]->post_content, 'nie mają znaczników')) throw new RuntimeException('Nieaktualna deklaracja');
}
if (!str_contains($posts[2]->post_content, '17 zł') || !str_contains($posts[2]->post_content, '170 000 zł netto') || !str_contains($posts[3]->post_excerpt, 'niedzielę')) throw new RuntimeException('Niepoprawne treści');
require __DIR__ . '/../wp/init/aktualizacje.php';
if ($writes !== 4) throw new RuntimeException('Migracja nie jest idempotentna');
echo "PASS: migracja historycznych deklaracji, opłaty, progu, skrótów; idempotencja\n";
