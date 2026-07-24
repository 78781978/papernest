<?php
/**
 * Demo content import — creates the core pages, sample WooCommerce products,
 * portfolio tiles and testimonials with the same copy as the static
 * prototype. Everything it creates is normal, editable WordPress content —
 * the client can rewrite or delete any of it afterwards.
 *
 * Deliberately NOT hooked to theme activation: running this automatically on
 * activation is risky on a site that isn't empty (e.g. re-activating the
 * theme, or switching to it on a site that already has real pages/products)
 * — it could create confusing duplicate/placeholder content next to real
 * content. Instead it only runs when explicitly triggered from
 * Wygląd > Treść startowa, so whoever installs the site decides if and when
 * to seed it with starter content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_run_demo_import() {
	papernest_import_pages();
	papernest_import_menu();
	papernest_import_testimonials();
	papernest_import_usecases();
	if ( class_exists( 'WooCommerce' ) ) {
		papernest_import_products();
		papernest_setup_wc_payment_gateways();
	}
	update_option( 'papernest_demo_imported', 1 );
}

/**
 * Wygląd > Treść startowa — the only way this import runs. See the note
 * above for why it's not tied to theme activation.
 */
function papernest_demo_import_admin_menu() {
	add_theme_page(
		__( 'Treść startowa PaperNest', 'papernest' ),
		__( 'Treść startowa', 'papernest' ),
		'manage_options',
		'papernest-demo-import',
		'papernest_demo_import_admin_page'
	);
}
add_action( 'admin_menu', 'papernest_demo_import_admin_menu' );

