<?php
/**
 * Customizer controls: everything the client should be able to edit without
 * touching code — phone/email/address, social link, and the reserved photo
 * spots that were placeholders in the static prototype.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'papernest_contact',
		array(
			'title'    => __( 'Dane kontaktowe PaperNest', 'papernest' ),
			'priority' => 30,
		)
	);

	$contact_fields = array(
		'papernest_phone'           => array( __( 'Telefon', 'papernest' ), '538 989 005', 'text' ),
		'papernest_email'           => array( __( 'E-mail', 'papernest' ), 'gd@papernest.pl', 'email' ),
		'papernest_address'         => array( __( 'Adres', 'papernest' ), 'I Brygady Legionów 12-14, 72-100 Goleniów', 'text' ),
		'papernest_hours'           => array( __( 'Godziny otwarcia', 'papernest' ), 'Pon–Pt 8:00–16:00', 'text' ),
		'papernest_contact_person'  => array( __( 'Osoba kontaktowa (karty produktów)', 'papernest' ), 'Grzegorz Działkowski', 'text' ),
		'papernest_facebook'        => array( __( 'Link do Facebooka', 'papernest' ), 'https://pl-pl.facebook.com/Papernestapp/', 'url' ),
	);

	foreach ( $contact_fields as $id => $field ) {
		list( $label, $default, $type ) = $field;
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => 'url' === $type ? 'esc_url_raw' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $label,
				'section' => 'papernest_contact',
				'type'    => $type,
			)
		);
	}

	// Reserved photo spots — same slots the static prototype marked as placeholders.
	$wp_customize->add_section(
		'papernest_photos',
		array(
			'title'    => __( 'Zdjęcia na stronie', 'papernest' ),
			'priority' => 31,
		)
	);

	$photo_fields = array(
		'papernest_photo_about_1'   => __( 'O nas — zdjęcie: wypełniacz papierowy', 'papernest' ),
		'papernest_photo_about_2'   => __( 'O nas — zdjęcie: papier dla piskląt', 'papernest' ),
		'papernest_photo_about_3'   => __( 'O nas — zdjęcie: tektura budowlana', 'papernest' ),
		'papernest_photo_warehouse' => __( 'Kontakt — zdjęcie magazynu', 'papernest' ),
		'papernest_photo_shop'      => __( 'Kontakt — zdjęcie sklepu / punktu odbioru', 'papernest' ),
		'papernest_photo_office'    => __( 'Kontakt — zdjęcie biura sprzedaży', 'papernest' ),
		'papernest_logo_paynow'     => __( 'Logo paynow (Płatność i Dostawa)', 'papernest' ),
		'papernest_logo_inpost'     => __( 'Logo InPost (Płatność i Dostawa)', 'papernest' ),
		'papernest_logo_dpd'        => __( 'Logo DPD (Płatność i Dostawa)', 'papernest' ),
	);

	foreach ( $photo_fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $label,
					'section' => 'papernest_photos',
				)
			)
		);
	}

	// Decorative illustrations (hero graphic, "O nas" graphic, industrial
	// services graphic) — these ship with a built-in SVG drawing, but the
	// client can replace any of them with her own photo/graphic here.
	$wp_customize->add_section(
		'papernest_illustrations',
		array(
			'title'    => __( 'Ilustracje', 'papernest' ),
			'priority' => 32,
			'description' => __( 'Te grafiki mają domyślną ilustrację wbudowaną w motyw — wgraj tu zdjęcie lub grafikę, żeby ją podmienić.', 'papernest' ),
		)
	);

	$illustration_fields = array(
		'papernest_illustration_hero'          => __( 'Strona główna — duża ilustracja (sekcja hero)', 'papernest' ),
		'papernest_illustration_about'         => __( 'O nas — duża ilustracja (obok "Nasza Historia")', 'papernest' ),
		'papernest_illustration_services'      => __( 'Strona główna — ilustracja usług przemysłowych', 'papernest' ),
		'papernest_illustration_about_growth'  => __( 'O nas — ilustracja przy sekcji "Tradycja produkcji, nowoczesna sprzedaż"', 'papernest' ),
		'papernest_home_product_1'             => __( 'Strona główna — zdjęcie produktu 1 (sekcja "Trzy produkty")', 'papernest' ),
		'papernest_home_product_2'             => __( 'Strona główna — zdjęcie produktu 2 (sekcja "Trzy produkty")', 'papernest' ),
		'papernest_home_product_3'             => __( 'Strona główna — zdjęcie produktu 3 (sekcja "Trzy produkty")', 'papernest' ),
	);

	foreach ( $illustration_fields as $id => $label ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				$id,
				array(
					'label'   => $label,
					'section' => 'papernest_illustrations',
				)
			)
		);
	}
}
add_action( 'customize_register', 'papernest_customize_register' );
