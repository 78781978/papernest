<?php
/**
 * Shop settings: bank account for "przelew tradycyjny", and flat shipping
 * rates. Kept as plain options (not a whole settings framework) since there
 * are only a handful of fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_shop_settings_defaults() {
	return array(
		'bank_account'            => '',
		'bank_owner'              => 'P.H.U „Bobinex” Grzegorz Działkowski',
		'shipping_paczkomat'      => '14.99',
		'shipping_kurier'         => '24.99',
		'free_shipping_threshold' => '300',
		'cod_enabled'             => '1',
	);
}

function papernest_shop_settings() {
	$saved = get_option( 'papernest_shop_settings', array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), papernest_shop_settings_defaults() );
}

function papernest_shop_settings_menu() {
	add_submenu_page(
		'edit.php?post_type=papernest_product',
		__( 'Ustawienia sklepu', 'papernest' ),
		__( 'Ustawienia sklepu', 'papernest' ),
		'manage_options',
		'papernest-shop-settings',
		'papernest_shop_settings_page'
	);
}
add_action( 'admin_menu', 'papernest_shop_settings_menu' );

function papernest_shop_settings_page() {
	if ( isset( $_POST['papernest_shop_settings_nonce'] ) && wp_verify_nonce( $_POST['papernest_shop_settings_nonce'], 'papernest_save_shop_settings' ) && current_user_can( 'manage_options' ) ) {
		$settings = array(
			'bank_account'            => sanitize_text_field( wp_unslash( $_POST['bank_account'] ?? '' ) ),
			'bank_owner'              => sanitize_text_field( wp_unslash( $_POST['bank_owner'] ?? '' ) ),
			'shipping_paczkomat'      => (string) papernest_parse_price( $_POST['shipping_paczkomat'] ?? '' ),
			'shipping_kurier'         => (string) papernest_parse_price( $_POST['shipping_kurier'] ?? '' ),
			'free_shipping_threshold' => (string) papernest_parse_price( $_POST['free_shipping_threshold'] ?? '' ),
			'cod_enabled'             => isset( $_POST['cod_enabled'] ) ? '1' : '0',
		);
		update_option( 'papernest_shop_settings', $settings );
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Zapisano ustawienia sklepu.', 'papernest' ) . '</p></div>';
	}

	$s = papernest_shop_settings();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ustawienia sklepu', 'papernest' ); ?></h1>
		<form method="post">
			<?php wp_nonce_field( 'papernest_save_shop_settings', 'papernest_shop_settings_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th><label for="bank_account"><?php esc_html_e( 'Numer konta bankowego (do przelewów)', 'papernest' ); ?></label></th>
					<td><input type="text" id="bank_account" name="bank_account" class="regular-text" value="<?php echo esc_attr( $s['bank_account'] ); ?>" placeholder="00 0000 0000 0000 0000 0000 0000"></td>
				</tr>
				<tr>
					<th><label for="bank_owner"><?php esc_html_e( 'Odbiorca przelewu', 'papernest' ); ?></label></th>
					<td><input type="text" id="bank_owner" name="bank_owner" class="regular-text" value="<?php echo esc_attr( $s['bank_owner'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="shipping_paczkomat"><?php esc_html_e( 'Koszt wysyłki — Paczkomat InPost (zł)', 'papernest' ); ?></label></th>
					<td><input type="text" inputmode="decimal" id="shipping_paczkomat" name="shipping_paczkomat" value="<?php echo esc_attr( $s['shipping_paczkomat'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="shipping_kurier"><?php esc_html_e( 'Koszt wysyłki — Kurier DPD (zł)', 'papernest' ); ?></label></th>
					<td><input type="text" inputmode="decimal" id="shipping_kurier" name="shipping_kurier" value="<?php echo esc_attr( $s['shipping_kurier'] ); ?>"></td>
				</tr>
				<tr>
					<th><label for="free_shipping_threshold"><?php esc_html_e( 'Darmowa wysyłka od kwoty (zł, zostaw puste/0 żeby wyłączyć)', 'papernest' ); ?></label></th>
					<td><input type="text" inputmode="decimal" id="free_shipping_threshold" name="free_shipping_threshold" value="<?php echo esc_attr( $s['free_shipping_threshold'] ); ?>"></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Płatność za pobraniem', 'papernest' ); ?></th>
					<td><label><input type="checkbox" name="cod_enabled" <?php checked( '1', $s['cod_enabled'] ); ?>> <?php esc_html_e( 'Włącz płatność za pobraniem (tylko przy kurierze DPD)', 'papernest' ); ?></label></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<p class="description"><?php esc_html_e( 'Płatności online (BLIK, karta, szybki przelew) nie są jeszcze podłączone — wymagają konta u dostawcy płatności (np. paynow) i jego kluczy API. Do tego czasu klienci płacą przelewem tradycyjnym lub za pobraniem.', 'papernest' ); ?></p>
	</div>
	<?php
}

function papernest_shipping_cost( $method, $items_total ) {
	$s = papernest_shop_settings();
	$threshold = (float) $s['free_shipping_threshold'];
	if ( $threshold > 0 && $items_total >= $threshold ) {
		return 0.0;
	}
	if ( 'paczkomat' === $method ) {
		return (float) $s['shipping_paczkomat'];
	}
	if ( 'kurier' === $method ) {
		return (float) $s['shipping_kurier'];
	}
	return 0.0;
}