function papernest_demo_import_admin_page() {
	if ( isset( $_POST['papernest_import_nonce'] ) && wp_verify_nonce( $_POST['papernest_import_nonce'], 'papernest_run_import' ) && current_user_can( 'manage_options' ) ) {
		papernest_run_demo_import();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Treść startowa została zaimportowana.', 'papernest' ) . '</p></div>';
	}
	$already = get_option( 'papernest_demo_imported' ) ? __( 'tak — uruchomiono wcześniej', 'papernest' ) : __( 'nie', 'papernest' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Treść startowa PaperNest', 'papernest' ); ?></h1>
		<p><?php esc_html_e( 'Tworzy przykładowe strony (O nas, Portfolio, Kontakt, dokumenty prawne), menu główne, opinie klientów, kafelki portfolio oraz — jeśli WooCommerce jest aktywne — trzy przykładowe produkty z wariantami cenowymi. Bezpiecznie uruchamiać wielokrotnie: istniejące produkty/strony nie zostaną zdublowane.', 'papernest' ); ?></p>
		<p><strong><?php esc_html_e( 'Uruchomiono wcześniej:', 'papernest' ); ?></strong> <?php echo esc_html( $already ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'papernest_run_import', 'papernest_import_nonce' ); ?>
			<?php submit_button( __( 'Importuj / uzupełnij treść startową', 'papernest' ) ); ?>
		</form>
	</div>
	<?php
}

/* ------------------------------------------------------------------ Pages ---- */

function papernest_import_pages() {
	$legal_dir = PAPERNEST_DIR . '/inc/legal-content/';
	$email     = papernest_email();

	$linkify = function ( $html ) use ( $email ) {
		return preg_replace(
			'/(?<!mailto:)' . preg_quote( $email, '/' ) . '/',
			'<a href="mailto:' . $email . '">' . $email . '</a>',
			$html
		);
	};

	$pages = array(
		array( 'title' => 'Home', 'slug' => 'home', 'template' => '', 'content' => '' ),
		array( 'title' => 'O nas', 'slug' => 'o-nas', 'template' => 'page-o-nas.php', 'content' => papernest_default_about_content() ),
		array( 'title' => 'Portfolio', 'slug' => 'portfolio', 'template' => 'page-portfolio.php', 'content' => '' ),
		array( 'title' => 'Kontakt', 'slug' => 'kontakt', 'template' => 'page-kontakt.php', 'content' => '' ),
		array( 'title' => 'Płatność i Dostawa', 'slug' => 'platnosc-i-dostawa', 'template' => 'page-platnosc.php', 'content' => '' ),
		array(
			'title'    => 'Odstąpienie od umowy',
			'slug'     => 'odstapienie',
			'template' => 'page-odstapienie.php',
			'content'  => $linkify( file_get_contents( $legal_dir . 'prawo-do-odstapienia.html' ) ),
		),
		array(
			'title'    => 'Regulamin Sklepu',
			'slug'     => 'regulamin',
			'template' => '',
			'content'  => $linkify( file_get_contents( $legal_dir . 'regulamin.html' ) ),
		),
		array(
			'title'    => 'Polityka Prywatności',
			'slug'     => 'polityka-prywatnosci',
			'template' => '',
			'content'  => $linkify( file_get_contents( $legal_dir . 'polityka-prywatnosci.html' ) ),
		),
		array(
			'title'    => 'Reklamacje',
			'slug'     => 'reklamacje',
			'template' => '',
			'content'  => $linkify( file_get_contents( $legal_dir . 'reklamacje.html' ) ),
		),
		array(
			'title'    => 'Prawo do odstąpienia od umowy',
			'slug'     => 'prawo-do-odstapienia-od-umowy',
			'template' => '',
			'content'  => $linkify( file_get_contents( $legal_dir . 'prawo-do-odstapienia.html' ) ),
		),
		array(
			'title'    => 'Deklaracja dostępności',
			'slug'     => 'dostepnosc',
			'template' => '',
			'content'  => papernest_default_accessibility_content(),
		),
	);

	$ids = array();
	foreach ( $pages as $p ) {
		$existing = get_page_by_path( $p['slug'] );
		if ( $existing ) {
			$ids[ $p['slug'] ] = $existing->ID;
			if ( $p['template'] ) {
				update_post_meta( $existing->ID, '_wp_page_template', $p['template'] );
			}
			continue;
		}
		$page_id = wp_insert_post(
			array(
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_content' => $p['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
		if ( $page_id && ! is_wp_error( $page_id ) && $p['template'] ) {
			update_post_meta( $page_id, '_wp_page_template', $p['template'] );
		}
		$ids[ $p['slug'] ] = $page_id;
	}

	// Set the "Home" page as the static front page.
	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}
}

function papernest_default_about_content() {
	return "<p>P.H.U „Bobinex” Grzegorz Działkowski to polska firma z Goleniowa, z wieloletnim doświadczeniem w branży papierniczej, specjalizująca się w przewijaniu papieru oraz cięciu wzdłużnym rolek papierowych. Fundamentem działalności jest ponad 20 lat praktyki i znajomości procesów związanych z przetwórstwem papieru.</p>\n"
		. "<p>Z pasji do tworzenia praktycznych i ekologicznych produktów powstała marka <strong>PaperNest</strong>, której celem jest dostarczanie wysokiej jakości wyrobów papierowych oraz profesjonalnych usług przetwórczych.</p>\n"
		. '<p>Obecnie PaperNest oferuje trzy główne grupy produktów: wypełniacze papierowe do zabezpieczania przesyłek, papier dla piskląt wykorzystywany w hodowli drobiu oraz tekturę budowlaną przeznaczoną do ochrony powierzchni podczas prac remontowych.</p>';
}

function papernest_default_accessibility_content() {
	$email     = papernest_email();
	$phone_tel = papernest_phone_tel();
	$phone     = papernest_phone_display();
	return '<p>P.H.U „Bobinex” Grzegorz Działkowski zobowiązuje się zapewnić dostępność serwisu papernest.pl zgodnie z ustawą z dnia 4 kwietnia 2019 r. o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych oraz wytycznymi WCAG 2.1.</p>'
		. '<h6>Status zgodności</h6><p>Serwis dąży do zgodności z wytycznymi <strong>WCAG 2.1 na poziomie AA</strong>.</p>'
		. '<h6>Zgłaszanie uwag i problemów z dostępnością</h6><p>E-mail: <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a><br>Telefon: <a href="' . esc_url( $phone_tel ) . '">' . esc_html( $phone ) . '</a></p>'
		. '<h6>Procedura odwoławcza</h6><p>Jeżeli zgłoszenie problemu z dostępnością nie zostanie rozpatrzone w sposób satysfakcjonujący, masz prawo złożyć skargę do Rzecznika Praw Obywatelskich.</p>';
}

/* -------------------------------------------------------------------- Menu ---- */

function papernest_import_menu() {
	$existing_menu = wp_get_nav_menu_object( 'Menu główne' );
	if ( $existing_menu && ! empty( wp_get_nav_menu_items( $existing_menu->term_id ) ) ) {
		// Menu already has items (from a previous import or manual edits in
		// Wygląd > Menu) — leave it alone rather than risk duplicating items.
		return;
	}
	$menu_id = $existing_menu ? $existing_menu->term_id : wp_create_nav_menu( 'Menu główne' );

	$home_id      = get_option( 'page_on_front' );
	$shop_page_id = class_exists( 'WooCommerce' ) ? wc_get_page_id( 'shop' ) : 0;
	$onas         = get_page_by_path( 'o-nas' );
	$portfolio    = get_page_by_path( 'portfolio' );
	$kontakt      = get_page_by_path( 'kontakt' );

	$items = array(
		array( 'title' => 'Home', 'object_id' => $home_id, 'type' => 'post_type', 'object' => 'page' ),
	);
	if ( $shop_page_id && $shop_page_id > 0 ) {
		$items[] = array( 'title' => 'Sklep', 'object_id' => $shop_page_id, 'type' => 'post_type', 'object' => 'page' );
	}
	if ( $onas ) {
		$items[] = array( 'title' => 'O Nas', 'object_id' => $onas->ID, 'type' => 'post_type', 'object' => 'page' );
	}
	if ( $portfolio ) {
		$items[] = array( 'title' => 'Portfolio', 'object_id' => $portfolio->ID, 'type' => 'post_type', 'object' => 'page' );
	}
	if ( $kontakt ) {
		$items[] = array( 'title' => 'Kontakt', 'object_id' => $kontakt->ID, 'type' => 'post_type', 'object' => 'page' );
	}

	foreach ( $items as $item ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => $item['title'],
				'menu-item-object-id' => $item['object_id'],
				'menu-item-object'    => $item['object'],
				'menu-item-type'      => $item['type'],
				'menu-item-status'    => 'publish',
			)
		);
	}

	$locations               = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary']    = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/* ------------------------------------------------------------ Testimonials ---- */

function papernest_import_testimonials() {
	if ( ! empty( get_posts( array( 'post_type' => 'papernest_review', 'numberposts' => 1, 'post_status' => 'any' ) ) ) ) {
		return;
	}

	$testimonials = array(
		array( 'Bracia Pikuła', 'Fermy BKK Pikuła', 'Doskonała Jakość', 'Jesteśmy bardzo zadowoleni z jakości papieru dla piskląt. Produkt jest wytrzymały, dobrze spełnia swoją funkcję i sprawdza się w codziennej pracy na fermie. Doceniamy również terminowe dostawy oraz profesjonalną obsługę.' ),
		array( 'J. Bednarczyk', 'Jar-Pol', 'Skuteczna ochrona', 'Tektura budowlana doskonale sprawdza się podczas prac wykończeniowych i remontowych. Skutecznie zabezpiecza podłogi oraz inne powierzchnie przed uszkodzeniami i zabrudzeniami. Doceniamy wysoką jakość!' ),
		array( 'Adam Ilnicki', 'Agrofirma Witkowo', 'Rzetelna Obsługa', 'Papier dla piskląt spełnił nasze oczekiwania pod względem jakości i funkcjonalności. Produkt jest trwały, wygodny w użytkowaniu i doskonale sprawdza się podczas odchowu piskląt.' ),
		array( 'W. Pazdańska', 'PaperNest Professional', 'Przyjazna obsługa, świetny wypełniacz', 'Bardzo miła i pomocna obsługa. Wypełniacz papierowy jest wysokiej jakości, skutecznie chroni produkty podczas transportu i świetnie sprawdza się podczas pakowania zamówień z naszego sklepu internetowego.' ),
	);

	foreach ( $testimonials as $i => $t ) {
		list( $author, $role, $title, $text ) = $t;
		$post_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_content' => $text,
				'post_status'  => 'publish',
				'post_type'    => 'papernest_review',
				'menu_order'   => $i,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_papernest_author', $author );
			update_post_meta( $post_id, '_papernest_role', $role );
		}
	}
}

