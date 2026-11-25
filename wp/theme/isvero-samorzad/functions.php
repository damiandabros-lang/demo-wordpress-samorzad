<?php
/**
 * Isvero Samorząd (DEMO) – motyw demonstracyjny.
 */
defined('ABSPATH') || exit;

const ISV_VER = '1.0.0';

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(['primary' => 'Menu główne', 'footer' => 'Menu w stopce']);
});

/* Lekkość: tylko jeden CSS i jeden mały JS, bez jQuery, emoji, bloków na froncie. */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('isv', get_stylesheet_uri(), [], ISV_VER);
    wp_enqueue_script('isv', get_template_directory_uri() . '/assets/site.js', [], ISV_VER, ['strategy' => 'defer', 'in_footer' => true]);
    if (!is_user_logged_in()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        wp_deregister_script('jquery');
    }
}, 20);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
add_filter('wp_img_tag_add_auto_sizes', '__return_false');

/* Wczesne ustawienie kontrastu/rozmiaru czcionki – bez migania. */
add_action('wp_head', function () {
    echo "<script>try{var c=document.documentElement,s=localStorage;if(s.getItem('isv-hc')==='1')c.classList.add('hc');var f=s.getItem('isv-fs');if(f)c.classList.add('fs'+f)}catch(e){}</script>\n";
    echo '<meta name="theme-color" content="#0b4f8a">' . "\n";
    $desc = is_singular() && has_excerpt() ? get_the_excerpt() : (is_singular() ? wp_trim_words(wp_strip_all_tags(str_replace('</p>', '</p> ', strip_shortcodes(get_post_field('post_content', get_queried_object_id())))), 25, '…') : '');
    if (!$desc) $desc = 'DEMO Isvero Studio: dostępny serwis gminy, BIP, szkoły i biblioteki (WCAG 2.1 AA). Dane fikcyjne.';
    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
}, 1);

/** Najwyższy przodek strony (sekcja: Gmina/BIP/Szkoła/Biblioteka). */
function isv_section_root($post = null) {
    $post = get_post($post);
    if (!$post || $post->post_type !== 'page') return 0;
    $anc = get_post_ancestors($post);
    return $anc ? end($anc) : $post->ID;
}

/** Okruszki. */
function isv_breadcrumbs() {
    if (is_front_page()) return;
    $items = ['<li><a href="' . esc_url(home_url('/')) . '">Strona główna</a></li>'];
    if (is_page()) {
        foreach (array_reverse(get_post_ancestors(get_the_ID())) as $a) {
            $items[] = '<li><a href="' . esc_url(get_permalink($a)) . '">' . esc_html(get_the_title($a)) . '</a></li>';
        }
        $items[] = '<li><span aria-current="page">' . esc_html(get_the_title()) . '</span></li>';
    } elseif (is_single()) {
        $pp = get_option('page_for_posts');
        if ($pp) $items[] = '<li><a href="' . esc_url(get_permalink($pp)) . '">' . esc_html(get_the_title($pp)) . '</a></li>';
        $items[] = '<li><span aria-current="page">' . esc_html(get_the_title()) . '</span></li>';
    } elseif (is_home()) {
        $items[] = '<li><span aria-current="page">Aktualności</span></li>';
    } elseif (is_category()) {
        $items[] = '<li><span aria-current="page">Kategoria: ' . esc_html(single_cat_title('', false)) . '</span></li>';
    } elseif (is_search()) {
        $items[] = '<li><span aria-current="page">Wyniki wyszukiwania</span></li>';
    } else {
        $items[] = '<li><span aria-current="page">' . esc_html(wp_get_document_title()) . '</span></li>';
    }
    echo '<nav class="breadcrumbs wrap" aria-label="Okruszki"><ol>' . implode('', $items) . '</ol></nav>';
}

