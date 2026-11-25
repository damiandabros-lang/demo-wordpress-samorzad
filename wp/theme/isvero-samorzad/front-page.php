<?php get_header(); the_post(); ?>
<main id="tresc" tabindex="-1">
  <section class="hero" aria-labelledby="hero-h">
    <div class="wrap">
      <div>
        <h1 id="hero-h">Witamy w Gminie Przykładowo</h1>
        <p>Przykładowy serwis samorządowy: aktualności, sprawy urzędowe, Biuletyn Informacji Publicznej, szkoła i biblioteka w jednym, dostępnym miejscu.</p>
        <p><a class="btn" href="<?php echo esc_url(home_url('/bip/zalatw-sprawe/')); ?>">Załatw sprawę</a> <a class="btn btn-ghost" href="<?php echo esc_url(home_url('/kontakt/')); ?>">Kontakt z urzędem</a></p>
      </div>
      <svg viewBox="0 0 400 220" role="img" aria-labelledby="hero-svg-t"><title id="hero-svg-t">Ilustracja: budynek urzędu, szkoła i biblioteka</title>
        <rect x="0" y="180" width="400" height="40" fill="#c5ced8"/>
        <rect x="140" y="70" width="120" height="110" fill="#0b4f8a"/><path d="M130 70l70-45 70 45z" fill="#083a66"/>
        <rect x="160" y="95" width="20" height="25" fill="#fff"/><rect x="190" y="95" width="20" height="25" fill="#fff"/><rect x="220" y="95" width="20" height="25" fill="#fff"/><rect x="185" y="140" width="30" height="40" fill="#fff"/>
        <rect x="20" y="110" width="100" height="70" fill="#8a1c14"/><rect x="35" y="125" width="18" height="18" fill="#fff"/><rect x="62" y="125" width="18" height="18" fill="#fff"/><rect x="89" y="125" width="18" height="18" fill="#fff"/>
        <rect x="280" y="100" width="100" height="80" fill="#1e6b34"/><rect x="295" y="115" width="70" height="8" fill="#fff"/><rect x="295" y="130" width="70" height="8" fill="#fff"/><rect x="295" y="145" width="70" height="8" fill="#fff"/>
      </svg>
    </div>
  </section>
  <div class="wrap">
    <section class="section" aria-labelledby="sprawy-h"><h2 id="sprawy-h">Najczęściej szukane</h2><?php echo do_shortcode('[kafelki]'); ?></section>
    <section class="section" aria-labelledby="news-h"><h2 id="news-h">Aktualności</h2><?php echo do_shortcode('[aktualnosci n="3"]'); ?>
      <p><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">Wszystkie aktualności</a></p></section>
    <section class="section" aria-labelledby="ogl-h"><h2 id="ogl-h">Ogłoszenia</h2><?php echo do_shortcode('[aktualnosci n="3" kategoria="ogloszenia"]'); ?></section>
    <section class="section content" aria-labelledby="intro-h"><h2 id="intro-h">O serwisie</h2><?php the_content(); ?></section>
  </div>
</main>
<?php get_footer();
