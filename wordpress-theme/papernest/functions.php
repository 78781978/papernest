<?php
/**
 * PaperNest theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PAPERNEST_VERSION', '1.0.0' );
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

	// WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu główne', 'papernest' ),
		)
	);

	add_image_size( 'papernest-portfolio', 800, 800, true );
	add_image_size( 'papernest-wide', 1600, 900, true );
}
add_action( 'after_setup_theme', 'papernest_setup' );

/**
 * Default the primary menu to the site's real pages the first time the
 * theme is activated, so navigation isn't empty before the client sets one up.
 */
require_once PAPERNEST_DIR . '/inc/customizer.php';
require_once PAPERNEST_DIR . '/inc/template-tags.php';
require_once PAPERNEST_DIR . '/inc/cpt.php';
require_once PAPERNEST_DIR . '/inc/legal-toc.php';
require_once PAPERNEST_DIR . '/inc/woocommerce.php';
require_once PAPERNEST_DIR . '/inc/demo-content.php';
require_once PAPERNEST_DIR . '/inc/contact-form.php';

/**
 * Styles & scripts — reuses the already-built, accessibility-checked stylesheet
 * from the static prototype as-is.
 */
function papernest_assets() {
	wp_enqueue_style( 'papernest-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'papernest-style', PAPERNEST_URI . '/assets/css/style.css', array(), PAPERNEST_VERSION );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'papernest-wc-theme', PAPERNEST_URI . '/assets/css/wc-theme.css', array( 'papernest-style', 'woocommerce-general' ), PAPERNEST_VERSION );
	}
	wp_enqueue_script( 'papernest-main', PAPERNEST_URI . '/assets/js/main.js', array(), PAPERNEST_VERSION, true );
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

/**
 * Cart count fragment so the header cart badge in assets/js/main.js style
 * stays correct after AJAX add-to-cart, without touching the shared JS file.
 */
function papernest_cart_count_fragment( $fragments ) {
	ob_start();
	?>
	<span class="cart-count"><?php echo absint( WC()->cart->get_cart_contents_count() ); ?></span>
	<?php
	$fragments['.cart-count'] = ob_get_clean();
	return $fragments;
}
if ( class_exists( 'WooCommerce' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'papernest_cart_count_fragment' );
}
