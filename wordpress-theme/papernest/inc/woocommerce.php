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

/**
 * WooCommerce's "Order Attribution" tracking (sourcebuster-js +
 * wc-order-attribution) loads on every single front-end page — not just
 * checkout — to record marketing-source data ("how did this customer find
 * us") shown in WooCommerce Analytics. Nobody's using that report, and
 * PageSpeed flags both scripts among the render-blocking requests on every
 * page load. Dequeued site-wide. If the "Origin" column in Analytics >
 * Orders is ever wanted, this needs removing.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_dequeue_script( 'sourcebuster-js' );
		wp_dequeue_script( 'wc-order-attribution' );
	},
	20
);

/**
 * Product page "share with a friend" buttons -- WhatsApp, Facebook,
 * Messenger, e-mail. Hooks into WooCommerce's own woocommerce_share action
 * (single-product/share.php already calls do_action('woocommerce_share')
 * after the add-to-cart form; it's just unused by default since core
 * dropped its old AddThis integration years ago), so no template override
 * is needed and it always renders in the right spot even if WooCommerce
 * updates that template.
 *
 * All four are plain outbound links a browser opens directly -- no API
 * keys, no Facebook App ID, nothing that can start failing later. Messenger
 * uses its mobile deep link (fb-messenger://), which only does something on
 * a phone with Messenger installed; a real "Send to Messenger" dialog needs
 * a registered Facebook App ID, which the site doesn't have. On desktop the
 * button simply does nothing when clicked, same as tapping a WhatsApp link
 * with no WhatsApp installed.
 */
add_action( 'woocommerce_share', 'papernest_product_share_buttons', 10 );
function papernest_product_share_buttons() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$url     = get_permalink( $product->get_id() );
	$title   = $product->get_name();
	$message = $title . ' - ' . $url;

	$links = array(
		'whatsapp'  => array(
			'label' => 'WhatsApp',
			'href'  => 'https://wa.me/?text=' . rawurlencode( $message ),
		),
		'facebook'  => array(
			'label' => 'Facebook',
			'href'  => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url ),
		),
		'messenger' => array(
			'label' => 'Messenger',
			'href'  => 'fb-messenger://share/?link=' . rawurlencode( $url ),
		),
		'mail'      => array(
			'label' => 'E-mail',
			'href'  => 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $message ),
		),
	);
	?>
	<div class="product-share">
		<span class="product-share-label">Poleć znajomemu:</span>
		<div class="product-share-links">
			<?php
			// esc_url()'s default protocol whitelist doesn't include
			// fb-messenger:// (an app deep link, not a real URI scheme
			// browsers register) -- without passing it explicitly here,
			// esc_url() silently strips the whole Messenger href down to
			// an empty string instead of erroring, so the button would
			// just quietly do nothing when clicked.
			$allowed_protocols = array_merge( wp_allowed_protocols(), array( 'fb-messenger' ) );
			foreach ( $links as $key => $link ) :
				?>
				<a class="product-share-btn" href="<?php echo esc_url( $link['href'], $allowed_protocols ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $link['label'] ); ?>"><?php echo papernest_icon( $key ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

// Default shop/category sorting to price, low to high — instead of
// WooCommerce's own default (menu order) — until a customer picks a
// different option from the sorting dropdown themselves.
add_filter(
	'woocommerce_default_catalog_orderby',
	function () {
		return 'price';
	}
);

// WooCommerce ships the checkout phone field as optional by default -- the
// client needs a phone number on every order (delivery/courier contact), so
// it has to actually block checkout, not just be a suggestion. The site
// uses the newer block-based checkout, which reads this from its own
// option instead of the classic woocommerce_checkout_fields filter (kept
// below too, in case anything ever renders the classic checkout shortcode).
add_action(
	'init',
	function () {
		if ( get_option( 'woocommerce_checkout_phone_field' ) !== 'required' ) {
			update_option( 'woocommerce_checkout_phone_field', 'required' );
		}
	}
);
add_filter(
	'woocommerce_checkout_fields',
	function ( $fields ) {
		if ( isset( $fields['billing']['billing_phone'] ) ) {
			$fields['billing']['billing_phone']['required'] = true;
		}
		return $fields;
	}
);

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

/**
 * True on the shop/category grid (not single-product) -- used to widen the
 * header, hero band, and product grid all together there, matching the
 * client's "sklep na całą szerokość" request. Declared here rather than
 * inline in each spot since header.php also needs it.
 */
function papernest_is_wide_shop_page() {
	return is_shop() || is_product_category() || is_product_tag();
}

/**
 * Short tile label + sort weight from a sibling product's title, e.g.
 * "2 x Tektura budowlana ..." -> "2 szt", "Paleta 96 szt. - tektura ..." ->
 * "Paleta", "Tektura budowlana ..." (no prefix) -> "1 szt". Quantities on
 * this site aren't WooCommerce variations -- each is its own simple product,
 * named with this prefix convention (confirmed against the real catalog).
 */
function papernest_variant_tile_from_title( $title ) {
	$title = trim( $title );
	if ( 0 === stripos( $title, 'paleta' ) ) {
		return array(
			'label' => 'Paleta',
			'sort'  => PHP_INT_MAX,
		);
	}
	if ( preg_match( '/^(\d+)\s*x\s+/i', $title, $matches ) ) {
		return array(
			'label' => $matches[1] . ' szt',
			'sort'  => (int) $matches[1],
		);
	}
	return array(
		'label' => '1 szt',
		'sort'  => 1,
	);
}

/**
 * The most specific product category a product belongs to. Every product on
 * this site sits in a specific line category (e.g. "Tektura Budowlana") that
 * is itself a child of the broad "Nasze Produkty" umbrella shared by every
 * product line -- and some products are tagged with BOTH. Grouping quantity
 * siblings on any assigned category (including the umbrella) wrongly mixes
 * different product lines together, so only a childless (leaf) category
 * counts here.
 */
function papernest_product_variant_category_id( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return 0;
	}
	foreach ( $terms as $term ) {
		if ( ! get_term_children( $term->term_id, 'product_cat' ) ) {
			return $term->term_id;
		}
	}
	return 0;
}

