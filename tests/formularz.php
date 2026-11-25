<?php
// Izolowany test shortcodu; nie uruchamia WordPressa ani nie wysyła danych.
define('ABSPATH', __DIR__);
$shortcodes = [];
function add_shortcode($name, $callback) { $GLOBALS['shortcodes'][$name] = $callback; }
function add_action(...$args) {}
function add_filter($name, $callback, ...$args) { $GLOBALS['filters'][$name] = $callback; }
function is_front_page() { return $GLOBALS['front'] ?? false; }
function remove_action(...$args) {}
function wp_unslash($s) { return stripslashes($s); }
function is_email($s) { return filter_var($s, FILTER_VALIDATE_EMAIL); }
function esc_attr($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function esc_html($s) { return esc_attr($s); }
function esc_textarea($s) { return esc_attr($s); }
function esc_url($s) { return esc_attr($s); }
function home_url($s) { return 'https://example.invalid' . $s; }
require __DIR__ . '/../wp/theme/isvero-samorzad/functions.php';
function sprawdz($condition, $message) { if (!$condition) throw new RuntimeException($message); }
$render = $shortcodes['formularz_kontaktowy'];
$GLOBALS['front'] = true;
$title = ['title' => 'Gmina Przykładowo – DEMO', 'tagline' => 'Długi opis demonstracyjny'];
sprawdz($GLOBALS['filters']['document_title_parts']($title) === ['title' => 'Gmina Przykładowo – DEMO'], 'Krótki tytuł strony głównej');
$GLOBALS['front'] = false;
sprawdz($GLOBALS['filters']['document_title_parts']($title) === $title, 'Brak zmiany tytułów podstron');
$_POST = ['isv_demo_form' => '1'];
$html = $render();
sprawdz(substr_count($html, 'aria-invalid="true"') === 4, 'Puste pola muszą wskazywać cztery błędy');
sprawdz(!str_contains($html, 'Formularz działa poprawnie'), 'Brak fałszywego sukcesu');
$_POST = ['isv_demo_form' => '1', 'f_name' => '<script>test</script>', 'f_email' => 'zly', 'f_msg' => '</textarea><script>test</script>', 'f_rodo' => 'on'];
$html = $render();
sprawdz(!str_contains($html, '<script>'), 'Wartości muszą być escapowane');
sprawdz(str_contains($html, 'name="f_rodo" checked'), 'Zachowanie zaznaczenia');
$_POST['f_email'] = 'test@example.org';
sprawdz(str_contains($render(), 'Formularz działa poprawnie'), 'Poprawne dane dają sukces');
$_POST['f_email'] = ['nieoczekiwana tablica'];
sprawdz(str_contains($render(), 'f-email-err'), 'Niepoprawny typ danych daje błąd');
echo "OK: walidacja pustych pól, sukces, escapowanie, zachowanie danych, typy wejścia\n";