/** Menu główne: strony najwyższego poziomu w kolejności menu_order (fallback bez konfiguracji). */
function isv_primary_nav() {
    if (has_nav_menu('primary')) {
        wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'menu_id' => 'menu-glowne', 'depth' => 1]);
        return;
    }
    $root = isv_section_root();
    $pages = get_pages(['parent' => 0, 'sort_column' => 'menu_order', 'meta_key' => '_isv_in_nav', 'meta_value' => '1']);
    echo '<ul id="menu-glowne">';
    foreach ($pages as $p) {
        $is_front = (int) get_option('page_on_front') === $p->ID;
        $current = ($root === $p->ID && !is_single() && !is_home()) || ($is_front && is_front_page());
        $cls = $current ? ' class="current-section"' : '';
        $aria = (is_page($p->ID)) ? ' aria-current="page"' : '';
        echo '<li' . $cls . '><a href="' . esc_url(get_permalink($p)) . '"' . $aria . '>' . esc_html($p->post_title) . '</a></li>';
    }
    $pp = get_option('page_for_posts');
    if ($pp) {
        $aria = (is_home()) ? ' aria-current="page"' : '';
        $cls = (is_home() || is_single()) ? ' class="current-section"' : '';
        echo '<li' . $cls . '><a href="' . esc_url(get_permalink($pp)) . '"' . $aria . '>Aktualności</a></li>';
    }
    echo '</ul>';
}

/** Ikony SVG (inline, dekoracyjne). */
function isv_icon($name) {
    $p = [
        'doc'   => '<path d="M6 2h9l5 5v15H6z" fill="none" stroke="currentColor" stroke-width="2"/><path d="M15 2v5h5" fill="none" stroke="currentColor" stroke-width="2"/>',
        'case'  => '<rect x="3" y="7" width="18" height="13" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M9 7V4h6v3" fill="none" stroke="currentColor" stroke-width="2"/>',
        'cal'   => '<rect x="3" y="5" width="18" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 10h18M8 3v4M16 3v4" stroke="currentColor" stroke-width="2"/>',
        'phone' => '<path d="M5 3h4l2 5-3 2a12 12 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2A18 18 0 0 1 3 5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="2"/>',
        'trash' => '<path d="M4 7h16M9 7V4h6v3M6 7l1 14h10l1-14" fill="none" stroke="currentColor" stroke-width="2"/>',
        'money' => '<rect x="2" y="6" width="20" height="12" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>',
        'book'  => '<path d="M4 4h7a3 3 0 0 1 3 3v13a2 2 0 0 0-2-2H4zM20 4h-6" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 4v14h-6" fill="none" stroke="currentColor" stroke-width="2"/>',
        'a11y'  => '<circle cx="12" cy="4" r="2" fill="currentColor"/><path d="M4 8l8 2 8-2M12 10v5l-3 6M12 15l3 6" fill="none" stroke="currentColor" stroke-width="2"/>',
    ];
    return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . ($p[$name] ?? $p['doc']) . '</svg>';
}

/* ---------- Shortcodes używane w treściach demo ---------- */

/** [kafelki] – szybkie linki "Załatw sprawę". */
add_shortcode('kafelki', function () {
    $tiles = [
        ['case', 'Załatw sprawę', 'Wnioski i formularze', 'bip/zalatw-sprawe'],
        ['money', 'Podatki i opłaty', 'Terminy, numery kont', 'gmina/podatki-i-oplaty'],
        ['trash', 'Odpady komunalne', 'Harmonogram odbioru', 'gmina/odpady'],
        ['doc', 'BIP', 'Biuletyn Informacji Publicznej', 'bip'],
        ['book', 'Biblioteka', 'Katalog, godziny, wydarzenia', 'biblioteka'],
        ['a11y', 'Dostępność', 'Deklaracja i ułatwienia', 'deklaracja-dostepnosci'],
    ];
    $o = '<ul class="grid" role="list">';
    foreach ($tiles as [$ic, $t, $s, $path]) {
        $o .= '<li><a class="tile" href="' . esc_url(home_url('/' . $path . '/')) . '">' . isv_icon($ic) . '<span>' . esc_html($t) . '<small>' . esc_html($s) . '</small></span></a></li>';
    }
    return $o . '</ul>';
});

