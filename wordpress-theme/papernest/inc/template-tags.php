<?php
/**
 * Shared markup helpers: inline icons, decorative illustrations, contact info,
 * and photo slots — ported 1:1 from the static prototype's tools/build.py so
 * every page keeps the exact same markup/CSS hooks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_icons() {
	static $icons = null;
	if ( null !== $icons ) {
		return $icons;
	}
	$icons = array(
		'phone'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5.5c0-1.1.9-2 2-2h2.1c.5 0 1 .4 1.1.9l.9 3.7c.1.4 0 .9-.3 1.2L7.4 10.7a13.7 13.7 0 0 0 5.9 5.9l1.4-1.4c.3-.3.8-.4 1.2-.3l3.7.9c.5.1.9.6.9 1.1V19c0 1.1-.9 2-2 2h-1C9.8 21 3 14.2 3 6.5v-1Z"/></svg>',
		'mail'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5h17v11h-17z"/><path d="m4 7 8 6 8-6"/></svg>',
		'pin'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.6 7-11.5A7 7 0 0 0 5 9.5C5 14.4 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>',
		'clock'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/></svg>',
		'facebook'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8.5h-2c-.8 0-1.5.7-1.5 1.5v2h3.4l-.4 3h-3v7.5h-3V15h-2v-3h2v-2.2C8.5 7 10 5.5 12.6 5.5H15v3Z"/></svg>',
		'cart'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="10" cy="21" r="1.3"/><circle cx="17" cy="21" r="1.3"/></svg>',
		'user'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.4 4.3-5 7.5-5s6.1 1.6 7.5 5"/></svg>',
		'search'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m20 20-4.3-4.3"/></svg>',
		'check'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9.5 18 20 6"/></svg>',
		'arrow'      => '<svg class="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		'star'       => '<svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1 5.9L10 14.9l-5.2 2.8 1-5.9L1.5 7.7l5.9-.8L10 1.5Z"/></svg>',
		'truck'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v9H3z"/><path d="M14 11h4l3 3v2h-7z"/><circle cx="7.5" cy="18" r="1.6"/><circle cx="17.5" cy="18" r="1.6"/></svg>',
		'shield'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.7-7 9-4-1.3-7-4.5-7-9V6l7-3Z"/><path d="m9 12 2 2 4-4.2"/></svg>',
		'factory'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V11l5 3.2V11l5 3.2V8l6 4v9H3Z"/><path d="M7 21v-4M12 21v-4M17 21v-4"/></svg>',
		'leaf'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c9 0 14-5 14-14-9 0-14 5-14 14Z"/><path d="M5 19c0-5 3-8 8-9"/></svg>',
		'chevronUp'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 15 6-6 6 6"/></svg>',
		'reel'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="3"/><path d="M12 3.5V6M12 18v2.5M3.5 12H6M18 12h2.5"/></svg>',
		'box'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8.5 12 4l9 4.5-9 4.5-9-4.5Z"/><path d="M3 8.5V17l9 4.5 9-4.5V8.5"/><path d="M12 13v8.5"/></svg>',
		'scissors'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2.4"/><circle cx="6" cy="18" r="2.4"/><path d="M20 4 7.6 15.6M8 8.4 20 20"/></svg>',
		'egg'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c4 0 6.5-3.4 6.5-7.6C18.5 8 15 3 12 3S5.5 8 5.5 13.4C5.5 17.6 8 21 12 21Z"/></svg>',
		'hammer'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m14.5 6.5 3 3-8 8-3.5.5.5-3.5 8-8Z"/><path d="M13 4l3 3M4 20l3.5-3.5"/></svg>',
		'sparkle'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/></svg>',
		'trend'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17 9.5 10.5 14 15 21 7"/><path d="M15 7h6v6"/></svg>',
		'gift'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v4H3z"/><path d="M5 13h14v8H5zM12 9v12"/><path d="M12 9c-1.5 0-3.5-.6-3.5-2.5S10 4 12 6c0-2 2-3 3.5-1.5S13.5 9 12 9Z"/></svg>',
		'palette'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a9 8 0 1 0 0 16c1.4 0 2-1 2-2s-.6-1.4-1-2c-.5-.7 0-2 1.3-2H16a4 4 0 0 0 4-4c0-3.3-3.6-6-8-6Z"/><circle cx="8" cy="11" r="1"/><circle cx="12" cy="8" r="1"/><circle cx="16" cy="11" r="1"/></svg>',
		'wrench'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5l-6 6L6 20l2.7-2.7 6-6a4 4 0 0 0 5-5.4l-2.8 2.8-2-2 2.8-2.8Z"/></svg>',
		'vet'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 8V4M15 8V4M9 4h0M15 4h0"/><path d="M7 8c0 3 1.5 4.5 5 4.5S17 11 17 8"/><path d="M12 12.5V17"/><circle cx="12" cy="19" r="2"/></svg>',
		'die'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h.01M15 9h.01M9 15h.01M15 15h.01M12 12h.01"/></svg>',
		'layers'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5M3 8l9 5 9-5"/></svg>',
		'blueprint'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h13l3 3v13H4Z"/><path d="M8 9h6M8 13h9M8 17h6"/></svg>',
		'pallet'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18M3 13h18"/><path d="M5 9v9M12 9v9M19 9v9M3 18h18"/></svg>',
		'minus'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M5 12h14"/></svg>',
		'plus'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>',
		'info'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5.5M12 8v.01"/></svg>',
		'image'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="15" rx="2"/><circle cx="8.5" cy="10" r="1.6"/><path d="m4 17 5-5 3.5 3.5L17 11l3 3.5"/></svg>',
		'pause'      => '<svg viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4.5" width="4.5" height="15" rx="1.2"/><rect x="13.5" y="4.5" width="4.5" height="15" rx="1.2"/></svg>',
		'play'       => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 4.8v14.4c0 .9 1 1.5 1.8 1L19 13.4c.7-.5.7-1.5 0-2L8.8 3.8c-.8-.5-1.8.1-1.8 1Z"/></svg>',
		'whatsapp'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.5 2 2 6.5 2 12c0 1.8.5 3.5 1.3 5L2 22l5.2-1.3c1.5.8 3.1 1.3 4.8 1.3 5.5 0 10-4.5 10-10S17.5 2 12 2Zm0 18.2c-1.6 0-3.1-.4-4.4-1.2l-.3-.2-3.1.8.8-3-.2-.3C4 14.9 3.6 13.5 3.6 12c0-4.6 3.8-8.4 8.4-8.4s8.4 3.8 8.4 8.4-3.8 8.2-8.4 8.2Zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.3-.7.8-.8 1-.2.2-.3.2-.5.1-.3-.1-1.1-.4-2.1-1.3-.8-.7-1.3-1.6-1.4-1.8-.2-.3 0-.4.1-.5.1-.1.3-.3.4-.5.1-.1.2-.3.2-.4.1-.2 0-.4 0-.5S9.7 8 9.5 7.5c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.2.3-.9.9-.9 2.1s.9 2.5 1.1 2.6c.1.2 1.8 2.8 4.4 3.8.6.3 1.1.4 1.5.5.6.2 1.2.2 1.6.1.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.1-.2-.2-.5-.3Z"/></svg>',
		'messenger'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.4 2 2 6.1 2 11.4c0 2.9 1.3 5.4 3.4 7.2v3.4l3.1-1.7c.8.2 1.7.4 2.5.4 5.6 0 10-4.1 10-9.3S17.6 2 12 2Zm.9 12.5-2.5-2.7-5 2.7 5.5-5.9 2.6 2.7 5-2.7-5.6 5.9Z"/></svg>',
	);
	return $icons;
}

/**
 * Decorative icon — aria-hidden because it always sits beside visible text
 * (WCAG 1.1.1: avoid duplicating that text for screen readers).
 */
