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
	// Logo is a simple graphic, never displayed above 180px tall — this is
	// plenty for a sharp retina image without serving the full-resolution
	// upload (which could be any size a client happens to upload) everywhere.
	add_image_size( 'papernest-logo', 220, 150, false );
}
add_action( 'after_setup_theme', 'papernest_setup' );

/**
 * A newly add_image_size()'d size only gets generated for images uploaded
 * from now on — the client's logo was already uploaded before
 * 'papernest-logo' existed, so without this, wp_get_attachment_image()
 * would silently fall back to the full-resolution original anyway (WP does
 * not backfill missing intermediate sizes on its own) and this optimization
 * would do nothing until she happened to re-upload the logo. Regenerates
 * just once per logo attachment.
 */
add_action(
	'init',
	function () {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( ! $logo_id || ! wp_attachment_is_image( $logo_id ) ) {
			return;
		}
		$meta = wp_get_attachment_metadata( $logo_id );
		if ( isset( $meta['sizes']['papernest-logo'] ) ) {
			return;
		}
		$file = get_attached_file( $logo_id );
		if ( ! $file || ! file_exists( $file ) ) {
			return;
		}
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$new_meta = wp_generate_attachment_metadata( $logo_id, $file );
		if ( $new_meta ) {
			wp_update_attachment_metadata( $logo_id, $new_meta );
		}
	}
);

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
 * WooCommerce's own CSS/JS (cart fragments, add-to-cart, product gallery,
 * checkout, etc.) is only actually needed on shop/product/cart/checkout/
 * account pages — but WooCommerce enqueues it on every single front-end
 * page by default. On the homepage or a plain content page like O nas, none
 * of it does anything (the homepage's product tiles use this theme's own
 * .product-card styling from style.css, not WooCommerce's), so it's pure
 * unused CSS/JS weight — exactly what PageSpeed's "unused CSS/JS" and
 * render-blocking audits were flagging.
 */
function papernest_is_commerce_page() {
	return class_exists( 'WooCommerce' ) && (
		is_shop() || is_product() || is_product_category() || is_product_tag() ||
		is_cart() || is_checkout() || is_account_page()
	);
}
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( papernest_is_commerce_page() ) {
			return;
		}
		foreach ( array( 'wc-cart-fragments', 'wc-add-to-cart', 'woocommerce', 'wc-single-product', 'wc-checkout', 'wc-cart', 'sourcebuster-js', 'wc-order-attribution' ) as $handle ) {
			wp_dequeue_script( $handle );
		}
		foreach ( array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'wc-blocks-style', 'wc-blocks-packages-style', 'wc-blocks-style-all-products', 'papernest-wc-theme' ) as $handle ) {
			wp_dequeue_style( $handle );
		}
	},
	100
);

// Dashicons is admin-toolbar iconography — irrelevant to anyone who isn't
// logged in, but loaded on every front-end page for everyone by default.
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! is_user_logged_in() ) {
			wp_deregister_style( 'dashicons' );
		}
	},
	100
);

/**
 * Preload the above-the-fold font files (Inter + Playfair Display) so the
 * browser fetches them immediately instead of discovering them only after
 * parsing fonts.css — cuts the delay before text renders in its final font,
 * which is what drives both LCP and the layout shift caused by the
 * fallback-to-webfont swap.
 *
 * Both the "latin" and "latin-ext" subsets are needed, not just latin: the
 * hero heading is Polish and contains diacritics (ą, ę, ć, ł, ń, ó, ś, ź,
 * ż), which live in the latin-ext Unicode range — a real Polish sentence
 * needs both files to render fully. Preloading only "latin" (the first
 * version of this fix) left latin-ext to be discovered the slow way, after
 * fonts.css downloaded and was parsed — PageSpeed's network dependency tree
 * showed it as the single longest link in the whole critical path (1067ms).
 */
