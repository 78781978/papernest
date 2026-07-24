<?php
/**
 * Order e-mails: confirmation to the customer and the shop owner when an
 * order is placed, and a "shipped" notice when the order's status changes
 * to "Wysłane" (see order-cpt.php).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_order_items_text( $order ) {
	$lines = array();
	foreach ( (array) $order['items'] as $item ) {
		$lines[] = sprintf(
			'%dx %s — %s (%s)',
			$item['qty'],
			$item['name'] . ' (' . $item['variant']['label'] . ')',
			papernest_format_price( $item['line_total'] ),
			papernest_format_price( $item['unit_price'] ) . '/szt.'
		);
	}
	return implode( "\n", $lines );
}

function papernest_send_order_emails( $order_id ) {
	$order = papernest_order_get( $order_id );
	$s     = papernest_shop_settings();

	$payment_labels  = array( 'przelew' => 'Przelew tradycyjny', 'pobranie' => 'Za pobraniem' );
	$shipping_labels = array( 'paczkomat' => 'Paczkomat InPost', 'kurier' => 'Kurier DPD' );

	$payment_note = '';
	if ( 'przelew' === $order['payment_method'] ) {
		$payment_note = $s['bank_account']
			? sprintf(
				"\n\nDane do przelewu:\nOdbiorca: %s\nNumer konta: %s\nKwota: %s\nTytuł: zamówienie %s",
				$s['bank_owner'],
				$s['bank_account'],
				papernest_format_price( $order['total'] ),
				$order['number']
			)
			: "\n\nDane do przelewu prześlemy odrębnie — skontaktujemy się z Tobą.";
	}

	$customer_body = sprintf(
		"Dziękujemy za zamówienie w PaperNest!\n\nNumer zamówienia: %s\n\n%s\n\nDostawa: %s (%s)\nPłatność: %s%s\n\nSuma: %s\n\nO zmianie statusu zamówienia poinformujemy e-mailem.",
		$order['number'],
		papernest_order_items_text( $order ),
		$shipping_labels[ $order['shipping_method'] ] ?? $order['shipping_method'],
		$order['shipping_detail'],
		$payment_labels[ $order['payment_method'] ] ?? $order['payment_method'],
		$payment_note,
		papernest_format_price( $order['total'] )
	);
	wp_mail(
		$order['customer_email'],
		sprintf( '[PaperNest] Potwierdzenie zamówienia %s', $order['number'] ),
		$customer_body
	);

	$owner_body = sprintf(
		"Nowe zamówienie: %s\n\nKlient: %s\nE-mail: %s\nTelefon: %s\n\n%s\n\nDostawa: %s (%s)\nPłatność: %s\nSuma: %s\n\nZarządzaj: %s",
		$order['number'],
		$order['customer_name'],
		$order['customer_email'],
		$order['customer_phone'],
		papernest_order_items_text( $order ),
		$shipping_labels[ $order['shipping_method'] ] ?? $order['shipping_method'],
		$order['shipping_detail'],
		$payment_labels[ $order['payment_method'] ] ?? $order['payment_method'],
		papernest_format_price( $order['total'] ),
		admin_url( 'post.php?post=' . $order_id . '&action=edit' )
	);
	wp_mail( papernest_email(), sprintf( '[PaperNest] Nowe zamówienie %s', $order['number'] ), $owner_body );
}

function papernest_send_order_shipped_email( $order_id ) {
	$order = papernest_order_get( $order_id );
	if ( ! $order['customer_email'] ) {
		return;
	}
	$body = sprintf(
		"Twoje zamówienie %s zostało wysłane.\n\nDostawa: %s (%s)\n\nDziękujemy za zakupy w PaperNest!",
		$order['number'],
		$order['shipping_method'],
		$order['shipping_detail']
	);
	wp_mail( $order['customer_email'], sprintf( '[PaperNest] Zamówienie %s zostało wysłane', $order['number'] ), $body );
}