/* ---------------------------------------------------------------- Portfolio ---- */

function papernest_import_usecases() {
	if ( ! empty( get_posts( array( 'post_type' => 'papernest_usecase', 'numberposts' => 1, 'post_status' => 'any' ) ) ) ) {
		return;
	}

	$uses = array(
		array( 'box', 'E-commerce i logistyka' ),
		array( 'palette', 'Zajęcia kreatywne' ),
		array( 'egg', 'Podłoże pokarmu dla piskląt' ),
		array( 'gift', 'ECO pakowanie prezentów' ),
		array( 'truck', 'Wyściółka transporterów' ),
		array( 'wrench', 'Papier dla mechaników' ),
		array( 'leaf', 'Papier dla florystów' ),
		array( 'shield', 'Tektura zabezpieczająca' ),
		array( 'layers', 'Tektura tapicerska' ),
		array( 'vet', 'Papier dla weterynarzy' ),
		array( 'die', 'Tektura do wykrojników' ),
		array( 'blueprint', 'Tektura do makiet' ),
	);

	foreach ( $uses as $i => $u ) {
		list( $icon, $title ) = $u;
		$post_id = wp_insert_post(
			array(
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => 'papernest_usecase',
				'menu_order'  => $i,
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_papernest_icon', $icon );
		}
	}
}

/* ----------------------------------------------------------------- Products ---- */

function papernest_import_products() {
	if ( ! empty( get_posts( array( 'post_type' => 'product', 'numberposts' => 1, 'post_status' => 'any' ) ) ) ) {
		return;
	}

	$products = array(
		array(
			'name'     => 'Wypełniacz Papierowy',
			'category' => 'Ekologiczny',
			'short'    => 'Ekologiczny wypełniacz papierowy do paczek — 100% surowca z recyklingu, w pełni biodegradowalny.',
			'desc'     => '<p><strong>Wypełniacz papierowy PaperNest</strong> to nowoczesne i odpowiedzialne rozwiązanie do zabezpieczania przesyłek. Wykonany w 100% z papieru pochodzącego z recyklingu, jest w pełni biodegradowalny i zgodny z ideą zero waste.</p><p>Doskonale sprawdza się przy wypełnianiu pustych przestrzeni w kartonach, zabezpieczaniu produktów podczas transportu oraz estetycznym pakowaniu zamówień.</p>',
			'variants' => array(
				array( '1 rolka', '420 mb x 35 cm', 45.00, 49.00 ),
				array( '2 rolki', '840 mb łącznie', 87.99, 97.99 ),
				array( '4 rolki', '1680 mb łącznie', 172.99, 184.99 ),
				array( 'Paleta 58 szt.', 'zamówienia hurtowe', 2500.00, 2650.00 ),
			),
		),
		array(
			'name'     => 'Papier Dla Piskląt',
			'category' => 'Dla hodowców',
			'short'    => 'Zielony papier pod paszę — wspiera start stada od pierwszych godzin życia piskląt.',
			'desc'     => '<p>Pierwsze dni życia piskląt to kluczowy moment, który bezpośrednio wpływa na zdrowie, rozwój i wyniki całej hodowli. <strong>Papier dla piskląt PaperNest</strong> to sprawdzone rozwiązanie, które wspiera start stada od pierwszych godzin.</p><p>Dzięki temu pisklęta szybciej odnajdują paszę i wodę, a szeleszczący dźwięk papieru przyciąga je i pobudza do aktywności.</p>',
			'variants' => array(
				array( '1 rolka', '200 mb x 64 cm', 51.49, 54.99 ),
				array( '2 rolki', '400 mb łącznie', 99.99, 107.99 ),
				array( '4 rolki', '800 mb łącznie', 195.99, 212.99 ),
				array( 'Paleta 62 szt.', 'zamówienia hurtowe', 2850.00, 2915.00 ),
			),
		),
		array(
			'name'     => 'Tektura Budowlana',
			'category' => 'Do remontu',
			'short'    => 'Papier ochronny w rolce do zabezpieczania podłóg i powierzchni podczas remontu.',
			'desc'     => '<p>Profesjonalny <strong>papier budowlany marki PaperNest</strong> (znany również jako tektura w rolce) to niezbędne rozwiązanie zarówno dla ekip remontowych, jak i osób samodzielnie odnawiających wnętrza.</p><p>Wysoka jakość i wytrzymałość materiału sprawiają, że skutecznie chroni powierzchnie przed zabrudzeniami z farby.</p>',
			'variants' => array(
				array( '1 rolka', '15 m² x 1 m', 26.90, 29.99 ),
				array( '2 rolki', 'ok. 30 m² łącznie', 53.49, null ),
				array( '4 rolki', 'ok. 60 m² łącznie', 104.99, null ),
				array( 'Paleta 96 szt.', 'zamówienia hurtowe', 2490.00, 2830.00 ),
			),
		),
	);

	foreach ( $products as $p ) {
		$term = term_exists( $p['category'], 'product_cat' );
		if ( ! $term ) {
			$term = wp_insert_term( $p['category'], 'product_cat' );
		}
		$term_id = is_array( $term ) ? $term['term_id'] : $term;

		$product = new WC_Product_Variable();
		$product->set_name( $p['name'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_description( $p['desc'] );
		$product->set_short_description( $p['short'] );
		$product->set_category_ids( array( $term_id ) );

		$attribute = new WC_Product_Attribute();
		$attribute->set_id( 0 );
		$attribute->set_name( 'Wariant' );
		$attribute->set_options( wp_list_pluck( $p['variants'], 0 ) );
		$attribute->set_position( 0 );
		$attribute->set_visible( true );
		$attribute->set_variation( true );
		$product->set_attributes( array( $attribute ) );

		$product_id = $product->save();

		foreach ( $p['variants'] as $v ) {
			list( $label, $sub, $price, $old ) = $v;
			$variation = new WC_Product_Variation();
			$variation->set_parent_id( $product_id );
			$variation->set_attributes( array( 'wariant' => $label ) );
			if ( $old ) {
				$variation->set_regular_price( $old );
				$variation->set_sale_price( $price );
			} else {
				$variation->set_regular_price( $price );
			}
			$variation->set_description( $sub );
			$variation->save();
		}

		// Keep the parent product purchasable with a sane default price range.
		$product = wc_get_product( $product_id );
		$product->set_price( $p['variants'][0][2] );
		$product->save();
	}
}

/**
 * Turns on WooCommerce's two built-in payment methods that need no external
 * account or API keys — bank transfer and cash on delivery — so the shop is
 * actually orderable immediately. Real online payments (BLIK, card, fast
 * bank transfer) need a payment provider account (e.g. paynow) and its own
 * plugin/API keys, which only the site owner can set up; these two are a
 * working starting point until that's in place. Never overrides settings
 * the client already changed.
 */
function papernest_setup_wc_payment_gateways() {
	if ( ! get_option( 'woocommerce_bacs_settings' ) ) {
		update_option(
			'woocommerce_bacs_settings',
			array(
				'enabled'     => 'yes',
				'title'       => 'Przelew tradycyjny',
				'description' => 'Dane do przelewu wyślemy w mailu z potwierdzeniem zamówienia.',
			)
		);
	}
	if ( ! get_option( 'woocommerce_cod_settings' ) ) {
		update_option(
			'woocommerce_cod_settings',
			array(
				'enabled'     => 'yes',
				'title'       => 'Za pobraniem',
				'description' => 'Zapłacisz gotówką lub kartą kurierowi przy odbiorze.',
			)
		);
	}
}
