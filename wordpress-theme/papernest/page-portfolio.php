<?php
/**
 * Template Name: Portfolio
 * Ported from tools/pages_portfolio.py. Use-case tiles pull from the
 * papernest_usecase CPT (inc/cpt.php) so the client can add photos.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$values = array(
		array( 'sparkle', 'Satysfakcja', 'Jakość, która przewyższa oczekiwania.' ),
		array( 'trend', 'Innowacja', 'Innowacyjne rozwiązania dla trwałego rozwoju i sukcesu.' ),
		array( 'shield', 'Rozpoznawalność', 'Budujemy markę, która pozostawia trwałe wrażenie.' ),
		array( 'leaf', 'Rozwój', 'Stawiamy na ciągły rozwój i nieustanne doskonalenie.' ),
	);
	?>

<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Portfolio</span></div>
    <h1><?php the_title(); ?></h1>
    <p>PaperNest w&nbsp;różnych zastosowaniach — odkryj, gdzie nasze wyroby z&nbsp;papieru sprawdzają się najlepiej.</p>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="use-grid">
      <?php
      foreach ( $values as $v ) :
          ob_start();
          ?>
          <div class="use-card">
            <span class="ic"><?php echo papernest_icon( $v[0] ); ?></span><b><?php echo esc_html( $v[1] ); ?></b>
            <span class="text-muted" style="font-size:.85rem"><?php echo esc_html( $v[2] ); ?></span>
          </div>
          <?php
          echo papernest_reveal( ob_get_clean() );
      endforeach;
      ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php
    echo papernest_reveal(
        '<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Przykłady zastosowań</span>
      <h2 class="text-balance">Jeden papier, dziesiątki możliwości</h2>
      <p>Od logistyki e-commerce po pracownie kreatywne i gabinety weterynaryjne — nasze rolki dopasowują się do branży klienta.</p>
    </div>'
    );
    ?>
    <div class="use-grid reveal-stagger">
      <?php echo papernest_usecase_tiles( 24 ); ?>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="cta-banner">
      <div>
        <h2>Nie znalazłeś swojego zastosowania?</h2>
        <p>Doradzimy, który papier PaperNest najlepiej sprawdzi się w Twojej branży — napisz do nas lub zadzwoń.</p>
      </div>
      <div class="cta-actions">
        <a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" class="btn" style="background:#fff;color:#4f7309;box-shadow:0 14px 30px rgba(0,0,0,.35)">Skontaktuj się <?php echo papernest_icon( 'arrow' ); ?></a>
      </div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<?php
endwhile;
get_footer();