/** [aktualnosci n="3" kategoria="..."] */
add_shortcode('aktualnosci', function ($a) {
    $a = shortcode_atts(['n' => 3, 'kategoria' => ''], $a);
    $q = new WP_Query(['posts_per_page' => (int) $a['n'], 'category_name' => $a['kategoria'], 'ignore_sticky_posts' => true, 'no_found_rows' => true]);
    if (!$q->have_posts()) return '<p>Brak wpisów.</p>';
    $o = '<ul class="grid" role="list">';
    while ($q->have_posts()) {
        $q->the_post();
        $o .= '<li class="card"><p class="meta"><time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time></p><h3><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3><p>' . esc_html(wp_trim_words(get_the_excerpt(), 22)) . '</p></li>';
    }
    wp_reset_postdata();
    return $o . '</ul>';
});

/** [dokumenty] – lista plików PDF z opisem formatu i rozmiaru. */
add_shortcode('dokumenty', function ($a) {
    $a = shortcode_atts(['zestaw' => 'bip'], $a);
    $docs = [
        'bip' => [
            ['uchwala-budzetowa-2027-demo.pdf', 'Uchwała budżetowa Gminy Przykładowo na rok 2027 (DEMO)'],
            ['zarzadzenie-wojta-12-2026-demo.pdf', 'Zarządzenie Wójta nr 12/2026 w sprawie regulaminu organizacyjnego (DEMO)'],
            ['wniosek-dowod-osobisty-demo.pdf', 'Wzór wniosku – przykładowy formularz (DEMO)'],
        ],
        'szkola' => [
            ['statut-szkoly-demo.pdf', 'Statut Szkoły Podstawowej nr 1 w Przykładowie (DEMO)'],
            ['rekrutacja-2027-demo.pdf', 'Zasady rekrutacji do klas pierwszych 2027/2028 (DEMO)'],
        ],
        'biblioteka' => [
            ['regulamin-biblioteki-demo.pdf', 'Regulamin korzystania z Biblioteki (DEMO)'],
        ],
    ][$a['zestaw']] ?? [];
    $dir = get_template_directory() . '/assets/docs/';
    $url = get_template_directory_uri() . '/assets/docs/';
    $o = '<ul class="doc-list">';
    foreach ($docs as [$f, $t]) {
        $size = is_file($dir . $f) ? size_format(filesize($dir . $f), 1) : '';
        $o .= '<li>' . isv_icon('doc') . '<span><a href="' . esc_url($url . $f) . '" download>' . esc_html($t) . '</a> <span class="meta">(PDF, ' . esc_html($size) . ')</span></span></li>';
    }
    return $o . '</ul>';
});

/** [rejestr_zmian] – ostatnio zmienione strony BIP. */
add_shortcode('rejestr_zmian', function () {
    $bip = get_page_by_path('bip');
    if (!$bip) return '';
    $ids = array_merge([$bip->ID], wp_list_pluck(get_pages(['child_of' => $bip->ID]), 'ID'));
    $q = new WP_Query(['post_type' => 'page', 'post__in' => $ids, 'orderby' => 'modified', 'order' => 'DESC', 'posts_per_page' => 20, 'no_found_rows' => true]);
    $o = '<div class="table-wrap"><table><caption>Rejestr zmian w Biuletynie (DEMO)</caption><thead><tr><th scope="col">Data zmiany</th><th scope="col">Strona</th><th scope="col">Rodzaj</th><th scope="col">Wprowadził</th></tr></thead><tbody>';
    while ($q->have_posts()) {
        $q->the_post();
        $kind = get_the_modified_date('U') - get_the_date('U') > 60 ? 'Aktualizacja' : 'Publikacja';
        $o .= '<tr><td><time datetime="' . esc_attr(get_the_modified_date('c')) . '">' . esc_html(get_the_modified_date('Y-m-d H:i')) . '</time></td><td><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></td><td>' . $kind . '</td><td>' . esc_html(get_post_meta(get_the_ID(), '_isv_opublikowal', true) ?: 'Redakcja BIP (demo)') . '</td></tr>';
    }
    wp_reset_postdata();
    return isv_table_wrap_a11y($o . '</tbody></table></div>');
});

