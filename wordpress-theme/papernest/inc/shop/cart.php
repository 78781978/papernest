<?php
/**
 * Server-side cart. Only product_id + variant_index + quantity are stored in
 * the session — prices are always resolved fresh from the product's current
 * variant data when the cart is read or the order is placed, so nothing sent
 * from the browser is ever trusted as a price (that's the #1 way homemade
 * cart systems get defrauded).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_cart_start_session() {
	if ( ! session_id() && ! headers_sent() ) {
		session_start();
	}
	if ( ! isset( $_SESSION['papernest_cart'] ) || ! is_array( $_SESSION['papernest_cart'] ) ) {
		$_SESSION['papernest_cart'] = array();
	}
}
add_action( 'init', 'papernest_cart_start_session', 1 );

function papernest_cart_key( $product_id, $variant_index ) {
	return absint( $product_id ) . '_' . absint( $variant_index );
}

/**
 * Raw session lines: key => ['product_id'=>, 'variant'=>, 'qty'=>].
 */
function papernest_cart_raw() {
	return isset( $_SESSION['papernest_cart'] ) && is_array( $_SESSION['papernest_cart'] ) ? $_SESSION['papernest_cart'] : array();
}

function papernest_cart_add( $product_id, $variant_index, $qty ) {
	$product_id    = absint( $product_id );
	$variant_index = absint( $variant_index );
	$qty           = max( 1, absint( $qty ) );

	$variants = papernest_get_variants( $product_id );
	if ( 'papernest_product' !== get_post_type( $product_id ) || ! isset( $variants[ $variant_index ] ) ) {
		return new WP_Error( 'invalid_product', __( 'Nieprawidłowy produkt lub wariant.', 'papernest' ) );
	}

	$key = papernest_cart_key( $product_id, $variant_index );
	if ( isset( $_SESSION['papernest_cart'][ $key ] ) ) {
		$_SESSION['papernest_cart'][ $key ]['qty'] += $qty;
	} else {
		$_SESSION['papernest_cart'][ $key ] = array(
			'product_id' => $product_id,
			'variant'    => $variant_index,
			'qty'        => $qty,
		);
	}
	return true;
}

function papernest_cart_set_qty( $key, $qty ) {
	$qty = absint( $qty );
	if ( ! isset( $_SESSION['papernest_cart'][ $key ] ) ) {
		return;
	}
	if ( $qty <= 0 ) {
		unset( $_SESSION['papernest_cart'][ $key ] );
		return;
	}
	$_SESSION['papernest_cart'][ $key ]['qty'] = min( $qty, 999 );
}

function papernest_cart_remove( $key ) {
	unset( $_SESSION['papernest_cart'][ $key ] );
}

function papernest_cart_clear() {
	$_SESSION['papernest_cart'] = array();
}

/**
 * Resolved cart lines with current product/variant data and computed totals.
 * Silently drops lines whose product or variant no longer exists (e.g. was
 * deleted) rather than erroring the whole cart.
 */
function papernest_get_cart() {
	$lines = array();
	foreach ( papernest_cart_raw() as $key => $row ) {
		$product = get_post( $row['product_id'] );
		if ( ! $product || 'papernest_product' !== $product->post_type || 'publish' !== $product->post_status ) {
			continue;
		}
		$variants = papernest_get_variants( $row['product_id'] );
		if ( ! isset( $variants[ $row['variant'] ] ) ) {
			continue;
		}
		$variant     = $variants[ $row['variant'] ];
		$unit_price  = (float) $variant['price'];
		$qty         = absint( $row['qty'] );
		$lines[]     = array(
			'key'        => $key,
			'product_id' => $product->ID,
			'name'       => $product->post_title,
			'variant'    => $variant,
			'variant_index' => $row['variant'],
			'qty'        => $qty,
			'unit_price' => $unit_price,
			'line_total' => round( $unit_price * $qty, 2 ),
			'thumb'      => get_the_post_thumbnail_url( $product->ID, 'thumbnail' ),
			'permalink'  => get_permalink( $product->ID ),
		);
	}
	return $lines;
}

