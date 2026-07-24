<?php
/**
 * Template Name: Sklep
 * Product grid — ported from tools/pages_shop.py build_shop(), pulling from
 * the papernest_product CPT instead of a hardcoded PRODUCTS dict.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$products = new WP_Query(
		array(
			'post_type'      => 'papernest_product',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
		)
	);

	$cats = get_terms( array( 'taxonomy' => 'papernest_product_cat', 'hide_empty' => true ) );
	?>

<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Sklep</span></div>
    <h1><?php the_title(); ?></h1>
    <p>Papier w rolkach prosto od producenta — wypełniacz do paczek, papier dla piskląt i tektura budowlana. Zamówienia realizujemy telefonicznie/mailowo lub przez koszyk poniżej.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="shop-toolbar">
      <div class="shop-filters">
        <button class="filter-chip active" data-filter="all">Wszystkie produkty</button>
        <?php foreach ( $cats as $cat ) : ?>
          <button class="filter-chip" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="product-grid cols-4 reveal-stagger">
      <?php if ( $products->have_posts() ) : ?>
        <?php while ( $products->have_posts() ) : $products->the_post(); ?>
          <?php
          $terms  = get_the_terms( get_the_ID(), 'papernest_product_cat' );
          $family = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
          $badge  = papernest_product_badge( get_the_ID() );
          $from   = papernest_product_from_price( get_the_ID() );
          ob_start();
          ?>
          <article class="product-card" data-family="<?php echo esc_attr( $family ); ?>">
            <a href="<?php the_permalink(); ?>" class="thumb">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium' ); ?>
              <?php endif; ?>
              <?php if ( $badge ) : ?><span class="badge"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
            </a>
            <div class="body">
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <div class="meta">
                <?php if ( null !== $from ) : ?>
                  <span class="price">od <span><?php echo esc_html( papernest_format_price( $from ) ); ?></span></span>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Wybierz <?php echo papernest_icon( 'arrow' ); ?></a>
              </div>
            </div>
          </article>
          <?php
          echo papernest_reveal( ob_get_clean() );
        endwhile;
        wp_reset_postdata();
      else :
        ?>
        <div class="notice-box"><?php echo papernest_icon( 'info' ); ?><p><strong>Sklep jest jeszcze pusty.</strong> Dodaj produkty w Produkty → Dodaj produkt.</p></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="notice-box">
      <?php echo papernest_icon( 'info' ); ?>
      <p><strong>Płatności i dostawa.</strong> Zamówienia opłacisz przelewem tradycyjnym lub za pobraniem, wysyłamy Paczkomatem InPost albo kurierem DPD. Szczegóły znajdziesz na stronie <a href="<?php echo esc_url( papernest_page_link( 'platnosc-i-dostawa' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">Płatność i Dostawa</a>.</p>
    </div>
  </div>
</section>

<?php
endwhile;
get_footer();
