<?php
/**
 * PaperNest theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PAPERNEST_VERSION', '1.1.0' );
define( 'PAPERNEST_DIR', get_template_directory() );
define( 'PAPERNEST_URI', get_template_directory_uri() );

/**
 * Theme setup: supports, nav menus, image sizes.
 */
function papernest_setup() {
	load_theme_textdomain( 'papernest', PAPERNEST_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'custom-logo',
		array(
			'width'       => 288,
			'height'      => 192,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu główne', 'papernest' ),
		)
	);

	add_image_size( 'papernest-portfolio', 800, 800, true );
	add_image_size( 'papernest-wide', 1600, 900, true );
}
add_action( 'after_setup_theme', 'papernest_setup' );

require_once PAPERNEST_DIR . '/inc/customizer.php';
require_once PAPERNEST_DIR . '/inc/template-tags.php';
require_once PAPERNEST_DIR . '/inc/cpt.php';
require_once PAPERNEST_DIR . '/inc/legal-toc.php';
require_once PAPERNEST_DIR . '/inc/contact-form.php';
require_once PAPERNEST_DIR . '/inc/shop/product-cpt.php';
require_once PAPERNEST_DIR . '/inc/shop/cart.php';
require_once PAPERNEST_DIR . '/inc/shop/order-cpt.php';
require_once PAPERNEST_DIR . '/inc/shop/settings.php';
require_once PAPERNEST_DIR . '/inc/shop/emails.php';
require_once PAPERNEST_DIR . '/inc/shop/checkout.php';
require_once PAPERNEST_DIR . '/inc/demo-content.php';

/**
 * Styles & scripts — reuses the already-built, accessibility-checked stylesheet
 * from the static prototype as-is.
 */
function papernest_assets() {
	wp_enqueue_style( 'papernest-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'papernest-style', PAPERNEST_URI . '/assets/css/style.css', array(), PAPERNEST_VERSION );

	wp_enqueue_script( 'papernest-main', PAPERNEST_URI . '/assets/js/main.js', array(), PAPERNEST_VERSION, true );

	wp_enqueue_script( 'papernest-shop', PAPERNEST_URI . '/assets/js/shop.js', array(), PAPERNEST_VERSION, true );
	wp_localize_script(
		'papernest-shop',
		'papernestShop',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'papernest_cart' ),
			'cartUrl' => papernest_page_link( 'koszyk' ),
			'i18n'    => array(
				'added'   => __( 'Dodano do koszyka', 'papernest' ),
				'error'   => __( 'Coś poszło nie tak, spróbuj ponownie.', 'papernest' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'papernest_assets' );

/**
 * WordPress's own style.css (theme header only) isn't the design stylesheet,
 * so it doesn't need to load on the front end.
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Content width for embeds/oEmbed.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200;
}
