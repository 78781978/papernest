<?php
/**
 * Template Name: Koszyk
 * Ported from koszyk.html — cart-table + summary-card layout. Quantity/remove
 * are plain forms (work without JS); "Dodaj do koszyka" on product pages is
 * the only AJAX-driven part (assets/js/shop.js).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$lines = papernest_get_cart();
	$total = papernest_cart_total();
	?>

<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Koszyk</span></div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( empty( $lines ) ) : ?>
      <div class="notice-box">
        <?php echo papernest_icon( 'info' ); ?>
        <p><strong>Twój koszyk jest pusty.</strong> Przejdź do <a href="<?php echo esc_url( papernest_shop_link() ); ?>" style="color:var(--brand-link-green);text-decoration:underline">sklepu</a>, żeby dodać produkty.</p>
      </div>
    <?php else : ?>
      <div class="two-col cols-sidebar" style="align-items:start">
        <div class="reveal"><div class="card card-pad" style="overflow-x:auto">
          <table class="cart-table">
            <thead><tr><th>Produkt</th><th>Cena</th><th>Ilość</th><th>Suma</th><th></th></tr></thead>
            <tbody>
            <?php foreach ( $lines as $line ) : ?>
              <tr>
                <td>
                  <div class="cart-item">
                    <div class="thumb">
                      <?php if ( $line['thumb'] ) : ?>
                        <img src="<?php echo esc_url( $line['thumb'] ); ?>" alt="">
                      <?php endif; ?>
                    </div>
                    <div><b><?php echo esc_html( $line['name'] ); ?></b><span><?php echo esc_html( $line['variant']['label'] . ' — ' . $line['variant']['sub'] ); ?></span></div>
                  </div>
                </td>
                <td><?php echo esc_html( papernest_format_price( $line['unit_price'] ) ); ?></td>
                <td>
                  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="qty-input">
                    <?php wp_nonce_field( 'papernest_cart_form', 'papernest_cart_nonce' ); ?>
                    <input type="hidden" name="action" value="papernest_cart_form_update">
                    <input type="hidden" name="key" value="<?php echo esc_attr( $line['key'] ); ?>">
                    <button class="minus" type="submit" name="qty" value="<?php echo esc_attr( max( 1, $line['qty'] - 1 ) ); ?>" aria-label="Zmniejsz ilość"><?php echo papernest_icon( 'minus' ); ?></button>
                    <input type="text" value="<?php echo esc_attr( $line['qty'] ); ?>" readonly aria-label="Ilość">
                    <button class="plus" type="submit" name="qty" value="<?php echo esc_attr( $line['qty'] + 1 ); ?>" aria-label="Zwiększ ilość"><?php echo papernest_icon( 'plus' ); ?></button>
                  </form>
                </td>
                <td style="font-weight:700;color:var(--heading)"><?php echo esc_html( papernest_format_price( $line['line_total'] ) ); ?></td>
                <td>
                  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <?php wp_nonce_field( 'papernest_cart_form', 'papernest_cart_nonce' ); ?>
                    <input type="hidden" name="action" value="papernest_cart_form_remove">
                    <input type="hidden" name="key" value="<?php echo esc_attr( $line['key'] ); ?>">
                    <button class="icon-btn" style="width:34px;height:34px" type="submit" aria-label="Usuń">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 6h16M9 6V4h6v2M6 6l1 14h10l1-14"/></svg>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div></div>
        <div class="reveal"><div class="summary-card">
          <h3 style="font-size:1.1rem;margin-bottom:16px">Podsumowanie zamówienia</h3>
          <div class="summary-row"><span>Wartość produktów</span><span><?php echo esc_html( papernest_format_price( $total ) ); ?></span></div>
          <div class="summary-row total"><span>Razem</span><span><?php echo esc_html( papernest_format_price( $total ) ); ?></span></div>
          <p class="text-muted" style="font-size:.82rem;margin-top:6px">Koszt dostawy wyliczysz w kolejnym kroku.</p>
          <a href="<?php echo esc_url( papernest_checkout_link() ); ?>" class="btn btn-primary btn-block" style="margin-top:20px">Przejdź do zamówienia <?php echo papernest_icon( 'arrow' ); ?></a>
          <a href="<?php echo esc_url( papernest_shop_link() ); ?>" class="btn btn-outline btn-block" style="margin-top:10px">Kontynuuj zakupy</a>
        </div></div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php
endwhile;
get_footer();
