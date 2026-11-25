<?php get_header(); the_post();
$root = isv_section_root();
$children = $root ? get_pages(['child_of' => $root]) : [];
$has_side = !empty($children);
?>
<div class="wrap layout<?php echo $has_side ? ' has-side' : ''; ?>">
<?php if ($has_side) : ?>
  <nav class="side-nav" aria-labelledby="side-nav-title">
    <h2 id="side-nav-title"><?php echo esc_html(get_the_title($root)); ?> – menu działu</h2>
    <ul>
      <li class="<?php echo get_the_ID() === $root ? 'current_page_item' : ''; ?>"><a href="<?php echo esc_url(get_permalink($root)); ?>"<?php echo get_the_ID() === $root ? ' aria-current="page"' : ''; ?>>Strona działu</a></li>
      <?php
      $html = wp_list_pages(['child_of' => $root, 'title_li' => '', 'echo' => 0, 'sort_column' => 'menu_order,post_title']);
      echo str_replace('current_page_item"><a ', 'current_page_item"><a aria-current="page" ', $html);
      ?>
    </ul>
  </nav>
<?php endif; ?>
  <main id="tresc" class="content" tabindex="-1">
    <article>
      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>
      <?php get_template_part('parts/metryczka'); ?>
    </article>
  </main>
</div>
<?php get_footer();
