<?php
/**
 * Template Name: Zamówienie
 * Checkout — ported from zamowienie.html. Real order creation via
 * inc/shop/checkout.php (admin-post.php, no AJAX — a normal form submit is
 * the most robust way to handle something as important as placing an order).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$lines   = papernest_get_cart();
	$items_total = papernest_cart_total();
	$s       = papernest_shop_settings();
	$cost_paczkomat = papernest_shipping_cost( 'paczkomat', $items_total );
	$cost_kurier    = papernest_shipping_cost( 'kurier', $items_total );

	$just_placed = isset( $_GET['zlozono'] ) && ! empty( $_SESSION['papernest_last_order'] );
	$error       = isset( $_GET['blad'] ) ? sanitize_key( wp_unslash( $_GET['blad'] ) ) : '';

	$error_messages = array(
		'sesja'        => 'Sesja wygasła — spróbuj złożyć zamówienie ponownie.',
		'pusty-koszyk' => 'Twój koszyk jest pusty.',
		'dane'         => 'Uzupełnij poprawnie imię i nazwisko, e-mail i telefon.',
		'dostawa'      => 'Wybierz sposób dostawy.',
		'paczkomat'    => 'Podaj kod paczkomatu.',
		'adres'        => 'Uzupełnij pełny adres dostawy.',
		'zamowienie'   => 'Nie udało się złożyć zamówienia — spróbuj ponownie lub zadzwoń.',
	);
	?>

<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <a href="<?php echo esc_url( papernest_cart_link() ); ?>">Koszyk</a> <span>/</span> <span>Zamówienie</span></div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">

    <?php if ( $just_placed ) : ?>
      <?php $order = $_SESSION['papernest_last_order']; unset( $_SESSION['papernest_last_order'] ); ?>
      <div class="notice-box">
        <?php echo papernest_icon( 'check' ); ?>
        <p><strong>Dziękujemy! Twoje zamówienie <?php echo esc_html( $order['number'] ); ?> zostało przyjęte.</strong> Potwierdzenie i dalsze instrukcje wysłaliśmy na podany adres e-mail. W razie pytań dzwoń pod <?php echo esc_html( papernest_phone_display() ); ?>.</p>
      </div>
      <a href="<?php echo esc_url( papernest_shop_link() ); ?>" class="btn btn-primary" style="margin-top:20px">Wróć do sklepu</a>

    <?php elseif ( empty( $lines ) ) : ?>
      <div class="notice-box">
        <?php echo papernest_icon( 'info' ); ?>
        <p><strong>Twój koszyk jest pusty.</strong> Przejdź do <a href="<?php echo esc_url( papernest_shop_link() ); ?>" style="color:var(--brand-link-green);text-decoration:underline">sklepu</a>, żeby dodać produkty.</p>
      </div>

    <?php else : ?>

      <?php if ( $error && isset( $error_messages[ $error ] ) ) : ?>
        <div class="notice-box" style="border-color:#b3261e">
          <?php echo papernest_icon( 'info' ); ?>
          <p><strong><?php echo esc_html( $error_messages[ $error ] ); ?></strong></p>
        </div>
      <?php endif; ?>

      <div class="two-col cols-sidebar" style="align-items:start">
        <div class="reveal"><div class="card card-pad">
          <h2 style="font-size:1.2rem;margin-bottom:18px">Dane do wysyłki</h2>
          <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="papernest-checkout-form">
            <?php wp_nonce_field( 'papernest_checkout', 'papernest_checkout_nonce' ); ?>
            <input type="hidden" name="action" value="papernest_checkout">
            <div style="position:absolute;left:-9999px" aria-hidden="true">
              <label for="website">Zostaw puste</label>
              <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-field"><label for="co-name">Imię i nazwisko</label><input id="co-name" name="name" type="text" required></div>
            <div class="form-row">
              <div class="form-field"><label for="co-email">Adres e-mail</label><input id="co-email" name="email" type="email" required></div>
              <div class="form-field"><label for="co-phone">Telefon</label><input id="co-phone" name="phone" type="tel" required></div>
            </div>

            <div class="variant-group">
              <h4>Sposób dostawy</h4>
              <div class="variant-options" data-role="shipping-method">
                <button type="button" class="variant-chip active" data-value="paczkomat" data-cost="<?php echo esc_attr( number_format( $cost_paczkomat, 2, '.', '' ) ); ?>">Paczkomat InPost<br><small style="font-weight:500;opacity:.7"><?php echo esc_html( papernest_format_price( $cost_paczkomat ) ); ?></small></button>
                <button type="button" class="variant-chip" data-value="kurier" data-cost="<?php echo esc_attr( number_format( $cost_kurier, 2, '.', '' ) ); ?>">Kurier DPD<br><small style="font-weight:500;opacity:.7"><?php echo esc_html( papernest_format_price( $cost_kurier ) ); ?></small></button>
              </div>
              <input type="hidden" name="shipping_method" id="shipping_method" value="paczkomat">
            </div>

            <div data-show-when="paczkomat">
              <div class="form-field"><label for="co-paczkomat">Kod paczkomatu</label><input id="co-paczkomat" name="paczkomat_code" type="text" placeholder="np. GLE01A"></div>
              <p class="text-muted" style="font-size:.82rem;margin-top:-8px">Kod paczkomatu znajdziesz na <a href="https://inpost.pl/znajdz-paczkomat" target="_blank" rel="noopener" style="color:var(--brand-link-green)">mapie InPost</a>.</p>
            </div>
            <div data-show-when="kurier" hidden>
              <div class="form-field"><label for="co-street">Ulica i numer</label><input id="co-street" name="street" type="text"></div>
              <div class="form-row">
                <div class="form-field"><label for="co-postal">Kod pocztowy</label><input id="co-postal" name="postal_code" type="text" placeholder="72-100"></div>
                <div class="form-field"><label for="co-city">Miejscowość</label><input id="co-city" name="city" type="text" placeholder="Goleniów"></div>
              </div>
            </div>

            <div class="variant-group">
              <h4>Sposób płatności</h4>
              <div class="variant-options" data-role="payment-method">
                <button type="button" class="variant-chip active" data-value="przelew">Przelew tradycyjny</button>
                <?php if ( '1' === $s['cod_enabled'] ) : ?>
                  <button type="button" class="variant-chip" data-value="pobranie" data-requires="kurier" disabled>Za pobraniem</button>
                <?php endif; ?>
              </div>
              <input type="hidden" name="payment_method" id="payment_method" value="przelew">
              <p class="text-muted" style="font-size:.82rem;margin-top:8px">Płatności online (BLIK, karta) pojawią się tutaj, gdy tylko zostaną podłączone.</p>
            </div>

            <div class="form-field"><label for="co-note">Uwagi do zamówienia (opcjonalnie)</label><textarea id="co-note" name="note" rows="3"></textarea></div>

            <button class="btn btn-primary btn-block" type="submit" style="margin-top:24px"><?php echo papernest_icon( 'shield' ); ?> Zamawiam</button>
          </form>
        </div></div>

        <div class="reveal"><div class="summary-card">
          <h3 style="font-size:1.1rem;margin-bottom:16px">Twoje zamówienie</h3>
          <?php foreach ( $lines as $line ) : ?>
            <div class="summary-row"><span><?php echo esc_html( $line['name'] . ' (' . $line['variant']['label'] . ') × ' . $line['qty'] ); ?></span><span><?php echo esc_html( papernest_format_price( $line['line_total'] ) ); ?></span></div>
          <?php endforeach; ?>
          <div class="summary-row"><span>Dostawa</span><span class="papernest-shipping-cost" data-paczkomat="<?php echo esc_attr( papernest_format_price( $cost_paczkomat ) ); ?>" data-kurier="<?php echo esc_attr( papernest_format_price( $cost_kurier ) ); ?>"><?php echo esc_html( papernest_format_price( $cost_paczkomat ) ); ?></span></div>
          <div class="summary-row total"><span>Razem</span><span class="papernest-order-total" data-items-total="<?php echo esc_attr( $items_total ); ?>" data-paczkomat="<?php echo esc_attr( $cost_paczkomat ); ?>" data-kurier="<?php echo esc_attr( $cost_kurier ); ?>"><?php echo esc_html( papernest_format_price( $items_total + $cost_paczkomat ) ); ?></span></div>
        </div></div>
      </div>

    <?php endif; ?>
  </div>
</section>

<?php
endwhile;
get_footer();
