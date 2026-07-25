<?php
/**
 * PaperNest theme bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tied to style.css's Version header so every asset URL (and therefore any
// browser/server cache keyed on it) automatically changes on each release,
// instead of relying on a second version number that's easy to forget to bump.
define( 'PAPERNEST_VERSION', wp_get_theme()->get( 'Version' ) );
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
require_once PAPERNEST_DIR . '/inc/typography.php';
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
	// Self-hosted (see assets/fonts/) instead of fonts.googleapis.com: removes
	// a whole cross-origin DNS+TLS+request round trip from the critical
	// render path (was the biggest single "render-blocking requests" hit in
	// PageSpeed), and only latin + latin-ext subsets were kept — everything
	// on the site is Polish, so cyrillic/greek/vietnamese glyphs never
	// render and were pure dead weight.
	wp_enqueue_style( 'papernest-fonts', PAPERNEST_URI . '/assets/css/fonts.css', array(), PAPERNEST_VERSION );
	wp_enqueue_style( 'papernest-style', PAPERNEST_URI . '/assets/css/style.css', array( 'papernest-fonts' ), PAPERNEST_VERSION );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'papernest-wc-theme', PAPERNEST_URI . '/assets/css/wc-theme.css', array( 'papernest-style', 'woocommerce-general' ), PAPERNEST_VERSION );
	}
	wp_enqueue_script( 'papernest-main', PAPERNEST_URI . '/assets/js/main.js', array(), PAPERNEST_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'papernest_assets' );

/**
 * Preload the two above-the-fold font files (Inter + Playfair Display,
 * regular Latin subset) so the browser fetches them immediately instead of
 * discovering them only after parsing fonts.css — cuts the delay before
 * text renders in its final font, which is what drives both LCP and the
 * layout shift caused by the fallback-to-webfont swap.
 */
add_action(
	'wp_head',
	function () {
		printf(
			'<link rel="preload" href="%1$s/assets/fonts/UcC73FwrK3iLTeHuS_nVMrMxCp50SjIa1ZL7.woff2" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( PAPERNEST_URI )
		);
		printf(
			'<link rel="preload" href="%1$s/assets/fonts/nuFiD-vYSZviVYUb_rj3ij__anPXDTzYgA.woff2" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( PAPERNEST_URI )
		);
	},
	1
);

/**
 * WordPress's own style.css (theme header only) isn't the design stylesheet,
 * so it doesn't need to load on the front end.
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * A link to /kontakt/ somewhere on the site (not from this theme's own
 * templates — none of them use this text) renders the generic English
 * "Learn more" instead of descriptive Polish text, which Lighthouse's SEO
 * audit flags as a non-descriptive link. Translate it wherever it's printed.
 */
add_filter(
	'gettext',
	function ( $translated, $original ) {
		if ( 'Learn more' === $original ) {
			return 'Przejdź do kontaktu';
		}
		return $translated;
	},
	10,
	2
);

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
		// Known content pages always use the theme's own template, by slug,
		// regardless of what a page builder plugin has set as this page's
		// _wp_page_template — on pages that existed before this theme (built
		// with Elementor) that meta points at a page-builder "canvas"
		// template, which would otherwise keep winning here even though the
		// theme is active.
		$slug_templates = array(
			'o-nas'                         => 'page-o-nas.php',
			'portfolio'                     => 'page-portfolio.php',
			'kontakt'                       => 'page-kontakt.php',
			'platnosc-i-dostawa'            => 'page-platnosc.php',
			'odstapienie'                   => 'page-odstapienie.php',
			'regulamin'                     => 'page.php',
			'polityka-prywatnosci'          => 'page.php',
			'reklamacje'                    => 'page.php',
			'prawo-do-odstapienia-od-umowy' => 'page.php',
			'dostepnosc'                    => 'page.php',
		);
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( isset( $slug_templates[ $slug ] ) ) {
			$file = PAPERNEST_DIR . '/' . $slug_templates[ $slug ];
			if ( file_exists( $file ) ) {
				return $file;
			}
		}
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