/** [formularz_kontaktowy] – w DEMO nic nie wysyła i nic nie zapisuje. */
add_shortcode('formularz_kontaktowy', function () {
    $sent = isset($_POST['isv_demo_form']);
    $o = '';
    if ($sent) {
        $o .= '<div class="notice notice-ok" role="status" tabindex="-1" id="form-status"><p><strong>To jest wersja demonstracyjna.</strong> Formularz działa poprawnie, ale nie wysyła ani nie zapisuje żadnych danych.</p></div>';
    }
    $o .= '<form method="post" action="#form-status" novalidate>
<p class="hint">Pola oznaczone gwiazdką (*) są wymagane.</p>
<div class="form-row"><label for="f-name">Imię i nazwisko *</label><input id="f-name" name="f_name" type="text" autocomplete="name" required aria-required="true"></div>
<div class="form-row"><label for="f-email">Adres e-mail *</label><input id="f-email" name="f_email" type="email" autocomplete="email" required aria-required="true" aria-describedby="f-email-hint"><p class="hint" id="f-email-hint">Na ten adres wyślemy odpowiedź (w demo – nie wysyłamy).</p></div>
<div class="form-row"><label for="f-topic">Temat</label><select id="f-topic" name="f_topic"><option>Sprawy ogólne</option><option>Podatki i opłaty</option><option>Odpady</option><option>Dostępność</option></select></div>
<div class="form-row"><label for="f-msg">Wiadomość *</label><textarea id="f-msg" name="f_msg" rows="6" required aria-required="true"></textarea></div>
<div class="form-row form-check"><input id="f-rodo" name="f_rodo" type="checkbox" required aria-required="true"><label for="f-rodo">Zapoznałem(-am) się z <a href="' . esc_url(home_url('/polityka-prywatnosci/')) . '">informacją o przetwarzaniu danych</a>. *</label></div>
<input type="hidden" name="isv_demo_form" value="1">
<button class="btn" type="submit">Wyślij wiadomość</button>
</form>';
    return $o;
});

/** [mapa_strony] */
add_shortcode('mapa_strony', function () {
    return '<ul>' . wp_list_pages(['title_li' => '', 'echo' => 0, 'sort_column' => 'menu_order,post_title']) . '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">Aktualności</a></li></ul>';
});

/** [katalog] – wyszukiwarka katalogu biblioteki (demo, bez backendu). */
add_shortcode('katalog', function () {
    $q = isset($_GET['k']) ? sanitize_text_field(wp_unslash($_GET['k'])) : '';
    $o = '<form method="get" action="#wyniki-katalogu" role="search" aria-label="Katalog biblioteki"><div class="form-row"><label for="k">Tytuł, autor lub temat</label><input id="k" name="k" type="search" value="' . esc_attr($q) . '"></div><button class="btn" type="submit">Szukaj w katalogu</button></form>';
    if ($q !== '') {
        $o .= '<div id="wyniki-katalogu" class="notice" role="status"><p>Wersja demonstracyjna: w prawdziwym wdrożeniu tutaj pojawią się wyniki z systemu bibliotecznego (np. MAK+, Koha) dla zapytania „' . esc_html($q) . '”.</p></div>';
    }
    return $o;
});

/* Przewijane tabele muszą być dostępne z klawiatury (WCAG 2.1.1). */
function isv_table_wrap_a11y($html) {
    return str_replace('<div class="table-wrap">', '<div class="table-wrap" tabindex="0" role="region" aria-label="Tabela – na małym ekranie przewiń w poziomie">', $html);
}
add_filter('the_content', 'isv_table_wrap_a11y', 20);
