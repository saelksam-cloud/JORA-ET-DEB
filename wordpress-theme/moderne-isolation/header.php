<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ( is_front_page() ) : ?>
<meta name="description" content="Moderne Isolation, entreprise de rénovation intérieure et extérieure depuis <?php echo esc_attr( mi_option( 'since_year', '2016' ) ); ?> (isolation, peinture, placo, maçonnerie) à Aix-en-Provence et dans toutes les Bouches-du-Rhône. Devis gratuit sous 48h.">
<meta name="keywords" content="rénovation Aix-en-Provence, isolation Bouches-du-Rhône, peinture Aix-en-Provence, maçonnerie Aix-en-Provence, plâtrerie placo, rénovation intérieure 13">
<?php endif; ?>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='20' fill='%23FFFFFF'/%3E%3Cpath d='M20,84 V16 H36 L50,36 L64,16 H80 V84 H68 V38 L50,63 L32,38 V84 Z' fill='%23A31E2B'/%3E%3C/svg%3E">

<?php if ( is_front_page() ) : ?>
<!-- LocalBusiness structured data -->
<script type="application/ld+json">
<?php
$reviews_ld = array();
$review_q   = new WP_Query( array( 'post_type' => 'mi_temoignage', 'posts_per_page' => 2, 'orderby' => 'date', 'order' => 'DESC' ) );
while ( $review_q->have_posts() ) {
	$review_q->the_post();
	$reviews_ld[] = array(
		'@type'         => 'Review',
		'author'        => array( '@type' => 'Person', 'name' => get_the_title() ),
		'reviewRating'  => array( '@type' => 'Rating', 'ratingValue' => (string) ( get_post_meta( get_the_ID(), 'mi_rating', true ) ?: 5 ), 'bestRating' => '5' ),
		'reviewBody'    => wp_strip_all_tags( get_the_content() ),
	);
}
wp_reset_postdata();

$schema = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'HomeAndConstructionBusiness',
	'name'        => get_bloginfo( 'name' ),
	'url'         => home_url( '/' ),
	'description' => 'Entreprise de rénovation intérieure et extérieure (isolation, peinture, plâtrerie, maçonnerie) intervenant à Aix-en-Provence et dans les Bouches-du-Rhône depuis ' . mi_option( 'since_year', '2016' ) . '.',
	'telephone'   => mi_option( 'phone_tel', '' ),
	'email'       => mi_option( 'email', '' ),
	'foundingDate'=> mi_option( 'since_year', '2016' ),
	'priceRange'  => '€€',
	'address'     => array(
		'@type'           => 'PostalAddress',
		'streetAddress'   => mi_option( 'address', '' ),
		'addressRegion'   => "Provence-Alpes-Côte d'Azur",
		'addressCountry'  => 'FR',
	),
	'aggregateRating' => array(
		'@type'       => 'AggregateRating',
		'ratingValue' => mi_option( 'google_rating', '5' ),
		'reviewCount' => mi_option( 'google_reviews_count', '24' ),
		'bestRating'  => '5',
	),
	'review' => $reviews_ld,
);
echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
?>
</script>

<!-- FAQPage structured data -->
<script type="application/ld+json">
<?php
$faq_ld = array();
$faq_q  = new WP_Query( array( 'post_type' => 'mi_faq', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
while ( $faq_q->have_posts() ) {
	$faq_q->the_post();
	$faq_ld[] = array(
		'@type'          => 'Question',
		'name'           => get_the_title(),
		'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( get_the_content() ) ),
	);
}
wp_reset_postdata();
echo wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $faq_ld ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
?>
</script>
<?php endif; ?>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main">Aller au contenu principal</a>

<!-- ===== HEADER ===== -->
<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php bloginfo( 'name' ); ?> - Accueil">
      <svg width="30" height="30" viewBox="0 0 100 100" aria-hidden="true"><path d="M20,84 V16 H36 L50,36 L64,16 H80 V84 H68 V38 L50,63 L32,38 V84 Z" fill="#A31E2B"/></svg>
      <span>Moderne <strong>isolation</strong></span>
    </a>

    <nav class="main-nav" id="main-nav" aria-label="Navigation principale">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '<ul>%3$s</ul>',
        'fallback_cb'    => 'mi_default_menu',
      ) );
      ?>
    </nav>

    <div class="header-actions">
      <a href="tel:<?php echo esc_attr( mi_option( 'phone_tel' ) ); ?>" class="header-phone">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span><?php echo esc_html( mi_option( 'phone' ) ); ?></span>
      </a>
      <a href="<?php echo esc_url( home_url( '/#devis' ) ); ?>" class="btn btn-primary">Devis gratuit</a>
    </div>

    <button class="nav-toggle" id="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="main-nav">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
