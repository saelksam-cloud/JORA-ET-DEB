<?php
/**
 * Generic page template (Mentions légales, Politique de confidentialité, etc.)
 */
get_header();
?>
<main id="main" class="container legal-content">
  <?php while ( have_posts() ) : the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div class="legal-body"><?php the_content(); ?></div>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
