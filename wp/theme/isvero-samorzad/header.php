<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#tresc">Przejdź do treści</a>
<a class="skip-link" href="#menu-glowne">Przejdź do menu</a>
<div class="demo-bar" role="region" aria-label="Informacja o wersji demonstracyjnej">
  <div class="wrap">
    <p style="margin:0"><span class="demo-badge">DEMO</span>Projekt pokazowy <a href="https://isverostudio.com/">Isvero Studio</a>. Nie jest to strona żadnej instytucji publicznej, a wszystkie nazwy i dane są fikcyjne.</p>
    <div class="a11y-tools" role="group" aria-label="Ułatwienia dostępu">
      <button type="button" data-fs="0" aria-pressed="false" aria-label="A – domyślny rozmiar tekstu">A</button>
      <button type="button" data-fs="1" aria-pressed="false" aria-label="A+ – większy tekst">A+</button>
      <button type="button" data-fs="2" aria-pressed="false" aria-label="A++ – największy tekst">A++</button>
      <button type="button" id="hc-toggle" aria-pressed="false">Wysoki kontrast</button>
    </div>
  </div>
</div>
<header class="site-header">
  <div class="wrap">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <svg viewBox="0 0 48 48" aria-hidden="true" focusable="false"><path d="M24 3l18 7v12c0 11-8 19-18 23C14 41 6 33 6 22V10z" fill="#0b4f8a"/><path d="M15 30l9-14 9 14z" fill="#fff"/><circle cx="24" cy="34" r="3" fill="#fff"/></svg>
      <span><span class="brand-name">Gmina Przykładowo</span><span class="brand-sub">Urząd Gminy Przykładowo · wersja demonstracyjna</span></span>
    </a>
    <form class="search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <label class="sr-only" for="s">Szukaj w serwisie</label>
      <input type="search" id="s" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Szukaj…">
      <button type="submit">Szukaj</button>
    </form>
  </div>
</header>
<nav class="main-nav" aria-label="Menu główne">
  <div class="wrap">
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu-glowne">
      <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2"/></svg>Menu
    </button>
    <?php isv_primary_nav(); ?>
  </div>
</nav>
<?php isv_breadcrumbs(); ?>
