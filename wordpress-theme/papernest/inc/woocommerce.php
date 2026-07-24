<?php
/**
 * WooCommerce integration: theme wrapper hooks, grid columns, and small
 * output tweaks so shop/product pages use the same container/header/footer
 * as the rest of the site. WooCommerce's own default stylesheet keeps
 * providing correct layout/forms; assets/css/wc-theme.css re-skins it to
 * match the PaperNest brand (colors, buttons, spacing) on top of that.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Theme wraps shop/product content in the same .section > .container used everywhere else.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// The design has no sidebar anywhere else on the site; without this,
// WooCommerce falls back to its own bundled global/sidebar.php template,
// which renders WordPress's default unstyled Search/Pages/Archives/
// Categories widgets on every shop/product page.
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
add_action( 'woocommerce_before_main_content', 'papernest_wc_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'papernest_wc_wrapper_end', 10 );

function papernest_wc_wrapper_start() {
	echo '<section class="section"><div class="container">';
}
function papernest_wc_wrapper_end() {
	echo '</div></section>';
}

// 4 products per row on the shop grid, matching the static prototype's cols-4.
add_filter(
	'loop_shop_columns',
	function () {
		return 4;
	}
);
add_filter(
	'woocommerce_product_loop_start',
	function ( $html ) {
		return '<div class="product-grid cols-4 reveal-stagger">';
	}
);
add_filter(
	'woocommerce_product_loop_end',
	function ( $html ) {
		return '</div>';
	}
);

// Drop WooCommerce's own breadcrumb — page-hero already shows one, and result page.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Fixed contact block under the price on every single product page.
add_action(
	'woocommerce_single_product_summary',
	function () {
		echo papernest_theme_product_contact_block();
	},
	11
);

// Trust row (shipping / safe payment / recycling) under add-to-cart, matching the static prototype.
add_action(
	'woocommerce_single_product_summary',
	function () {
		?>
		<div class="trust-row">
			<div class="item"><?php echo papernest_icon( 'truck' ); ?> Wysyłka InPost / DPD</div>
			<div class="item"><?php echo papernest_icon( 'shield' ); ?> Bezpieczna płatność</div>
			<div class="item"><?php echo papernest_icon( 'leaf' ); ?> 100% recykling</div>
		</div>
		<?php
	},
	35
);

/**
 * The shop toolbar (filters + sort) is WooCommerce's own
 * woocommerce_before_shop_loop output (ordering dropdown + result count);
 * we just wrap it to keep the .shop-toolbar visual style.
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_before_shop_loop', 'papernest_shop_toolbar_start', 15 );
add_action( 'woocommerce_before_shop_loop', 'papernest_shop_toolbar_end', 31 );

function papernest_shop_toolbar_start() {
	echo '<div class="shop-toolbar"><div class="shop-filters"></div><div class="shop-sort">Sortuj: ';
}
function papernest_shop_toolbar_end() {
	woocommerce_catalog_ordering();
	echo '</div></div>';
}

/**
 * Page-hero band (crumbs + heading) on shop/category/single-product pages,
 * matching the .page-hero used by every other page in the theme.
 */
add_action( 'woocommerce_before_main_content', 'papernest_wc_page_hero', 4 );

function papernest_wc_page_hero() {
	if ( is_product() ) {
		global $product;
		?>
		<section class="page-hero" style="padding-block:40px 32px">
			<div class="container">
				<div class="crumbs">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span>
					<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Sklep</a> <span>/</span>
					<span><?php the_title(); ?></span>
				</div>
			</div>
		</section>
		<?php
		return;
	}
	?>
	<section class="page-hero">
		<div class="container">
			<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Sklep</span></div>
			<h1><?php woocommerce_page_title(); ?></h1>
			<?php if ( is_shop() && ! is_search() ) : ?>
				<p>Papier w rolkach prosto od producenta — wypełniacz do paczek, papier dla piskląt i tektura budowlana.</p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Number of related products, matching the static prototype's "Zobacz też".
 */
add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_count'] = 3;
		$args['columns']     = 3;
		return $args;
	}
);
