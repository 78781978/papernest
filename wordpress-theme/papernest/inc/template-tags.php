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
			// Registered right above (220x150, uncropped) instead of 'full' —
			// the logo never displays above 180px tall, so requesting the
			// original upload's own resolution (whatever a client happens to
			// upload, e.g. 600x400) served that many more pixels than needed
			// at every single size down to 44px on scrolled mobile.
			'papernest-logo',
			false,
			array(
				'class'         => $classes,
				'loading'       => 'eager',
				'decoding'      => 'async',
				'fetchpriority' => 'high',
				// wp_get_attachment_image() always builds a full srcset (every
				// registered size up to the original), but without an explicit
				// `sizes` override it guesses one from the requested size's own
				// width, telling browsers this logo might render much wider
				// than it ever does (180px, on desktop before scrolling) and
				// so picking a needlessly large srcset candidate.
				'sizes'         => '180px',
			)
		);
		if ( $img ) {
			return $img;
		}
	}
	return sprintf(
		'<img class="%1$s" src="%2$s/assets/img/logo/papernest-lockup.png" alt="PaperNest — Producent wyrobów z papieru" width="288" height="192" loading="eager">',
		esc_attr( $classes ),
		PAPERNEST_URI
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
		if ( $priority ) {
			// The hero image is the page's Largest Contentful Paint element —
			// loading="lazy" was making the browser defer even discovering it,
			// which PageSpeed measured as ~2.4s of pure added delay on mobile
			// (990ms render delay + 1380ms load delay). It's the first thing
			// visible on the page, so it must load eagerly and with priority
			// instead of being treated like a below-the-fold image.
			printf(
				'<img src="%1$s" alt="%2$s" loading="eager" fetchpriority="high">',
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