function papernest_cart_total() {
	$total = 0.0;
	foreach ( papernest_get_cart() as $line ) {
		$total += $line['line_total'];
	}
	return round( $total, 2 );
}

function papernest_cart_count() {
	$count = 0;
	foreach ( papernest_cart_raw() as $row ) {
		$count += absint( $row['qty'] );
	}
	return $count;
}

/* --------------------------------------------------------------- AJAX API ---- */

function papernest_cart_verify_nonce() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'papernest_cart' ) ) {
		wp_send_json_error( array( 'message' => __( 'Sesja wygasła, odśwież stronę i spróbuj ponownie.', 'papernest' ) ), 403 );
	}
}

function papernest_cart_response() {
	wp_send_json_success(
		array(
			'count' => papernest_cart_count(),
			'total' => papernest_format_price( papernest_cart_total() ),
			'lines' => papernest_get_cart(),
		)
	);
}

function papernest_ajax_cart_add() {
	papernest_cart_verify_nonce();
	$result = papernest_cart_add(
		$_POST['product_id'] ?? 0,
		$_POST['variant'] ?? 0,
		$_POST['qty'] ?? 1
	);
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
	}
	papernest_cart_response();
}
add_action( 'wp_ajax_papernest_cart_add', 'papernest_ajax_cart_add' );
add_action( 'wp_ajax_nopriv_papernest_cart_add', 'papernest_ajax_cart_add' );

function papernest_ajax_cart_update() {
	papernest_cart_verify_nonce();
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	papernest_cart_set_qty( $key, $_POST['qty'] ?? 0 );
	papernest_cart_response();
}
add_action( 'wp_ajax_papernest_cart_update', 'papernest_ajax_cart_update' );
add_action( 'wp_ajax_nopriv_papernest_cart_update', 'papernest_ajax_cart_update' );

function papernest_ajax_cart_remove() {
	papernest_cart_verify_nonce();
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	papernest_cart_remove( $key );
	papernest_cart_response();
}
add_action( 'wp_ajax_papernest_cart_remove', 'papernest_ajax_cart_remove' );
add_action( 'wp_ajax_nopriv_papernest_cart_remove', 'papernest_ajax_cart_remove' );

/**
 * Plain form-POST handlers for the cart page itself (quantity +/- and
 * remove), so the cart works even with JavaScript disabled — the product
 * page's "add to cart" button is the only part that uses AJAX.
 */
function papernest_handle_cart_form_update() {
	if ( ! isset( $_POST['papernest_cart_nonce'] ) || ! wp_verify_nonce( $_POST['papernest_cart_nonce'], 'papernest_cart_form' ) ) {
		wp_safe_redirect( papernest_cart_link() );
		exit;
	}
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	papernest_cart_set_qty( $key, $_POST['qty'] ?? 0 );
	wp_safe_redirect( papernest_cart_link() );
	exit;
}
add_action( 'admin_post_papernest_cart_form_update', 'papernest_handle_cart_form_update' );
add_action( 'admin_post_nopriv_papernest_cart_form_update', 'papernest_handle_cart_form_update' );

function papernest_handle_cart_form_remove() {
	if ( ! isset( $_POST['papernest_cart_nonce'] ) || ! wp_verify_nonce( $_POST['papernest_cart_nonce'], 'papernest_cart_form' ) ) {
		wp_safe_redirect( papernest_cart_link() );
		exit;
	}
	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	papernest_cart_remove( $key );
	wp_safe_redirect( papernest_cart_link() );
	exit;
}
add_action( 'admin_post_papernest_cart_form_remove', 'papernest_handle_cart_form_remove' );
add_action( 'admin_post_nopriv_papernest_cart_form_remove', 'papernest_handle_cart_form_remove' );