function papernest_icon( $name, $class = '' ) {
	$icons = papernest_icons();
	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}
	$svg   = $icons[ $name ];
	$attrs = 'aria-hidden="true" focusable="false"';
	if ( $class ) {
		$attrs = 'class="' . esc_attr( $class ) . '" ' . $attrs;
	}
	return str_replace( '<svg ', '<svg ' . $attrs . ' ', $svg );
}

/**
 * Large decorative illustration from assets/img/illustrations — purely
 * decorative, the section already has adjacent text (WCAG 1.1.1).
 */
function papernest_svg_file( $path ) {
	static $cache = array();
	if ( isset( $cache[ $path ] ) ) {
		return $cache[ $path ];
	}
	$file = PAPERNEST_DIR . '/assets/img/illustrations/' . $path;
	if ( ! file_exists( $file ) ) {
		return '';
	}
	$svg = file_get_contents( $file );
	$svg = preg_replace( '/<svg /', '<svg aria-hidden="true" focusable="false" ', $svg, 1 );
	$cache[ $path ] = $svg;
	return $svg;
}

function papernest_reveal( $content, $extra_class = '' ) {
	return '<div class="reveal ' . esc_attr( $extra_class ) . '">' . papernest_no_orphans( $content ) . '</div>';
}

/**
 * Fallback nav (used only until the client sets up Wygląd > Menu in wp-admin).
 */
