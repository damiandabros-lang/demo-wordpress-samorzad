<?php get_header(); ?>
<div class="wrap layout">
  <main id="tresc" class="content" tabindex="-1">
    <h1><?php
      if (is_search()) { printf('Wyniki wyszukiwania: „%s”', esc_html(get_search_query())); }
      elseif (is_category()) { single_cat_title('Kategoria: '); }
      elseif (is_home()) { echo 'Aktualności'; }
      else { echo esc_html(wp_get_document_title()); }
    ?></h1>
    <?php if (have_posts()) : ?>
      <ul class="post-list">
      <?php while (have_posts()) : the_post(); ?>
        <li>
          <p class="meta"><?php if (get_post_type() === 'post') : ?><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time> · <?php the_category(', '); ?><?php else : ?>Strona<?php endif; ?></p>
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?></p>
        </li>
      <?php endwhile; ?>
      </ul>
      <nav aria-label="Stronicowanie" class="pagination"><?php echo paginate_links(['prev_text' => 'Poprzednia', 'next_text' => 'Następna']); ?></nav>
    <?php else : ?>
      <p>Nic nie znaleziono. Spróbuj innego słowa lub przejdź do <a href="<?php echo esc_url(home_url('/mapa-strony/')); ?>">mapy strony</a>.</p>
    <?php endif; ?>
  </main>
</div>
<?php get_footer();