/**
 * Quantity-variant quick links for a product's single-product page. Different
 * quantities of the same item aren't WooCommerce variations here -- they're
 * separate simple products (own page, own URL), grouped only by sharing one
 * specific (leaf) product category that contains nothing but those
 * quantities. So "the other quantities" = the other published products in
 * this product's own leaf category, sorted by quantity with Paleta last,
 * excluding the product being viewed.
 */
function papernest_product_variant_tiles( $product ) {
	if ( ! $product ) {
		return '';
	}
	$category_id = papernest_product_variant_category_id( $product->get_id() );
	if ( ! $category_id ) {
		return '';
	}
	$siblings = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $category_id,
				),
			),
		)
	);
	if ( count( $siblings ) < 2 ) {
		return '';
	}
	$tiles = array();
	foreach ( $siblings as $sibling ) {
		if ( $sibling->ID === $product->get_id() ) {
			continue;
		}
		$sibling_product = wc_get_product( $sibling->ID );
		if ( ! $sibling_product || ! $sibling_product->is_purchasable() ) {
			continue;
		}
		$tile    = papernest_variant_tile_from_title( $sibling->post_title );
		$tiles[] = array(
			'sort' => $tile['sort'],
			'html' => sprintf(
				'<a href="%1$s" class="variant-tile" title="%2$s">%3$s</a>',
				esc_url( get_permalink( $sibling->ID ) ),
				esc_attr( $sibling->post_title ),
				esc_html( $tile['label'] )
			),
		);
	}
	if ( empty( $tiles ) ) {
		return '';
	}
	usort(
		$tiles,
		function ( $a, $b ) {
			return $a['sort'] <=> $b['sort'];
		}
	);
	return '<div class="variant-tiles">' . implode( '', wp_list_pluck( $tiles, 'html' ) ) . '</div>';
}

