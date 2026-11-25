<?php
/**
 * Plugin Name: Isvero DEMO – zabezpieczenia
 * Description: noindex do czasu akceptacji, brak wysyłki e-maili, ograniczenie powierzchni ataku. Wersja demonstracyjna.
 */
defined('ABSPATH') || exit;

/* noindex: nagłówek + robots.txt (dopóki ISV_ALLOW_INDEX nie jest ustawione na "1"). */
if (getenv('ISV_ALLOW_INDEX') !== '1') {
    add_filter('pre_option_blog_public', fn() => '0');
    add_action('send_headers', fn() => header('X-Robots-Tag: noindex, nofollow', true));
    add_filter('robots_txt', fn() => "User-agent: *\nDisallow: /\n", 99);
}
/* Demo nie wysyła żadnych e-maili. */
add_filter('pre_wp_mail', '__return_false');
/* XML-RPC i pingbacki wyłączone. */
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', function ($h) { unset($h['X-Pingback']); return $h; });
/* Bez enumeracji użytkowników. */
add_filter('rest_endpoints', function ($e) {
    if (!is_user_logged_in()) { unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']); }
    return $e;
});
add_action('template_redirect', function () {
    if (is_author() || (isset($_GET['author']) && !is_admin())) { wp_safe_redirect(home_url('/'), 301); exit; }
});
/* Bezpieczne nagłówki. */
add_action('send_headers', function () {
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-Frame-Options: SAMEORIGIN');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
});
/* Komentarze wyłączone globalnie. */
add_filter('comments_open', '__return_false', 20);
add_filter('pings_open', '__return_false', 20);
