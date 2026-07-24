<?php
/**
 * Custom post types the client edits from wp-admin: portfolio use-cases and
 * customer testimonials — both were static, hardcoded content in the
 * prototype; here they become real, editable WordPress content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function papernest_register_cpts() {

	register_post_type(
		'papernest_usecase',
		array(
			'labels'       => array(
				'name'          => __( 'Portfolio / Zastosowania', 'papernest' ),
				'singular_name' => __( 'Zastosowanie', 'papernest' ),
				'add_new_item'  => __( 'Dodaj zastosowanie', 'papernest' ),
				'edit_item'     => __( 'Edytuj zastosowanie', 'papernest' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-images-alt2',
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'zastosowanie' ),
			// Classic editor: simpler and more reliable for the custom
			// meta boxes below than the block editor.
			'show_in_rest' => false,
		)
	);

	register_post_type(
		'papernest_review',
		array(
			'labels'       => array(
				'name'          => __( 'Opinie klientów', 'papernest' ),
				'singular_name' => __( 'Opinia', 'papernest' ),
				'add_new_item'  => __( 'Dodaj opinię', 'papernest' ),
				'edit_item'     => __( 'Edytuj opinię', 'papernest' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'opinia' ),
			// Classic editor: simpler and more reliable for the custom
			// meta boxes below than the block editor.
			'show_in_rest' => false,
		)
	);
}
add_action( 'init', 'papernest_register_cpts' );

/**
 * Extra fields: author name / role for testimonials, decorative icon key for
 * use-cases (chosen from the same icon set the rest of the theme uses).
 */
function papernest_cpt_meta_boxes() {
	add_meta_box( 'papernest_testimonial_meta', __( 'Autor opinii', 'papernest' ), 'papernest_testimonial_meta_box', 'papernest_review', 'side' );
	add_meta_box( 'papernest_usecase_meta', __( 'Ikona kafelka', 'papernest' ), 'papernest_usecase_meta_box', 'papernest_usecase', 'side' );
}
add_action( 'add_meta_boxes', 'papernest_cpt_meta_boxes' );

function papernest_testimonial_meta_box( $post ) {
	wp_nonce_field( 'papernest_save_testimonial_meta', 'papernest_testimonial_nonce' );
	$author = get_post_meta( $post->ID, '_papernest_author', true );
	$role   = get_post_meta( $post->ID, '_papernest_role', true );
	?>
	<p>
		<label for="papernest_author"><?php esc_html_e( 'Imię i nazwisko / firma', 'papernest' ); ?></label>
		<input type="text" class="widefat" id="papernest_author" name="papernest_author" value="<?php echo esc_attr( $author ); ?>">
	</p>
	<p>
		<label for="papernest_role"><?php esc_html_e( 'Rola / firma (druga linia)', 'papernest' ); ?></label>
		<input type="text" class="widefat" id="papernest_role" name="papernest_role" value="<?php echo esc_attr( $role ); ?>">
	</p>
	<p class="description"><?php esc_html_e( 'Tytuł opinii wpisz w polu "Tytuł" u góry, a treść w edytorze poniżej.', 'papernest' ); ?></p>
	<?php
}

