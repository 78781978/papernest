<?php
/**
 * Orders — a lightweight custom post type with an admin list tailored for
 * order management (status, customer, total) instead of the generic Posts
 * screen, since there's no WooCommerce "Zamówienia" screen to rely on here.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_order_statuses() {
	return array(
		'nowe'         => __( 'Nowe', 'papernest' ),
		'w_realizacji' => __( 'W realizacji', 'papernest' ),
		'wyslane'      => __( 'Wysłane', 'papernest' ),
		'zrealizowane' => __( 'Zrealizowane', 'papernest' ),
		'anulowane'    => __( 'Anulowane', 'papernest' ),
	);
}

function papernest_register_order_cpt() {
	register_post_type(
		'papernest_order',
		array(
			'labels'              => array(
				'name'          => __( 'Zamówienia', 'papernest' ),
				'singular_name' => __( 'Zamówienie', 'papernest' ),
				'edit_item'     => __( 'Szczegóły zamówienia', 'papernest' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-clipboard',
			'menu_position'       => 21,
			'capability_type'     => 'post',
			'supports'            => array( 'title' ),
			'exclude_from_search' => true,
		)
	);
}
add_action( 'init', 'papernest_register_order_cpt' );

/**
 * Creates an order from the current cart + checkout form data. Returns the
 * new order's post ID. Everything here is a snapshot (prices, names) taken
 * at order time, so later edits to products don't change past orders.
 */
function papernest_create_order( array $customer, string $shipping_method, string $shipping_detail, float $shipping_cost, string $payment_method ) {
	$lines = papernest_get_cart();
	if ( empty( $lines ) ) {
		return new WP_Error( 'empty_cart', __( 'Koszyk jest pusty.', 'papernest' ) );
	}

	$items_total = papernest_cart_total();
	$grand_total = round( $items_total + $shipping_cost, 2 );

	$order_number = strtoupper( 'PN-' . gmdate( 'ymd' ) . '-' . wp_generate_password( 4, false, false ) );

	$order_id = wp_insert_post(
		array(
			'post_type'   => 'papernest_order',
			'post_title'  => $order_number,
			'post_status' => 'publish',
		)
	);
	if ( is_wp_error( $order_id ) ) {
		return $order_id;
	}

	update_post_meta( $order_id, '_papernest_order_number', $order_number );
	update_post_meta( $order_id, '_papernest_order_items', $lines );
	update_post_meta( $order_id, '_papernest_order_items_total', $items_total );
	update_post_meta( $order_id, '_papernest_order_shipping_method', $shipping_method );
	update_post_meta( $order_id, '_papernest_order_shipping_detail', $shipping_detail );
	update_post_meta( $order_id, '_papernest_order_shipping_cost', $shipping_cost );
	update_post_meta( $order_id, '_papernest_order_payment_method', $payment_method );
	update_post_meta( $order_id, '_papernest_order_total', $grand_total );
	update_post_meta( $order_id, '_papernest_order_status', 'nowe' );
	update_post_meta( $order_id, '_papernest_order_customer_name', $customer['name'] );
	update_post_meta( $order_id, '_papernest_order_customer_email', $customer['email'] );
	update_post_meta( $order_id, '_papernest_order_customer_phone', $customer['phone'] );
	update_post_meta( $order_id, '_papernest_order_customer_note', $customer['note'] );

	papernest_cart_clear();

	return $order_id;
}

function papernest_order_get( $order_id ) {
	return array(
		'id'              => $order_id,
		'number'          => get_post_meta( $order_id, '_papernest_order_number', true ),
		'items'           => get_post_meta( $order_id, '_papernest_order_items', true ),
		'items_total'     => (float) get_post_meta( $order_id, '_papernest_order_items_total', true ),
		'shipping_method' => get_post_meta( $order_id, '_papernest_order_shipping_method', true ),
		'shipping_detail' => get_post_meta( $order_id, '_papernest_order_shipping_detail', true ),
		'shipping_cost'   => (float) get_post_meta( $order_id, '_papernest_order_shipping_cost', true ),
		'payment_method'  => get_post_meta( $order_id, '_papernest_order_payment_method', true ),
		'total'           => (float) get_post_meta( $order_id, '_papernest_order_total', true ),
		'status'          => get_post_meta( $order_id, '_papernest_order_status', true ),
		'customer_name'   => get_post_meta( $order_id, '_papernest_order_customer_name', true ),
		'customer_email'  => get_post_meta( $order_id, '_papernest_order_customer_email', true ),
		'customer_phone'  => get_post_meta( $order_id, '_papernest_order_customer_phone', true ),
		'customer_note'   => get_post_meta( $order_id, '_papernest_order_customer_note', true ),
		'date'            => get_the_date( 'd.m.Y H:i', $order_id ),
	);
}

/* ------------------------------------------------------------- Admin list ---- */

function papernest_order_columns( $columns ) {
	$new = array(
		'cb'       => $columns['cb'],
		'title'    => __( 'Numer', 'papernest' ),
		'customer' => __( 'Klient', 'papernest' ),
		'total'    => __( 'Suma', 'papernest' ),
		'payment'  => __( 'Płatność', 'papernest' ),
		'shipping' => __( 'Dostawa', 'papernest' ),
		'status'   => __( 'Status', 'papernest' ),
		'date'     => __( 'Data', 'papernest' ),
	);
	return $new;
}
add_filter( 'manage_papernest_order_posts_columns', 'papernest_order_columns' );

