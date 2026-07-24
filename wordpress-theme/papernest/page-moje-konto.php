<?php
/**
 * Template Name: Status zamówienia
 * There's no customer login/account system in this build (out of scope —
 * see INSTALACJA.md) — order confirmation and shipped notices go out by
 * e-mail instead, and this page just explains how to reach the shop for a
 * status update.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	?>

<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Status zamówienia</span></div>
    <h1><?php the_title(); ?></h1>
    <p>Potwierdzenie zamówienia i informację o wysyłce wysyłamy e-mailem. Potrzebujesz szybszej odpowiedzi? Napisz lub zadzwoń.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="card card-pad" style="max-width:640px;margin:0 auto">
      <span class="eyebrow">Sprawdź status</span>
      <h2 style="font-size:1.3rem;margin-bottom:14px">Numer zamówienia pod ręką?</h2>
      <p style="color:var(--ink-600);line-height:1.75;margin-bottom:18px">Napisz do nas numer zamówienia (znajdziesz go w mailu z potwierdzeniem), a sprawdzimy status i odpiszemy.</p>
      <a class="btn btn-primary btn-block" href="mailto:<?php echo esc_attr( papernest_email() ); ?>?subject=<?php echo rawurlencode( 'Status zamówienia' ); ?>"><?php echo papernest_icon( 'mail' ); ?> Napisz o status zamówienia</a>
      <a class="btn btn-outline btn-block" style="margin-top:10px" href="<?php echo esc_url( papernest_phone_tel() ); ?>"><?php echo papernest_icon( 'phone' ); ?> Zadzwoń: <?php echo esc_html( papernest_phone_display() ); ?></a>
    </div>
  </div>
</section>

<?php
endwhile;
get_footer();
