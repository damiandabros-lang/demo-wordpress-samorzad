<?php get_header(); the_post(); ?>
<div class="wrap layout">
  <main id="tresc" class="content" tabindex="-1">
    <article>
      <h1><?php the_title(); ?></h1>
      <p class="meta">Opublikowano: <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time> · Kategoria: <?php the_category(', '); ?></p>
      <?php the_content(); ?>
    </article>
    <nav aria-label="Inne wpisy" class="pagination">
      <?php previous_post_link('%link', '« %title'); ?> <?php next_post_link('%link', '%title »'); ?>
    </nav>
  </main>
</div>
<?php get_footer();