function papernest_usecase_meta_box( $post ) {
	wp_nonce_field( 'papernest_save_usecase_meta', 'papernest_usecase_nonce' );
	$icon    = get_post_meta( $post->ID, '_papernest_icon', true );
	$icons   = array_keys( papernest_icons() );
	$current = $icon ? $icon : 'box';
	?>
	<p>
		<label for="papernest_icon"><?php esc_html_e( 'Ikona (widoczna obok podpisu, dopóki nie dodasz zdjęcia)', 'papernest' ); ?></label>
		<select class="widefat" id="papernest_icon" name="papernest_icon">
			<?php foreach ( $icons as $key ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $current, $key ); ?>><?php echo esc_html( $key ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description"><?php esc_html_e( 'Dodaj obrazek wyróżniający, aby zastąpić szary placeholder prawdziwym zdjęciem.', 'papernest' ); ?></p>
	<?php
}

function papernest_save_cpt_meta( $post_id ) {
	if ( isset( $_POST['papernest_testimonial_nonce'] ) && wp_verify_nonce( $_POST['papernest_testimonial_nonce'], 'papernest_save_testimonial_meta' ) ) {
		if ( isset( $_POST['papernest_author'] ) ) {
			update_post_meta( $post_id, '_papernest_author', sanitize_text_field( wp_unslash( $_POST['papernest_author'] ) ) );
		}
		if ( isset( $_POST['papernest_role'] ) ) {
			update_post_meta( $post_id, '_papernest_role', sanitize_text_field( wp_unslash( $_POST['papernest_role'] ) ) );
		}
	}
	if ( isset( $_POST['papernest_usecase_nonce'] ) && wp_verify_nonce( $_POST['papernest_usecase_nonce'], 'papernest_save_usecase_meta' ) ) {
		if ( isset( $_POST['papernest_icon'] ) ) {
			update_post_meta( $post_id, '_papernest_icon', sanitize_key( $_POST['papernest_icon'] ) );
		}
	}
}
add_action( 'save_post', 'papernest_save_cpt_meta' );

/**
 * Initials for the testimonial avatar circle, computed from the author name
 * so editors don't have to type them separately.
 */
function papernest_initials( $name ) {
	$words    = preg_split( '/\s+/', trim( $name ) );
	$initials = '';
	foreach ( array_slice( $words, 0, 2 ) as $word ) {
		$initials .= mb_strtoupper( mb_substr( $word, 0, 1 ) );
	}
	return $initials ? $initials : 'PN';
}

/**
 * Testimonials marquee — pulls from the papernest_testimonial CPT instead of
 * a hardcoded array, but renders identical markup/CSS to the static prototype
 * (assets/css/style.css .testi-slider etc.), including the WCAG 2.2.2
 * pause/resume control and reduced-motion handling already built into main.js.
 */
function papernest_testimonial_marquee() {
	$query = new WP_Query(
		array(
			'post_type'      => 'papernest_review',
			'posts_per_page' => 12,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
		)
	);

	if ( ! $query->have_posts() ) {
		return '';
	}

	$card = function ( $hidden = false ) {
		$author = get_post_meta( get_the_ID(), '_papernest_author', true );
		$role   = get_post_meta( get_the_ID(), '_papernest_role', true );
		$attrs  = $hidden ? ' aria-hidden="true" tabindex="-1"' : '';
		ob_start();
		?>
		<div class="testi-card"<?php echo $attrs; ?>>
			<div class="stars"><?php echo str_repeat( papernest_icon( 'star' ), 5 ); ?></div>
			<b style="color:var(--heading);font-size:.98rem"><?php the_title(); ?></b>
			<blockquote><?php echo wp_kses_post( get_the_content() ); ?></blockquote>
			<div class="who"><span class="avatar"><?php echo esc_html( papernest_initials( $author ) ); ?></span><div><b><?php echo esc_html( $author ); ?></b><span><?php echo esc_html( $role ); ?></span></div></div>
		</div>
		<?php
		return ob_get_clean();
	};

	$cards = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$cards .= $card( false );
	}
	$query->rewind_posts();
	$cards_dup = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$cards_dup .= $card( true );
	}
	wp_reset_postdata();

	ob_start();
	?>
	<div class="testi-slider" data-testi-slider>
		<div class="testimonial-track">
			<div class="testi-rail"><?php echo $cards . $cards_dup; ?></div>
		</div>
		<div class="testi-nav">
			<button type="button" class="testi-pause" data-testi-toggle aria-pressed="false" aria-label="Zatrzymaj automatyczne przewijanie opinii"><?php echo papernest_icon( 'pause' ); ?></button>
		</div>
	</div>
	<?php
	return papernest_reveal( ob_get_clean() );
}

/**
 * Portfolio "use case" tiles — pulls from papernest_usecase CPT, showing the
 * client's uploaded photo when set, otherwise the decorative icon.
 */
function papernest_usecase_tiles( $limit = 12 ) {
	$query = new WP_Query(
		array(
			'post_type'      => 'papernest_usecase',
			'posts_per_page' => $limit,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
		)
	);
	if ( ! $query->have_posts() ) {
		return '';
	}
	$tiles = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$icon_key = get_post_meta( get_the_ID(), '_papernest_icon', true );
		$icon_key = $icon_key ? $icon_key : 'box';
		ob_start();
		if ( has_post_thumbnail() ) {
			?>
			<div class="portfolio-tile portfolio-tile-photo">
				<?php the_post_thumbnail( 'papernest-portfolio' ); ?>
				<span class="cap"><?php echo papernest_icon( $icon_key ); ?><?php the_title(); ?></span>
			</div>
			<?php
		} else {
			?>
			<div class="portfolio-tile">
				<span class="ph-ic"><?php echo papernest_icon( 'image' ); ?></span>
				<span class="cap"><?php echo papernest_icon( $icon_key ); ?><?php the_title(); ?></span>
			</div>
			<?php
		}
		$tiles .= papernest_reveal( ob_get_clean() );
	}
	wp_reset_postdata();
	return $tiles;
}
