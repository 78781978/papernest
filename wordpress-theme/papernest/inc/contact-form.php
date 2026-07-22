<?php
/**
 * Contact form handler — plain wp_mail, no plugin dependency. Posts to
 * admin-post.php so it works whether or not the visitor is logged in.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_handle_contact_form() {
	if ( ! isset( $_POST['papernest_contact_nonce'] ) || ! wp_verify_nonce( $_POST['papernest_contact_nonce'], 'papernest_contact_form' ) ) {
		wp_safe_redirect( add_query_arg( 'papernest_contact', 'error', wp_get_referer() ) );
		exit;
	}

	$name    = isset( $_POST['c_name'] ) ? sanitize_text_field( wp_unslash( $_POST['c_name'] ) ) : '';
	$email   = isset( $_POST['c_email'] ) ? sanitize_email( wp_unslash( $_POST['c_email'] ) ) : '';
	$phone   = isset( $_POST['c_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['c_phone'] ) ) : '';
	$topic   = isset( $_POST['c_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['c_topic'] ) ) : '';
	$message = isset( $_POST['c_msg'] ) ? sanitize_textarea_field( wp_unslash( $_POST['c_msg'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'papernest_contact', 'error', wp_get_referer() ) );
		exit;
	}

	$to      = papernest_email();
	$subject = sprintf( '[Formularz kontaktowy] %s — %s', get_bloginfo( 'name' ), $topic );
	$body    = "Imię i nazwisko: {$name}\nE-mail: {$email}\nTelefon: {$phone}\nTemat: {$topic}\n\nWiadomość:\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'papernest_contact', 'sent', wp_get_referer() ) );
	exit;
}
add_action( 'admin_post_papernest_contact', 'papernest_handle_contact_form' );
add_action( 'admin_post_nopriv_papernest_contact', 'papernest_handle_contact_form' );
