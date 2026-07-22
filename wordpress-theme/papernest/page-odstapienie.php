<?php
/**
 * Template Name: Odstąpienie od umowy
 * The static prototype's version was a non-functional JS demo form. This
 * version is honest about what actually exists: instructions plus a
 * pre-filled mailto: link, until/unless a real order-lookup tool is added.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$email     = papernest_email();
	$mailto    = 'mailto:' . $email . '?subject=' . rawurlencode( 'Odstąpienie od umowy — numer zamówienia' );
	?>

<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Odstąpienie od umowy</span></div>
    <h1><?php the_title(); ?></h1>
    <p>Masz 14 dni na odstąpienie od umowy zawartej na odległość bez podania przyczyny.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">
      <?php
      ob_start();
      ?>
      <div class="card card-pad">
        <span class="eyebrow">Jak to działa</span>
        <h2 style="font-size:1.4rem;margin-bottom:18px">3 kroki do zwrotu</h2>
        <ul class="icon-list">
          <li><?php echo papernest_icon( 'check' ); ?><span><strong style="color:var(--heading)">Krok 1.</strong> Napisz do nas, podając numer zamówienia i adres e-mail użyty przy zakupie.</span></li>
          <li><?php echo papernest_icon( 'check' ); ?><span><strong style="color:var(--heading)">Krok 2.</strong> Prześlij oświadczenie o odstąpieniu na adres <a href="mailto:<?php echo esc_attr( $email ); ?>" style="color:var(--brand-link-green);text-decoration:underline;font-weight:700"><?php echo esc_html( $email ); ?></a> w ciągu 14 dni od otrzymania towaru.</span></li>
          <li><?php echo papernest_icon( 'check' ); ?><span><strong style="color:var(--heading)">Krok 3.</strong> Odeślij produkt na adres: P.H.U „Bobinex” Grzegorz Działkowski, ul. Szczecińska 1A, 72-100 Goleniów.</span></li>
        </ul>
        <a class="btn btn-primary btn-block" style="margin-top:20px" href="<?php echo esc_url( $mailto ); ?>"><?php echo papernest_icon( 'mail' ); ?> Napisz o odstąpieniu od umowy</a>
      </div>
      <?php
      echo papernest_reveal( ob_get_clean() );

      ob_start();
      ?>
      <div class="card card-pad">
        <span class="eyebrow">Pełne warunki</span>
        <h2 style="font-size:1.4rem;margin-bottom:18px">Prawo do odstąpienia</h2>
        <?php the_content(); ?>
        <div class="legal-note" style="margin-top:24px">Pełne warunki odstąpienia od umowy — w tym wyjątki i terminy zwrotu płatności — znajdziesz w dokumencie <a href="<?php echo esc_url( papernest_page_link( 'prawo-do-odstapienia-od-umowy' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">Prawo do odstąpienia od umowy</a>.</div>
      </div>
      <?php
      echo papernest_reveal( ob_get_clean() );
      ?>
    </div>
  </div>
</section>

<?php
endwhile;
get_footer();