function papernest_wc_wrapper_start() {
	$class = papernest_is_wide_shop_page() ? 'container container-wide-shop' : 'container';
	echo '<section class="section"><div class="' . esc_attr( $class ) . '">';
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

// Quantity-variant tiles right under the price (netto) on the single
// product page, before the "Masz pytania" contact block. Registered at the
// same priority as the core price template (10) so it lands right after it
// -- WordPress runs same-priority hooks in registration order, and this
// theme hook is always registered after WooCommerce's own.
add_action(
	'woocommerce_single_product_summary',
	function () {
		global $product;
		echo papernest_product_variant_tiles( $product ); // phpcs:ignore
	},
	10
);

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
 * "Ships today if ordered by X" line -- a well-known trust/urgency signal
 * on premium e-commerce sites. Pure text, computed from the server clock,
 * no request/script cost. $cutoff_hour is the only thing that should ever
 * need changing here (the actual dispatch cut-off time); everything else
 * (weekend handling, naming the right day) follows from it automatically.
 */
function papernest_shipping_estimate_text() {
	$cutoff_hour = 11;
	$now         = current_datetime();
	$is_weekday  = (int) $now->format( 'N' ) <= 5;
	$before_cutoff = (int) $now->format( 'G' ) < $cutoff_hour;

	if ( $is_weekday && $before_cutoff ) {
		return sprintf( 'Zamów dziś do godziny %d:00, a wyślemy jeszcze dzisiaj (nie dotyczy zamówień paletowych).', $cutoff_hour );
	}

	$ship_date = $now->modify( '+1 day' );
	while ( (int) $ship_date->format( 'N' ) > 5 ) {
		$ship_date = $ship_date->modify( '+1 day' );
	}

	if ( $ship_date->format( 'Y-m-d' ) === $now->modify( '+1 day' )->format( 'Y-m-d' ) ) {
		return 'Zamów teraz, a wyślemy jutro (nie dotyczy zamówień paletowych).';
	}

	$days_pl = array(
		1 => 'w poniedziałek',
		2 => 'we wtorek',
		3 => 'w środę',
		4 => 'w czwartek',
		5 => 'w piątek',
	);
	return 'Zamów teraz, a wyślemy ' . ( $days_pl[ (int) $ship_date->format( 'N' ) ] ?? '' ) . ' (nie dotyczy zamówień paletowych).';
}
add_action(
	'woocommerce_single_product_summary',
	function () {
		?>
		<p class="shipping-estimate"><?php echo papernest_icon( 'clock' ); ?> <?php echo esc_html( papernest_shipping_estimate_text() ); ?></p>
		<?php
	},
	36
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
		papernest_breadcrumb_schema(
			array(
				array( 'name' => 'Strona główna', 'url' => home_url( '/' ) ),
				array( 'name' => 'Sklep', 'url' => get_permalink( wc_get_page_id( 'shop' ) ) ),
				array( 'name' => get_the_title(), 'url' => null ),
			)
		);
		return;
	}
	?>
	<section class="page-hero">
		<div class="container container-wide-shop">
			<div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Sklep</span></div>
			<h1><?php woocommerce_page_title(); ?></h1>
			<?php if ( is_shop() && ! is_search() ) : ?>
				<p>Papier w&nbsp;rolkach prosto od producenta — wypełniacz do paczek, papier dla piskląt i&nbsp;tektura budowlana.</p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	papernest_breadcrumb_schema(
		array(
			array( 'name' => 'Strona główna', 'url' => home_url( '/' ) ),
			array( 'name' => 'Sklep', 'url' => null ),
		)
	);
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
 * "Recently viewed" strip -- purely client-side (localStorage), so it costs
 * nothing on the server and nothing on pages where a visitor hasn't viewed
 * any products yet (main.js just leaves the container hidden). The current
 * product's data goes out as a data-attribute on the container itself
 * rather than a separate wp_localize_script call, since it's only ever
 * needed by the one script that already has this element in hand.
 */
add_action(
	'woocommerce_after_single_product_summary',
	function () {
		global $product;
		if ( ! $product ) {
			return;
		}
		$image_id  = $product->get_image_id();
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : wc_placeholder_img_src( 'medium' );
		?>
		<section class="section-tight recently-viewed-section" data-recently-viewed hidden
			data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
			data-product-name="<?php echo esc_attr( $product->get_name() ); ?>"
			data-product-url="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>"
			data-product-image="<?php echo esc_url( $image_url ); ?>"
			data-product-price="<?php echo esc_attr( wp_strip_all_tags( $product->get_price_html() ) ); ?>">
			<div class="container">
				<div class="section-head">
					<span class="eyebrow">Historia przeglądania</span>
					<h2>Ostatnio oglądane</h2>
				</div>
				<div class="recently-viewed-grid"></div>
			</div>
		</section>
		<?php
	},
	25
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
