<?php
/**
 * Checkout form handler — validates input server-side, computes shipping
 * cost from the shop settings (never from the browser), creates the order,
 * sends confirmation e-mails, and redirects back to the checkout page with
 * a one-time flag so it renders the "thank you" state instead of the form.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_handle_checkout() {
	$redirect_to = papernest_checkout_link();

	if ( ! isset( $_POST['papernest_checkout_nonce'] ) || ! wp_verify_nonce( $_POST['papernest_checkout_nonce'], 'papernest_checkout' ) ) {
		wp_safe_redirect( add_query_arg( 'blad', 'sesja', $redirect_to ) );
		exit;
	}

	// Honeypot: real visitors never fill this hidden field.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'blad', 'sesja', $redirect_to ) );
		exit;
	}

	if ( empty( papernest_get_cart() ) ) {
		wp_safe_redirect( add_query_arg( 'blad', 'pusty-koszyk', $redirect_to ) );
		exit;
	}

	$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$note  = sanitize_textarea_field( wp_unslash( $_POST['note'] ?? '' ) );

	$shipping_method = sanitize_key( wp_unslash( $_POST['shipping_method'] ?? '' ) );
	$payment_method  = sanitize_key( wp_unslash( $_POST['payment_method'] ?? '' ) );

	if ( ! $name || ! is_email( $email ) || ! $phone ) {
		wp_safe_redirect( add_query_arg( 'blad', 'dane', $redirect_to ) );
		exit;
	}

	if ( ! in_array( $shipping_method, array( 'paczkomat', 'kurier' ), true ) ) {
		wp_safe_redirect( add_query_arg( 'blad', 'dostawa', $redirect_to ) );
		exit;
	}

	if ( 'paczkomat' === $shipping_method ) {
		$paczkomat_code = sanitize_text_field( wp_unslash( $_POST['paczkomat_code'] ?? '' ) );
		if ( ! $paczkomat_code ) {
			wp_safe_redirect( add_query_arg( 'blad', 'paczkomat', $redirect_to ) );
			exit;
		}
		$shipping_detail = sprintf( 'Paczkomat %s', $paczkomat_code );
	} else {
		$street = sanitize_text_field( wp_unslash( $_POST['street'] ?? '' ) );
		$postal = sanitize_text_field( wp_unslash( $_POST['postal_code'] ?? '' ) );
		$city   = sanitize_text_field( wp_unslash( $_POST['city'] ?? '' ) );
		if ( ! $street || ! $postal || ! $city ) {
			wp_safe_redirect( add_query_arg( 'blad', 'adres', $redirect_to ) );
			exit;
		}
		$shipping_detail = sprintf( '%s, %s %s', $street, $postal, $city );
	}

	$s = papernest_shop_settings();
	if ( 'pobranie' === $payment_method && ( '1' !== $s['cod_enabled'] || 'kurier' !== $shipping_method ) ) {
		$payment_method = 'przelew';
	}
	if ( ! in_array( $payment_method, array( 'przelew', 'pobranie' ), true ) ) {
		$payment_method = 'przelew';
	}

	$items_total    = papernest_cart_total();
	$shipping_cost  = papernest_shipping_cost( $shipping_method, $items_total );

	$order_id = papernest_create_order(
		array(
			'name'  => $name,
			'email' => $email,
			'phone' => $phone,
			'note'  => $note,
		),
		$shipping_method,
		$shipping_detail,
		$shipping_cost,
		$payment_method
	);

	if ( is_wp_error( $order_id ) ) {
		wp_safe_redirect( add_query_arg( 'blad', 'zamowienie', $redirect_to ) );
		exit;
	}

	papernest_send_order_emails( $order_id );

	$_SESSION['papernest_last_order'] = array(
		'id'     => $order_id,
		'number' => get_post_meta( $order_id, '_papernest_order_number', true ),
	);

	wp_safe_redirect( add_query_arg( 'zlozono', '1', $redirect_to ) );
	exit;
}
add_action( 'admin_post_papernest_checkout', 'papernest_handle_checkout' );
add_action( 'admin_post_nopriv_papernest_checkout', 'papernest_handle_checkout' );
