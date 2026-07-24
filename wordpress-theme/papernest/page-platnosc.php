<?php
/**
 * Template Name: Płatność i Dostawa
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
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Płatność i Dostawa</span></div>
    <h1><?php the_title(); ?></h1>
    <p>W PaperNest zależy nam na prostym i bezpiecznym procesie zakupu. Poniżej znajdziesz informacje o metodach płatności oraz sposobach dostawy zamówień.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">
      <?php
      ob_start();
      ?>
      <div class="card card-pad">
        <span class="eyebrow">Płatności</span>
        <h2 style="font-size:1.4rem;margin-bottom:14px">Metody płatności</h2>
        <p style="color:var(--ink-600);line-height:1.75;margin-bottom:18px">Zamówienie opłacisz jedną z dwóch metod:</p>
        <ul class="icon-list">
          <li><?php echo papernest_icon( 'shield' ); ?><span><strong style="color:var(--heading)">Przelew tradycyjny</strong> — dane do przelewu (numer konta i kwotę) wyślemy w mailu z potwierdzeniem zamówienia.</span></li>
          <li><?php echo papernest_icon( 'truck' ); ?><span><strong style="color:var(--heading)">Za pobraniem</strong> — płatność gotówką lub kartą u kuriera, przy dostawie kurierem DPD.</span></li>
        </ul>
        <p class="text-muted" style="font-size:.85rem;margin-top:14px">Szybkie płatności online (BLIK, karta, przelew online) pojawią się w sklepie, gdy tylko zostaną podłączone.</p>
      </div>
      <?php
      echo papernest_reveal( ob_get_clean() );

      ob_start();
      ?>
      <div class="card card-pad">
        <span class="eyebrow">Dostawa</span>
        <h2 style="font-size:1.4rem;margin-bottom:14px">Metody dostawy</h2>
        <p style="color:var(--ink-600);line-height:1.75;margin-bottom:18px">Dostępne metody dostawy zależą od rodzaju produktu, gabarytu zamówienia oraz adresu dostawy. Dla standardowych przesyłek korzystamy z usług InPost oraz DPD.</p>
        <ul class="icon-list">
          <li><?php echo papernest_icon( 'box' ); ?><span><strong style="color:var(--heading)">InPost Paczkomat®</strong> — odbiór w wybranym automacie paczkowym, wygodny dla mniejszych zamówień.</span></li>
          <li><?php echo papernest_icon( 'truck' ); ?><span><strong style="color:var(--heading)">Kurier DPD</strong> — dostawa na wskazany adres, możliwa płatność za pobraniem.</span></li>
        </ul>
        <div class="logo-strip"><?php papernest_photo_slot( 'papernest_logo_inpost', 'logo InPost', 'logo-slot' ); ?><?php papernest_photo_slot( 'papernest_logo_dpd', 'logo DPD', 'logo-slot' ); ?></div>
      </div>
      <?php
      echo papernest_reveal( ob_get_clean() );
      ?>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="card card-pad" style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">
      <span class="ic" style="width:52px;height:52px;border-radius:14px;background:var(--paper-100);color:var(--brand-green-deep);display:flex;align-items:center;justify-content:center;flex:none"><?php echo papernest_icon( 'pallet' ); ?></span>
      <div style="flex:1;min-width:260px">
        <h2 style="font-size:1.3rem;margin-bottom:10px">Większe zamówienia</h2>
        <p style="color:var(--ink-600);line-height:1.75">W przypadku większych zamówień, produktów o niestandardowych wymiarach albo zamówień hurtowych koszt i sposób dostawy mogą być ustalane indywidualnie. Jeśli potrzebujesz większej ilości papieru, <a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">skontaktuj się z nami</a> przed zakupem.</p>
      </div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
    <p class="text-muted" style="font-size:.85rem;margin-top:24px">Masz pytania o płatność, dostawę lub termin realizacji zamówienia? Skorzystaj z <a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" style="color:var(--brand-link-green);text-decoration:underline">formularza kontaktowego</a>.</p>
  </div>
</section>

<?php
endwhile;
get_footer();