function papernest_order_column_content( $column, $post_id ) {
	$payment_labels  = array( 'przelew' => 'Przelew tradycyjny', 'pobranie' => 'Za pobraniem' );
	$shipping_labels = array( 'paczkomat' => 'Paczkomat InPost', 'kurier' => 'Kurier DPD' );

	switch ( $column ) {
		case 'customer':
			echo esc_html( get_post_meta( $post_id, '_papernest_order_customer_name', true ) );
			echo '<br><small>' . esc_html( get_post_meta( $post_id, '_papernest_order_customer_email', true ) ) . '</small>';
			break;
		case 'total':
			echo esc_html( papernest_format_price( get_post_meta( $post_id, '_papernest_order_total', true ) ) );
			break;
		case 'payment':
			$m = get_post_meta( $post_id, '_papernest_order_payment_method', true );
			echo esc_html( $payment_labels[ $m ] ?? $m );
			break;
		case 'shipping':
			$m = get_post_meta( $post_id, '_papernest_order_shipping_method', true );
			echo esc_html( $shipping_labels[ $m ] ?? $m );
			break;
		case 'status':
			$current = get_post_meta( $post_id, '_papernest_order_status', true );
			echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" class="papernest-status-form">';
			wp_nonce_field( 'papernest_update_order_status', 'papernest_status_nonce' );
			echo '<input type="hidden" name="action" value="papernest_update_order_status">';
			echo '<input type="hidden" name="order_id" value="' . esc_attr( $post_id ) . '">';
			echo '<select name="status" onchange="this.form.submit()">';
			foreach ( papernest_order_statuses() as $key => $label ) {
				printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $key ), selected( $current, $key, false ), esc_html( $label ) );
			}
			echo '</select></form>';
			break;
	}
}
add_action( 'manage_papernest_order_posts_custom_column', 'papernest_order_column_content', 10, 2 );

function papernest_handle_order_status_update() {
	if ( ! isset( $_POST['papernest_status_nonce'] ) || ! wp_verify_nonce( $_POST['papernest_status_nonce'], 'papernest_update_order_status' ) ) {
		wp_die( esc_html__( 'Nieprawidłowe żądanie.', 'papernest' ) );
	}
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'Brak uprawnień.', 'papernest' ) );
	}
	$order_id = absint( $_POST['order_id'] ?? 0 );
	$status   = sanitize_key( $_POST['status'] ?? '' );
	if ( $order_id && array_key_exists( $status, papernest_order_statuses() ) ) {
		update_post_meta( $order_id, '_papernest_order_status', $status );
		if ( 'wyslane' === $status ) {
			papernest_send_order_shipped_email( $order_id );
		}
	}
	wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=papernest_order' ) );
	exit;
}
add_action( 'admin_post_papernest_update_order_status', 'papernest_handle_order_status_update' );

/**
 * Order detail (line items, address, payment) shown on the order's edit
 * screen instead of the normal content editor.
 */
function papernest_order_detail_meta_box( $post ) {
	$order = papernest_order_get( $post->ID );
	?>
	<p><strong><?php esc_html_e( 'Klient:', 'papernest' ); ?></strong> <?php echo esc_html( $order['customer_name'] ); ?><br>
	<?php echo esc_html( $order['customer_email'] ); ?> · <?php echo esc_html( $order['customer_phone'] ); ?></p>
	<?php if ( $order['customer_note'] ) : ?>
		<p><strong><?php esc_html_e( 'Uwagi klienta:', 'papernest' ); ?></strong> <?php echo esc_html( $order['customer_note'] ); ?></p>
	<?php endif; ?>
	<p><strong><?php esc_html_e( 'Dostawa:', 'papernest' ); ?></strong> <?php echo esc_html( $order['shipping_method'] ); ?> — <?php echo esc_html( $order['shipping_detail'] ); ?></p>
	<p><strong><?php esc_html_e( 'Płatność:', 'papernest' ); ?></strong> <?php echo esc_html( $order['payment_method'] ); ?></p>
	<table class="widefat striped">
		<thead><tr><th><?php esc_html_e( 'Produkt', 'papernest' ); ?></th><th><?php esc_html_e( 'Ilość', 'papernest' ); ?></th><th><?php esc_html_e( 'Cena', 'papernest' ); ?></th><th><?php esc_html_e( 'Razem', 'papernest' ); ?></th></tr></thead>
		<tbody>
		<?php foreach ( (array) $order['items'] as $item ) : ?>
			<tr>
				<td><?php echo esc_html( $item['name'] . ' — ' . $item['variant']['label'] ); ?></td>
				<td><?php echo esc_html( $item['qty'] ); ?></td>
				<td><?php echo esc_html( papernest_format_price( $item['unit_price'] ) ); ?></td>
				<td><?php echo esc_html( papernest_format_price( $item['line_total'] ) ); ?></td>
			</tr>
		<?php endforeach; ?>
		<tr><td colspan="3" style="text-align:right"><strong><?php esc_html_e( 'Dostawa', 'papernest' ); ?></strong></td><td><?php echo esc_html( papernest_format_price( $order['shipping_cost'] ) ); ?></td></tr>
		<tr><td colspan="3" style="text-align:right"><strong><?php esc_html_e( 'Suma', 'papernest' ); ?></strong></td><td><strong><?php echo esc_html( papernest_format_price( $order['total'] ) ); ?></strong></td></tr>
		</tbody>
	</table>
	<?php
}

function papernest_order_meta_boxes() {
	add_meta_box( 'papernest_order_detail', __( 'Zamówienie', 'papernest' ), 'papernest_order_detail_meta_box', 'papernest_order', 'normal', 'high' );
	remove_post_type_support( 'papernest_order', 'editor' );
}
add_action( 'add_meta_boxes', 'papernest_order_meta_boxes' );
