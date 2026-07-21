<?php
/**
 * Fallback template (single posts, archives, search...). This theme is built
 * around front-page.php + page.php; blog/archive templates can be extended
 * later if "Actualités" grows into a full blog.
 */
get_header();
?>
<main id="main" class="container legal-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  <?php endwhile; else : ?>
    <p>Aucun contenu trouvé.</p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