add_action(
	'wp_head',
	function () {
		$fonts = array(
			'UcC73FwrK3iLTeHuS_nVMrMxCp50SjIa1ZL7.woff2',   // Inter, latin
			'UcC73FwrK3iLTeHuS_nVMrMxCp50SjIa25L7SUc.woff2', // Inter, latin-ext
			'nuFiD-vYSZviVYUb_rj3ij__anPXDTzYgA.woff2',      // Playfair Display, latin
			'nuFiD-vYSZviVYUb_rj3ij__anPXDTLYgFE_.woff2',    // Playfair Display, latin-ext
		);
		foreach ( $fonts as $font ) {
			printf(
				'<link rel="preload" href="%1$s/assets/fonts/%2$s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( PAPERNEST_URI ),
				esc_attr( $font )
			);
		}
	},
	1
);

/**
 * WordPress's own style.css (theme header only) isn't the design stylesheet,
 * so it doesn't need to load on the front end.
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * The site doesn't embed emoji as images (native emoji font rendering is
 * fine) or use WordPress's oEmbed auto-discovery — both add extra
 * render-blocking-ish script/link tags to every page for nothing.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * /llms.txt — an emerging convention (same idea as robots.txt, but a plain
 * Markdown summary for AI agents/crawlers) that PageSpeed's new "agentic
 * browsing" checks look for. Served virtually the same way WordPress itself
 * serves /robots.txt when there's no physical file — no filesystem access
 * to the site root is needed, and it always reflects the live site name/URL.
 */
add_action(
	'init',
	function () {
		add_rewrite_rule( '^llms\.txt$', 'index.php?papernest_llms_txt=1', 'top' );
	}
);
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'papernest_llms_txt';
		return $vars;
	}
);
// Without this, WordPress's own redirect_canonical() sees "/llms.txt" as an
// unrecognized path and 301s it to "/llms.txt/" before our handler below
// ever runs (the same reason WP core's is_robots() check exists for
// /robots.txt) — bypass it specifically for this one request.
add_filter(
	'redirect_canonical',
	function ( $redirect_url ) {
		return get_query_var( 'papernest_llms_txt' ) ? false : $redirect_url;
	}
);
add_action(
	'template_redirect',
	function () {
		if ( ! get_query_var( 'papernest_llms_txt' ) ) {
			return;
		}
		header( 'Content-Type: text/markdown; charset=utf-8' );
		echo "# " . get_bloginfo( 'name' ) . "\n\n"; // phpcs:ignore
		echo esc_html( get_bloginfo( 'description' ) ) . "\n\n";
		echo "> " . esc_html__( 'Producent wyrobów z papieru — wypełniacze papierowe, papier dla piskląt i tektura budowlana.', 'papernest' ) . "\n\n"; // phpcs:ignore
		echo "- [" . esc_html__( 'Sklep', 'papernest' ) . '](' . esc_url( papernest_shop_link() ) . ")\n"; // phpcs:ignore
		echo "- [" . esc_html__( 'O nas', 'papernest' ) . '](' . esc_url( home_url( '/o-nas/' ) ) . ")\n"; // phpcs:ignore
		echo "- [" . esc_html__( 'Kontakt', 'papernest' ) . '](' . esc_url( home_url( '/kontakt/' ) ) . ")\n"; // phpcs:ignore
		exit;
	}
);
/**
 * The rewrite rule above only takes effect after WordPress's rewrite rules
 * are flushed once — normally that happens on theme activation, but this
 * theme's already active on the live site, so flush on the very next load
 * after an update introduces a new rule instead of waiting for a manual
 * Wygląd > Bezpośrednie odnośniki > Zapisz.
 */
add_action(
	'init',
	function () {
		if ( get_option( 'papernest_llms_txt_rewrite_flushed' ) !== PAPERNEST_VERSION ) {
			flush_rewrite_rules();
			update_option( 'papernest_llms_txt_rewrite_flushed', PAPERNEST_VERSION );
		}
	},
	20
);

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
