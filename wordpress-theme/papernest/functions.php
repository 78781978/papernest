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

/**
 * Page builders (e.g. Elementor) can hook template_include at high priority
 * to fully replace the theme's template on pages they've "taken over"
 * editing — even when this theme's own template is assigned to that page —
 * which is why a page could keep showing an old page-builder design after
 * switching to this theme. This runs last and forces our own front-page.php
 * and page-*.php templates to win for the pages this theme controls,
 * without touching or deleting whatever page-builder data already exists;
 * it's purely about which template renders, so it's fully reversible by
 * removing this filter.
 */
add_filter( 'template_include', 'papernest_force_own_templates', PHP_INT_MAX );
function papernest_force_own_templates( $template ) {
	if ( is_front_page() && ! is_home() ) {
		$front = PAPERNEST_DIR . '/front-page.php';
		if ( file_exists( $front ) ) {
			return $front;
		}
	}
	if ( is_page() ) {
		$page_template = get_page_template_slug( get_queried_object_id() );
		if ( $page_template ) {
			$file = PAPERNEST_DIR . '/' . $page_template;
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
	}
	return $template;
}