function papernest_default_nav() {
	$items = array(
		home_url( '/' )                    => 'Home',
		papernest_shop_link()              => 'Sklep',
		papernest_page_link( 'o-nas' )     => 'O Nas',
		papernest_page_link( 'portfolio' ) => 'Portfolio',
		papernest_page_link( 'kontakt' )   => 'Kontakt',
	);
	$blog_page_id = (int) get_option( 'page_for_posts' );
	if ( $blog_page_id ) {
		$items[ get_permalink( $blog_page_id ) ] = 'Blog';
	}
	echo '<ul>';
	foreach ( $items as $url => $label ) {
		printf( '<li><a href="%1$s">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

function papernest_shop_link() {
	if ( class_exists( 'WooCommerce' ) ) {
		return get_permalink( wc_get_page_id( 'shop' ) );
	}
	return papernest_page_link( 'sklep' );
}

/* ------------------------------------------------------------- Contact info ---- */

function papernest_phone_display() {
	return get_theme_mod( 'papernest_phone', '538 989 005' );
}

function papernest_phone_tel() {
	$digits = preg_replace( '/\D+/', '', papernest_phone_display() );
	return 'tel:+48' . $digits;
}

function papernest_email() {
	return get_theme_mod( 'papernest_email', 'gd@papernest.pl' );
}

function papernest_address_line() {
	return get_theme_mod( 'papernest_address', 'I Brygady Legionów 12-14, 72-100 Goleniów' );
}

function papernest_hours() {
	return get_theme_mod( 'papernest_hours', 'Pon–Pt 8:00–16:00' );
}

function papernest_contact_person() {
	return get_theme_mod( 'papernest_contact_person', 'Grzegorz Działkowski' );
}

function papernest_facebook_url() {
	return get_theme_mod( 'papernest_facebook', 'https://pl-pl.facebook.com/Papernestapp/' );
}

/**
 * Permalink for one of the theme's demo-created pages, found by slug so
 * footer/legal links keep working no matter the permalink structure.
 */
function papernest_page_link( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * The client's full logo lockup (mark + name + contact info). Returns just the
 * <img> (no wrapping link — callers wrap it in their own <a class="brand">),
 * using WordPress's built-in custom-logo image when the client has uploaded
 * one via Customizer > Site Identity, otherwise the bundled default.
 */
function papernest_logo( $class = '' ) {
	$classes   = trim( 'brand-logo ' . $class );
	$logo_id   = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$img = wp_get_attachment_image(
			$logo_id,
			// Registered right above (540x360, uncropped) instead of 'full' —
			// the logo never displays above 270px wide (180px tall at its
			// CSS-forced 3:2 ratio) even on desktop, so requesting the
			// original upload's own resolution (whatever a client happens to
			// upload, e.g. 600x400) served more pixels than needed at every
			// single size down to 66px on scrolled mobile.
			'papernest-logo',
			false,
			array(
				'class'         => $classes,
				'loading'       => 'eager',
				'decoding'      => 'async',
				'fetchpriority' => 'high',
				// LiteSpeed Cache's lazy-load rewriting still intercepts this
				// image regardless of loading="eager" (it rewrites the final
				// HTML output after the theme renders it, with no way to know
				// this is above-the-fold unless told explicitly) — data-no-lazy
				// is its documented attribute for opting an image out.
				'data-no-lazy'  => '1',
				// wp_get_attachment_image() always builds a full srcset (every
				// registered size up to the original), but without an explicit
				// `sizes` override it guesses one from the requested size's own
				// width. The real display width is 270px on desktop but only
				// 96px below the 1200px breakpoint where the header shrinks
				// (see .brand-logo in style.css) — a flat "180px" hint had
				// mobile picking a candidate sized for neither.
				'sizes'         => '(max-width: 1200px) 96px, 270px',
			)
		);
		if ( $img ) {
			return $img;
		}
	}
	return sprintf(
		'<img class="%1$s" src="%2$s/assets/img/logo/papernest-lockup.png" alt="PaperNest — Producent wyrobów z papieru" width="288" height="192" loading="eager" data-no-lazy="1">',
		esc_attr( $classes ),
		PAPERNEST_URI
	);
}

/**
 * The round footer badge (papernest_footer_logo_round Customizer field)
 * stores a raw uploaded URL, not an attachment ID, so — same issue as
 * papernest_illustration()/papernest_photo_slot() — a plain <img src="...">
 * was serving whatever resolution the client uploaded (e.g. a 512x512
 * favicon-style mark) to a badge that never renders above 64px. Resolves it
 * back to an attachment and uses WP's own 'thumbnail' size (150x150,
 * cropped) — already generated for every upload regardless of when this
 * field was added, so no backfill hook is needed here the way the header
 * logo needed one. Falls back to the raw <img> if the URL doesn't resolve.
 */
function papernest_footer_round_logo( $url ) {
	$attachment_id = attachment_url_to_postid( $url );
	if ( $attachment_id ) {
		$img = wp_get_attachment_image(
			$attachment_id,
			'thumbnail',
			false,
			array(
				'class'        => 'brand-logo footer-logo',
				'alt'          => 'PaperNest',
				'loading'      => 'eager',
				'decoding'     => 'async',
				'data-no-lazy' => '1',
			)
		);
		if ( $img ) {
			return $img;
		}
	}
	return sprintf(
		'<img class="brand-logo footer-logo" src="%1$s" alt="PaperNest" loading="eager" data-no-lazy="1">',
		esc_url( $url )
	);
}

/**
 * Fixed contact block shown on every product page under the price —
 * matches the static prototype's product_contact_block().
 */
function papernest_theme_product_contact_block() {
	ob_start();
	?>
	<div class="product-contact">
		<p>Masz pytania lub potrzebujesz większego zamówienia?<br>Skontaktuj się z nami:</p>
		<p class="pc-name"><?php echo esc_html( papernest_contact_person() ); ?></p>
		<a class="pc-line" href="<?php echo esc_url( papernest_phone_tel() ); ?>"><?php echo papernest_icon( 'phone' ); ?>tel. <?php echo esc_html( papernest_phone_display() ); ?></a>
		<a class="pc-line" href="mailto:<?php echo esc_attr( papernest_email() ); ?>"><?php echo papernest_icon( 'mail' ); ?><?php echo esc_html( papernest_email() ); ?></a>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Large decorative illustration (hero, "O nas", industrial-services art) —
 * renders the client-uploaded Customizer image if one has been set,
 * otherwise falls back to the theme's own built-in SVG artwork, so these
 * spots stay swappable without needing a placeholder state (unlike
 * papernest_photo_slot(), the default here is never empty).
 */
function papernest_illustration( $mod_key, $svg_file, $label = '', $priority = false ) {
	$image_url = get_theme_mod( $mod_key, '' );
	if ( $image_url ) {
		// These slots store a raw uploaded URL (WP_Customize_Image_Control),
		// not an attachment ID, so a plain <img src="..."> served whatever
		// resolution the client happened to upload — one hero photo was
		// 750x1024 natively and got served at that size to every visitor
		// regardless of how small the frame actually renders on a phone.
		// Resolving the real attachment lets wp_get_attachment_image() build
		// its normal srcset (thumbnail/medium/etc.) so mobile browsers can
		// pick a genuinely small candidate instead. Falls back to the raw
		// <img> exactly as before if the URL doesn't resolve to a local
		// attachment (e.g. hotlinked image, or the attachment was deleted).
		$attachment_id = attachment_url_to_postid( $image_url );
		if ( $attachment_id ) {
			$attrs = $priority
				? array(
					'alt'           => $label,
					'sizes'         => '(max-width: 980px) 90vw, 600px',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					// LiteSpeed Cache's lazy-load rewriting still intercepts
					// this image regardless of loading="eager" (it rewrites
					// the final HTML output after the theme renders it, with
					// no way to know this is above-the-fold unless told
					// explicitly) — data-no-lazy is its documented opt-out.
					'data-no-lazy'  => '1',
				)
				: array(
					'alt'     => $label,
					'sizes'   => '(max-width: 980px) 90vw, 600px',
					'loading' => 'lazy',
				);
			$img = wp_get_attachment_image( $attachment_id, 'large', false, $attrs );
			if ( $img ) {
				echo $img; // phpcs:ignore -- wp_get_attachment_image() already escapes.
				return;
			}
		}
		if ( $priority ) {
			// The hero image is the page's Largest Contentful Paint element —
			// loading="lazy" was making the browser defer even discovering it,
			// which PageSpeed measured as ~2.4s of pure added delay on mobile
			// (990ms render delay + 1380ms load delay). It's the first thing
			// visible on the page, so it must load eagerly and with priority
			// instead of being treated like a below-the-fold image.
			printf(
				// data-no-lazy="1" is LiteSpeed Cache's documented opt-out for
				// its own lazy-load rewriting, which otherwise still intercepts
				// this image regardless of loading="eager" — LSCache rewrites
				// image markup in the final HTML output after the theme has
				// already rendered it, so it has no way to know this is the
				// LCP element unless told explicitly.
				'<img src="%1$s" alt="%2$s" loading="eager" fetchpriority="high" data-no-lazy="1">',
				esc_url( $image_url ),
				esc_attr( $label )
			);
		} else {
			printf(
				'<img src="%1$s" alt="%2$s" loading="lazy">',
				esc_url( $image_url ),
				esc_attr( $label )
			);
		}
		return;
	}
	echo papernest_svg_file( $svg_file ); // phpcs:ignore
}

/**
 * A named photo slot: renders the client-uploaded Customizer image if one has
 * been set, otherwise the same "reserved photo spot" placeholder markup the
 * static prototype used, so it's obvious in wp-admin where to add a picture.
 */
function papernest_photo_slot( $mod_key, $label, $class = '' ) {
	$image_url = get_theme_mod( $mod_key, '' );
	if ( $image_url ) {
		// Same reasoning as papernest_illustration() above: these slots store
		// a raw URL, not an attachment ID, so resolve it back to one where
		// possible so wp_get_attachment_image() can build a real srcset
		// instead of serving whatever resolution was uploaded to everyone.
		$attachment_id = attachment_url_to_postid( $image_url );
		if ( $attachment_id ) {
			$img = wp_get_attachment_image(
				$attachment_id,
				'large',
				false,
				array(
					'alt'     => $label,
					'sizes'   => '(max-width: 780px) 90vw, 500px',
					'loading' => 'lazy',
				)
			);
			if ( $img ) {
				printf( '<div class="photo-slot photo-slot-filled %1$s">%2$s</div>', esc_attr( $class ), $img ); // phpcs:ignore
				return;
			}
		}
		printf(
			'<div class="photo-slot photo-slot-filled %1$s"><img src="%2$s" alt="%3$s" loading="lazy"></div>',
			esc_attr( $class ),
			esc_url( $image_url ),
			esc_attr( $label )
		);
		return;
	}
	printf(
		'<div class="photo-slot %1$s"><span class="photo-slot-ic">%2$s</span><span class="photo-slot-label">%3$s</span></div>',
		esc_attr( $class ),
		papernest_icon( 'image' ),
		esc_html( $label )
	);
}
