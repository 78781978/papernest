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

/**
 * Product category pills for the shop toolbar — replaces the earlier
 * sidebar-style category list (too narrow a content column for a
 * comfortable product grid); this renders inline in .shop-filters instead.
 */
function papernest_shop_category_filters() {
	// childless => true skips umbrella/parent categories (e.g. a "Nasze
	// Produkty" wrapper containing the real categories as its children) —
	// "Wszystkie produkty" already covers that, so listing the parent too
	// is a redundant near-duplicate link.
	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
			'childless'  => true,
		)
	);
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return;
	}
	?>
	<a class="filter-chip<?php echo is_shop() ? ' active' : ''; ?>" href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Wszystkie produkty</a>
	<?php foreach ( $categories as $cat ) : ?>
		<a class="filter-chip<?php echo is_tax( 'product_cat', $cat->slug ) ? ' active' : ''; ?>" href="<?php echo esc_url( get_term_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
	<?php endforeach; ?>
	<?php
}

// The static prototype's cols-4 assumed a full-width grid; once this theme
// added the category sidebar (papernest_shop_category_sidebar() above), the
// content column is narrower, and 4 columns made cards too cramped for their
// price/button/title. 3 columns matches the space actually available.
add_filter(
	'loop_shop_columns',
	function () {
		return 3;
	}
);
add_filter(
	'woocommerce_product_loop_start',
	function ( $html ) {
		return '<div class="product-grid reveal-stagger">';
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
	echo '<div class="shop-toolbar"><div class="shop-filters">';
	papernest_shop_category_filters();
	echo '</div><div class="shop-sort">Sortuj: ';
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
				<p>Papier w&nbsp;rolkach prosto od producenta — wypełniacz do paczek, papier dla piskląt i&nbsp;tektura budowlana.</p>
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
		// WC's own arg key is posts_per_page, not posts_count — the wrong
		// key silently did nothing and left the default of 4 in place.
		$args['posts_per_page'] = 3;
		$args['columns']        = 3;
		return $args;
	}
);

/**
 * Legally-required "obligation to pay" wording on the checkout submit button
 * (Art. 12 ust. 1 pkt 21 ustawy o prawach konsumenta). Covers the classic
 * checkout template.
 */
add_filter(
	'woocommerce_order_button_text',
	function () {
		return 'Zamawiam z obowiązkiem zapłaty';
	}
);

/**
 * Checkout consent text (classic template): hyperlink Regulamin and
 * Polityka Prywatności instead of leaving them as plain text.
 */
add_filter(
	'woocommerce_get_privacy_policy_text',
	function ( $text, $type ) {
		if ( 'checkout' !== $type ) {
			return $text;
		}

		$regulamin = get_page_by_path( 'regulamin' );
		$polityka  = get_page_by_path( 'polityka-prywatnosci' );

		$terms_link   = $regulamin
			? '<a href="' . esc_url( get_permalink( $regulamin ) ) . '" target="_blank" rel="noopener">Warunkami i&nbsp;zasadami</a>'
			: 'Warunkami i&nbsp;zasadami';
		$privacy_link = $polityka
			? '<a href="' . esc_url( get_permalink( $polityka ) ) . '" target="_blank" rel="noopener">Polityką Prywatności</a>'
			: 'Polityką Prywatności';

		return 'Kontynuując zamówienie wyrażasz zgodę na nasze ' . $terms_link . ' oraz ' . $privacy_link . '.';
	},
	10,
	2
);

/**
 * The checkout page uses the WooCommerce Checkout block, which links its
 * own "terms" text to whatever pages are configured as the Terms and
 * Conditions / Privacy Policy pages. Point those at our real Regulamin and
 * Polityka Prywatności pages so the block's built-in text becomes clickable
 * instead of two words with no target.
 */
add_action(
	'init',
	function () {
		$regulamin = get_page_by_path( 'regulamin' );
		if ( $regulamin && (int) get_option( 'woocommerce_terms_page_id' ) !== $regulamin->ID ) {
			update_option( 'woocommerce_terms_page_id', $regulamin->ID );
		}

		$polityka = get_page_by_path( 'polityka-prywatnosci' );
		if ( $polityka && (int) get_option( 'wp_page_for_privacy_policy' ) !== $polityka->ID ) {
			update_option( 'wp_page_for_privacy_policy', $polityka->ID );
		}
	}
);

/**
 * A marketing-emails opt-in checkbox on checkout (from a plugin, not this
 * theme) renders untranslated English text. Translate it wherever it's
 * rendered server-side via PHP.
 */
add_filter(
	'gettext',
	function ( $translated, $original ) {
		if ( 'I would like to receive exclusive emails with discounts and product information' === $original ) {
			return 'Chcę otrzymywać ekskluzywne wiadomości e-mail z rabatami i informacjami o produktach';
		}
		return $translated;
	},
	10,
	2
);

/**
 * The Checkout block's "Place order" button label is a JS-translated
 * string, not something a PHP filter can reach — WooCommerce's own Polish
 * translation renders it as "Kupuję i płacę", which doesn't meet the legal
 * "obligation to pay" wording. Rewrite it client-side once the block
 * mounts (it renders asynchronously, so a MutationObserver is needed).
 * The same pass also catches the marketing opt-in checkbox text above, in
 * case that plugin renders it client-side rather than via PHP.
 */
add_action(
	'wp_footer',
	function () {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		?>
		<script>
		(function () {
			var BUTTON_LABEL = 'Zamawiam z obowiązkiem zapłaty';
			var TEXT_MAP = {
				'I would like to receive exclusive emails with discounts and product information':
					'Chcę otrzymywać ekskluzywne wiadomości e-mail z rabatami i informacjami o produktach'
			};

			function fixButton() {
				document.querySelectorAll( '.wc-block-components-checkout-place-order-button__text' ).forEach( function ( el ) {
					if ( el.textContent.trim() !== BUTTON_LABEL ) {
						el.textContent = BUTTON_LABEL;
					}
				} );
			}

			function fixTextNodes( root ) {
				var walker = document.createTreeWalker( root, NodeFilter.SHOW_TEXT );
				var node;
				while ( ( node = walker.nextNode() ) ) {
					var text = node.textContent.trim();
					if ( TEXT_MAP[ text ] ) {
						node.textContent = node.textContent.replace( text, TEXT_MAP[ text ] );
					}
				}
			}

			function run() {
				fixButton();
				fixTextNodes( document.body );
			}

			var scheduled = false;
			function scheduleRun() {
				if ( scheduled ) {
					return;
				}
				scheduled = true;
				requestAnimationFrame( function () {
					scheduled = false;
					run();
				} );
			}

			run();
			new MutationObserver( scheduleRun ).observe( document.body, { childList: true, subtree: true } );
		})();
		</script>
		<?php
	}
);
