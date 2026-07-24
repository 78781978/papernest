<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<section class="section" style="padding-block:120px">
  <div class="container" style="text-align:center">
    <span class="eyebrow" style="justify-content:center">Błąd 404</span>
    <h1 class="text-balance">Nie znaleźliśmy tej strony</h1>
    <p style="color:var(--ink-600);margin:16px 0 32px">Strona mogła zostać przeniesiona lub usunięta. Wróć na stronę główną albo przejdź do sklepu.</p>
    <div class="hero-cta" style="justify-content:center">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Strona główna</a>
      <a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : papernest_page_link( 'sklep' ) ); ?>" class="btn btn-outline">Przejdź do sklepu</a>
    </div>
  </div>
</section>
<?php get_footer(); ?>
