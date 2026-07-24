<?php
/**
 * Custom product type — replaces WooCommerce. A product has a name,
 * description, category, gallery, and a repeatable list of price variants
 * (e.g. "1 rolka" / "2 rolki" / "4 rolki" / "Paleta"), matching exactly what
 * the static prototype and the WooCommerce version both modeled, but without
 * requiring the WooCommerce plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_register_product_cpt() {
	register_post_type(
		'papernest_product',
		array(
			'labels'       => array(
				'name'          => __( 'Produkty', 'papernest' ),
				'singular_name' => __( 'Produkt', 'papernest' ),
				'add_new_item'  => __( 'Dodaj produkt', 'papernest' ),
				'edit_item'     => __( 'Edytuj produkt', 'papernest' ),
			),
			'public'       => true,
			'has_archive'  => false, // custom archive lives at page-sklep.php instead.
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-products',
			'menu_position' => 20,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'produkt' ),
			// Classic editor: simpler and more reliable for the custom
			// variants/gallery meta boxes below than the block editor.
			'show_in_rest' => false,
		)
	);

	register_taxonomy(
		'papernest_product_cat',
		'papernest_product',
		array(
			'labels'            => array(
				'name'          => __( 'Kategorie produktów', 'papernest' ),
				'singular_name' => __( 'Kategoria produktu', 'papernest' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'kategoria-produktu' ),
		)
	);
}
add_action( 'init', 'papernest_register_product_cpt' );

/* ---------------------------------------------------------------- Meta box ---- */

function papernest_product_meta_boxes() {
	add_meta_box( 'papernest_variants', __( 'Warianty i ceny', 'papernest' ), 'papernest_variants_meta_box', 'papernest_product', 'normal', 'high' );
	add_meta_box( 'papernest_gallery', __( 'Galeria zdjęć produktu', 'papernest' ), 'papernest_gallery_meta_box', 'papernest_product', 'side' );
	add_meta_box( 'papernest_product_badge', __( 'Etykieta na karcie produktu', 'papernest' ), 'papernest_product_badge_meta_box', 'papernest_product', 'side' );
}
add_action( 'add_meta_boxes', 'papernest_product_meta_boxes' );

/**
 * Variants: repeatable rows [label, sub, price, old_price]. Stored as a
 * plain array in post meta — prices are always read server-side from here,
 * never trusted from the browser (see inc/shop/cart.php).
 */
function papernest_get_variants( $product_id ) {
	$variants = get_post_meta( $product_id, '_papernest_variants', true );
	return is_array( $variants ) ? $variants : array();
}

function papernest_variants_meta_box( $post ) {
	wp_nonce_field( 'papernest_save_variants', 'papernest_variants_nonce' );
	$variants = papernest_get_variants( $post->ID );
	if ( empty( $variants ) ) {
		$variants = array( array( 'label' => '', 'sub' => '', 'price' => '', 'old_price' => '' ) );
	}
	?>
	<table class="widefat" id="papernest-variants-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Nazwa wariantu (np. "1 rolka")', 'papernest' ); ?></th>
				<th><?php esc_html_e( 'Podtytuł (np. "420 mb x 35 cm")', 'papernest' ); ?></th>
				<th><?php esc_html_e( 'Cena (zł)', 'papernest' ); ?></th>
				<th><?php esc_html_e( 'Cena przed obniżką (opcjonalnie)', 'papernest' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $variants as $i => $v ) : ?>
			<tr>
				<td><input type="text" class="widefat" name="papernest_variant_label[]" value="<?php echo esc_attr( $v['label'] ?? '' ); ?>"></td>
				<td><input type="text" class="widefat" name="papernest_variant_sub[]" value="<?php echo esc_attr( $v['sub'] ?? '' ); ?>"></td>
				<td><input type="text" inputmode="decimal" class="widefat" name="papernest_variant_price[]" value="<?php echo esc_attr( $v['price'] ?? '' ); ?>"></td>
				<td><input type="text" inputmode="decimal" class="widefat" name="papernest_variant_old_price[]" value="<?php echo esc_attr( $v['old_price'] ?? '' ); ?>"></td>
				<td><button type="button" class="button papernest-remove-variant">&times;</button></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="button" class="button" id="papernest-add-variant"><?php esc_html_e( '+ Dodaj wariant', 'papernest' ); ?></button></p>
	<p class="description"><?php esc_html_e( 'Ceny wpisuj z kropką lub przecinkiem, np. 45.00 albo 45,00. Cena przed obniżką jest opcjonalna — jeśli ją podasz, pokaże się przekreślona.', 'papernest' ); ?></p>
	<script>
	(function(){
		var table = document.getElementById('papernest-variants-table').querySelector('tbody');
		document.getElementById('papernest-add-variant').addEventListener('click', function(){
			var row = table.rows[0].cloneNode(true);
			Array.prototype.forEach.call(row.querySelectorAll('input'), function(i){ i.value = ''; });
			table.appendChild(row);
		});
		table.addEventListener('click', function(e){
			if (e.target.classList.contains('papernest-remove-variant')) {
				if (table.rows.length > 1) e.target.closest('tr').remove();
			}
		});
	})();
	</script>
	<?php
}

