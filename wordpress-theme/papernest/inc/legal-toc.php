<?php
/**
 * Auto table-of-contents for long legal pages (e.g. Regulamin): scans the
 * page content for <h3> paragraph headings, injects an id on each, and
 * returns [content_with_ids, toc_items] — mirrors add_anchors() from the
 * static prototype's tools/pages_legal.py so editors don't have to add
 * anchors by hand when they edit the text in wp-admin.
 *
 * Was <h6> — three levels below the h2 chapter headings around it with
 * nothing at h3/h4/h5 in between, which is exactly the "heading elements
 * not in a sequentially descending order" a11y/SEO issue Lighthouse flags.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_legal_add_anchors( $html ) {
	$toc     = array();
	$counter = 0;

	$html = preg_replace_callback(
		'/<h3>(.*?)<\/h3>/s',
		function ( $m ) use ( &$toc, &$counter ) {
			++$counter;
			$anchor = 'par-' . $counter;
			$inner  = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $m[1] ) ) );
			$toc[]  = array( $anchor, $inner );
			return '<h3 id="' . esc_attr( $anchor ) . '">' . $m[1] . '</h3>';
		},
		$html
	);

	return array( $html, $toc );
}
