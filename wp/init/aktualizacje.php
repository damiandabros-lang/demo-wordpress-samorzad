<?php
/**
 * Korekty treści już zainstalowanego demo (uruchamiane przy każdym starcie, idempotentne).
 * Świeża instalacja dostaje te same treści z seed.php.
 */
$zamiany = [
    'Serwis „Gminy Przykładowo” to projekt' => 'Serwis Gminy Przykładowo to projekt',
    '<p>Strona internetowa jest zgodna z ustawą o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych (WCAG 2.1, poziom AA).</p>' =>
        '<p>Strona internetowa jest częściowo zgodna z ustawą o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych (WCAG 2.1, poziom AA) z powodu niezgodności wymienionych poniżej.</p><h3>Treści niedostępne</h3><ul><li>Przykładowe dokumenty PDF w BIP są krótkimi plikami pokazowymi i nie przeszły pełnej weryfikacji dostępności (PDF/UA).</li></ul>',
    'dla dzieci w wieku 6–12 lat.' => 'dla dzieci w wieku 7–12 lat.',
];
$zmienione = 0;
foreach (get_posts(['post_type' => ['page', 'post'], 'post_status' => 'any', 'numberposts' => -1]) as $p) {
    $nowa = strtr($p->post_content, $zamiany);
    if ($nowa !== $p->post_content) {
        wp_update_post(['ID' => $p->ID, 'post_content' => $nowa]);
        $zmienione++;
    }
}
echo "[isv] aktualizacje treści: zmieniono $zmienione\n";
