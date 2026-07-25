<?php
/**
 * Polish typesetting convention: a single-letter word (i, a, o, u, w, z —
 * "and", "and", "about", "at", "in", "with/from") must never end up alone
 * at the end of a line ("sierotka" / orphan). Glues each one to the word
 * that follows with a non-breaking space instead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_no_orphans( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}
	$nbsp = "\xc2\xa0"; // UTF-8 non-breaking space.
	// A single-letter word preceded by whitespace/start-of-string and
	// followed by whitespace, not already glued to something.
	$text = preg_replace( '/(^|[\s(>])([aiouwzAIOUWZ])[ \t]+/u', '$1$2' . $nbsp, $text );
	return $text;
}

/**
 * Turns a plain phone number or email address written in body text (e.g. a
 * product's "Zapytaj o ofertę: Grzegorz Działkowski, tel. 538 989 005,
 * gd@papernest.pl") into a real tel:/mailto: link, without the client
 * having to manually add one every time she writes contact info into a
 * description. Skips anything already inside a link/attribute.
 */
function papernest_autolink_contact( $text ) {
	if ( ! is_string( $text ) || '' === $text ) {
		return $text;
	}
	// Email addresses.
	$text = preg_replace_callback(
		'/(?<!["\'>:\/])[\w.+-]+@[\w-]+\.[a-z]{2,}\b/i',
		function ( $m ) {
			return '<a href="mailto:' . esc_attr( $m[0] ) . '">' . esc_html( $m[0] ) . '</a>';
		},
		$text
	);
	// Polish phone numbers written as 3-3-3 digit groups (spaces, dots or
	// dashes), optionally with a +48/48 country code.
	$text = preg_replace_callback(
		'/(?<!["\'>\d])(?:\+?48[\s.-]?)?\d{3}[\s.-]\d{3}[\s.-]\d{3}(?!\d)/',
		function ( $m ) {
			$digits = preg_replace( '/[\s.-]/', '', $m[0] );
			return '<a href="tel:' . esc_attr( $digits ) . '">' . esc_html( $m[0] ) . '</a>';
		},
		$text
	);
	return $text;
}

/**
 * Applied to the dynamic content the client edits herself in wp-admin
 * (page/post titles and body content, WooCommerce short descriptions) —
 * hardcoded copy in the theme's own templates is passed through
 * papernest_no_orphans() directly at the point it's written instead.
 */
add_filter( 'the_title', 'papernest_no_orphans' );
add_filter( 'the_content', 'papernest_no_orphans', 20 );
add_filter( 'the_content', 'papernest_autolink_contact', 8 );
add_filter( 'the_excerpt', 'papernest_no_orphans' );
add_filter( 'widget_text', 'papernest_no_orphans' );
if ( class_exists( 'WooCommerce' ) ) {
	add_filter( 'woocommerce_short_description', 'papernest_no_orphans' );
	add_filter( 'woocommerce_short_description', 'papernest_autolink_contact', 8 );
	add_filter( 'woocommerce_product_title', 'papernest_no_orphans' );
}
