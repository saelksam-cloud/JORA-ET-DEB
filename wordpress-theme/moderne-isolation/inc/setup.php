<?php
/**
 * Theme setup: supports, enqueue, menus.
 */

/**
 * Disable WordPress's default emoji-to-image conversion: modern browsers render
 * emoji natively, and this avoids an external request to s.w.org on every page.
 */
function mi_disable_wp_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'mi_disable_wp_emojis' );

function mi_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'custom-logo' );

	register_nav_menus( array(
		'primary' => __( 'Menu principal', 'moderne-isolation' ),
	) );
}
add_action( 'after_setup_theme', 'mi_theme_setup' );

function mi_enqueue_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'mi-style', get_template_directory_uri() . '/assets/css/style.css', array(), $theme_version );
	wp_enqueue_script( 'mi-main', get_template_directory_uri() . '/assets/js/main.js', array(), $theme_version, true );
	wp_enqueue_script( 'mi-analytics', get_template_directory_uri() . '/assets/js/analytics.js', array(), $theme_version, true );

	wp_localize_script( 'mi-main', 'miAjax', array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'mi_contact_form' ),
	) );

	wp_localize_script( 'mi-analytics', 'miAnalytics', array(
		'gaId' => mi_option( 'ga_measurement_id', '' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'mi_enqueue_assets' );

/**
 * Fallback nav (shown until a menu is assigned in Apparence > Menus).
 */
function mi_default_menu() {
	$home = home_url( '/' );
	$items = array(
		'#services'    => 'Services',
		'#realisations'=> 'Réalisations',
		'#avis'        => 'Avis',
		'#partenaires' => 'Partenaires',
		'#zone'        => "Zone d'intervention",
		'#faq'         => 'FAQ',
		'#contact'     => 'Contact',
	);
	echo '<ul>';
	foreach ( $items as $anchor => $label ) {
		echo '<li><a href="' . esc_url( $home . $anchor ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Deterministic pastel-on-brand color for a testimonial avatar, derived from the name.
 */
function mi_avatar_color( $name ) {
	$palette = array( '#7E1620', '#3A3A3E', '#C13645', '#D9601C' );
	$hash    = crc32( $name );
	return $palette[ $hash % count( $palette ) ];
}