function papernest_gallery_meta_box( $post ) {
	wp_nonce_field( 'papernest_save_gallery', 'papernest_gallery_nonce' );
	$gallery = get_post_meta( $post->ID, '_papernest_gallery', true );
	$gallery = is_array( $gallery ) ? $gallery : array();
	?>
	<div id="papernest-gallery-preview" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px">
		<?php foreach ( $gallery as $att_id ) : ?>
			<?php echo wp_get_attachment_image( $att_id, array( 60, 60 ), false, array( 'data-id' => $att_id ) ); ?>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="papernest_gallery_ids" id="papernest_gallery_ids" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>">
	<button type="button" class="button" id="papernest-pick-gallery"><?php esc_html_e( 'Wybierz zdjęcia', 'papernest' ); ?></button>
	<p class="description"><?php esc_html_e( 'Do 4 dodatkowych zdjęć (miniatury pod głównym zdjęciem produktu).', 'papernest' ); ?></p>
	<?php
}

function papernest_product_badge_meta_box( $post ) {
	wp_nonce_field( 'papernest_save_badge', 'papernest_badge_nonce' );
	$badge = get_post_meta( $post->ID, '_papernest_badge', true );
	?>
	<input type="text" class="widefat" name="papernest_badge" value="<?php echo esc_attr( $badge ); ?>" placeholder="np. Ekologiczny">
	<?php
}

function papernest_product_admin_scripts( $hook ) {
	global $post_type;
	if ( 'papernest_product' !== $post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_add_inline_script(
		'media-editor',
		"jQuery(function($){
			var frame;
			$('#papernest-pick-gallery').on('click', function(e){
				e.preventDefault();
				if (frame) { frame.open(); return; }
				frame = wp.media({ title: 'Wybierz zdjęcia produktu', multiple: true, library: { type: 'image' } });
				frame.on('select', function(){
					var ids = frame.state().get('selection').map(function(a){ return a.id; });
					$('#papernest_gallery_ids').val(ids.join(','));
					var preview = $('#papernest-gallery-preview').empty();
					frame.state().get('selection').each(function(a){
						preview.append($('<img>').attr('src', a.attributes.sizes && a.attributes.sizes.thumbnail ? a.attributes.sizes.thumbnail.url : a.attributes.url).css({width:60,height:60,objectFit:'cover'}));
					});
				});
				frame.open();
			});
		});"
	);
}
add_action( 'admin_enqueue_scripts', 'papernest_product_admin_scripts' );

/* ------------------------------------------------------------------- Save ---- */

function papernest_save_product_meta( $post_id ) {
	if ( 'papernest_product' !== get_post_type( $post_id ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['papernest_variants_nonce'] ) && wp_verify_nonce( $_POST['papernest_variants_nonce'], 'papernest_save_variants' ) ) {
		$labels = isset( $_POST['papernest_variant_label'] ) ? (array) wp_unslash( $_POST['papernest_variant_label'] ) : array();
		$subs   = isset( $_POST['papernest_variant_sub'] ) ? (array) wp_unslash( $_POST['papernest_variant_sub'] ) : array();
		$prices = isset( $_POST['papernest_variant_price'] ) ? (array) wp_unslash( $_POST['papernest_variant_price'] ) : array();
		$olds   = isset( $_POST['papernest_variant_old_price'] ) ? (array) wp_unslash( $_POST['papernest_variant_old_price'] ) : array();

		$variants = array();
		foreach ( $labels as $i => $label ) {
			$label = sanitize_text_field( $label );
			$price = papernest_parse_price( $prices[ $i ] ?? '' );
			if ( '' === $label || null === $price ) {
				continue;
			}
			$old_price = papernest_parse_price( $olds[ $i ] ?? '' );
			$variants[] = array(
				'label'     => $label,
				'sub'       => sanitize_text_field( $subs[ $i ] ?? '' ),
				'price'     => $price,
				'old_price' => $old_price,
			);
		}
		update_post_meta( $post_id, '_papernest_variants', $variants );
	}

	if ( isset( $_POST['papernest_gallery_nonce'] ) && wp_verify_nonce( $_POST['papernest_gallery_nonce'], 'papernest_save_gallery' ) ) {
		$ids = isset( $_POST['papernest_gallery_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['papernest_gallery_ids'] ) ) : '';
		$ids = array_filter( array_map( 'absint', explode( ',', $ids ) ) );
		update_post_meta( $post_id, '_papernest_gallery', array_values( $ids ) );
	}

	if ( isset( $_POST['papernest_badge_nonce'] ) && wp_verify_nonce( $_POST['papernest_badge_nonce'], 'papernest_save_badge' ) ) {
		update_post_meta( $post_id, '_papernest_badge', sanitize_text_field( wp_unslash( $_POST['papernest_badge'] ?? '' ) ) );
	}
}
add_action( 'save_post', 'papernest_save_product_meta' );

/**
 * Parses a price string ("45,00", "45.00", "45") into a float with 2
 * decimals, or null if it's not a usable positive number. Centralised here
 * because prices are entered with either decimal separator.
 */
function papernest_parse_price( $raw ) {
	$raw = trim( (string) $raw );
	if ( '' === $raw ) {
		return null;
	}
	$raw = str_replace( ',', '.', $raw );
	if ( ! is_numeric( $raw ) || (float) $raw < 0 ) {
		return null;
	}
	return round( (float) $raw, 2 );
}

function papernest_format_price( $amount ) {
	return number_format( (float) $amount, 2, ',', ' ' ) . ' zł';
}

/**
 * The lowest-priced variant's price, used for "od 45,00 zł" on product cards.
 */
function papernest_product_from_price( $product_id ) {
	$variants = papernest_get_variants( $product_id );
	if ( empty( $variants ) ) {
		return null;
	}
	$prices = wp_list_pluck( $variants, 'price' );
	return min( array_map( 'floatval', $prices ) );
}

function papernest_product_badge( $product_id ) {
	$badge = get_post_meta( $product_id, '_papernest_badge', true );
	if ( $badge ) {
		return $badge;
	}
	$terms = get_the_terms( $product_id, 'papernest_product_cat' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}
