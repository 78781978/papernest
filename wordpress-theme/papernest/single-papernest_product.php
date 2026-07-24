<?php
/**
 * Single product page — ported from tools/pages_shop.py build_products().
 * Variant price-switching reuses the exact .variant-chip/.pd-info JS
 * already in assets/js/main.js; "Dodaj do koszyka" is wired up in
 * assets/js/shop.js against the currently active variant chip.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$product_id = get_the_ID();
	$variants   = papernest_get_variants( $product_id );
	$gallery    = get_post_meta( $product_id, '_papernest_gallery', true );
	$gallery    = is_array( $gallery ) ? $gallery : array();
	$badge      = papernest_product_badge( $product_id );

	$variants_html = '';
	foreach ( $variants as $i => $v ) {
		$active   = ( 0 === $i ) ? ' active' : '';
		$old_attr = $v['old_price'] ? ' data-old-price="' . esc_attr( number_format( (float) $v['old_price'], 2, '.', '' ) ) . '"' : '';
		$variants_html .= sprintf(
			'<button type="button" class="variant-chip%1$s" data-variant-index="%2$d" data-price="%3$s"%4$s>%5$s<br><small style="font-weight:500;opacity:.7">%6$s</small></button>',
			$active,
			$i,
			esc_attr( number_format( (float) $v['price'], 2, '.', '' ) ),
			$old_attr,
			esc_html( $v['label'] ),
			esc_html( $v['sub'] )
		);
	}

	$first = $variants[0] ?? array( 'price' => 0, 'old_price' => null );
	$save_pct = 0;
	if ( ! empty( $first['old_price'] ) && $first['old_price'] > 0 ) {
		$save_pct = round( ( 1 - $first['price'] / $first['old_price'] ) * 100 );
	}

	$others = new WP_Query(
		array(
			'post_type'      => 'papernest_product',
			'posts_per_page' => 3,
			'post__not_in'   => array( $product_id ),
			'orderby'        => 'rand',
		)
	);
	?>

<section class="page-hero" style="padding-block:40px 32px">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <a href="<?php echo esc_url( papernest_shop_link() ); ?>">Sklep</a> <span>/</span> <span><?php the_title(); ?></span></div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="pd-grid">
      <div class="pd-gallery reveal">
        <div class="frame">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large' ); ?>
          <?php else : ?>
            <?php papernest_photo_slot( '', 'Zdjęcie produktu' ); ?>
          <?php endif; ?>
        </div>
        <div class="pd-gallery-thumbs">
          <?php
          $slots = 4;
          for ( $i = 0; $i < $slots; $i++ ) :
              if ( isset( $gallery[ $i ] ) ) :
                  echo wp_get_attachment_image( $gallery[ $i ], 'thumbnail', false, array( 'class' => 'photo-slot photo-slot-filled' ) );
              else :
                  papernest_photo_slot( '', 'Zdjęcie ' . ( $i + 1 ) );
              endif;
          endfor;
          ?>
        </div>
      </div>
      <div class="pd-info reveal">
        <?php if ( $badge ) : ?><span class="tag-pill"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
        <h1 style="margin-top:16px;font-size:clamp(1.8rem,3vw,2.5rem)"><?php the_title(); ?></h1>

        <div class="price-row">
          <span class="price-now"><?php echo esc_html( papernest_format_price( $first['price'] ) ); ?></span>
          <span class="price-old" <?php echo empty( $first['old_price'] ) ? 'style="display:none"' : ''; ?>><?php echo esc_html( ! empty( $first['old_price'] ) ? papernest_format_price( $first['old_price'] ) : '' ); ?></span>
          <span class="save-badge" <?php echo $save_pct <= 0 ? 'style="display:none"' : ''; ?>>-<?php echo esc_html( $save_pct ); ?>%</span>
        </div>

        <?php echo papernest_product_contact_block(); ?>

        <?php if ( ! empty( $variants ) ) : ?>
        <div class="variant-group">
          <h4>Wybierz wariant</h4>
          <div class="variant-options"><?php echo $variants_html; // phpcs:ignore ?></div>
        </div>
        <?php endif; ?>

        <div class="qty-row">
          <div class="qty-input">
            <button class="minus" type="button" aria-label="Zmniejsz ilość"><?php echo papernest_icon( 'minus' ); ?></button>
            <input type="text" value="1" inputmode="numeric" aria-label="Ilość">
            <button class="plus" type="button" aria-label="Zwiększ ilość"><?php echo papernest_icon( 'plus' ); ?></button>
          </div>
          <button class="btn btn-primary btn-block" type="button" data-add-to-cart data-product-id="<?php echo esc_attr( $product_id ); ?>"><?php echo papernest_icon( 'cart' ); ?> Dodaj do koszyka</button>
        </div>
        <p class="papernest-cart-feedback" role="status" aria-live="polite" style="min-height:1.5em;margin-top:10px;font-weight:600;color:var(--brand-link-green)"></p>

        <div class="trust-row">
          <div class="item"><?php echo papernest_icon( 'truck' ); ?> Wysyłka InPost / DPD</div>
          <div class="item"><?php echo papernest_icon( 'shield' ); ?> Płatność przelewem/za pobraniem</div>
          <div class="item"><?php echo papernest_icon( 'leaf' ); ?> 100% recykling</div>
        </div>

        <div class="tabs">
          <button class="tab-btn active" data-tab="opis">Opis produktu</button>
          <button class="tab-btn" data-tab="dostawa">Dostawa i zwroty</button>
        </div>
        <div class="tab-panel active" data-tab="opis"><?php the_content(); ?></div>
        <div class="tab-panel" data-tab="dostawa">
          <p style="font-size:.95rem;line-height:1.75;color:var(--ink-600)">Wysyłka Paczkomatem InPost lub kurierem DPD. Zamówienia hurtowe i paletowe realizujemy indywidualnie — <a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">skontaktuj się z nami</a>. Zwroty na zasadach opisanych w <a href="<?php echo esc_url( papernest_page_link( 'prawo-do-odstapienia-od-umowy' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">prawie odstąpienia od umowy</a>.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ( $others->have_posts() ) : ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Zobacz też</span>
      <h2>Pozostałe produkty PaperNest</h2>
    </div>
    <div class="product-grid">
      <?php while ( $others->have_posts() ) : $others->the_post(); ?>
        <?php $from = papernest_product_from_price( get_the_ID() ); ?>
        <article class="product-card">
          <a href="<?php the_permalink(); ?>" class="thumb">
            <?php if ( has_post_thumbnail() ) : ?><?php the_post_thumbnail( 'medium' ); ?><?php endif; ?>
            <span class="badge"><?php echo esc_html( papernest_product_badge( get_the_ID() ) ); ?></span>
          </a>
          <div class="body">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="meta">
              <?php if ( null !== $from ) : ?><span class="price">od <span><?php echo esc_html( papernest_format_price( $from ) ); ?></span></span><?php endif; ?>
              <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Zobacz <?php echo papernest_icon( 'arrow' ); ?></a>
            </div>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
endwhile;
get_footer();
