<?php
/**
 * Template Name: O nas
 * Ported from tools/pages_about.py. The three narrative paragraphs come from
 * the page's own editable content (wp-admin editor); everything else
 * (stats, offer tiles, testimonials) stays structural like the rest of the theme.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	?>

<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>O nas</span></div>
    <h1><?php the_title(); ?></h1>
    <p>Ponad 20 lat doświadczenia w&nbsp;przetwórstwie papieru — od lokalnego zakładu w&nbsp;Goleniowie po markę PaperNest, znaną w&nbsp;całej Polsce.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="history-block">
      <div class="history-media">
        <div class="frame"><?php papernest_illustration( 'papernest_illustration_about', 'about.svg', 'O nas — PaperNest' ); ?></div>
        <div class="badge-ring"><b>25+</b><span>lat na rynku</span></div>
      </div>
      <div class="copy">
        <span class="eyebrow">Od 20+ lat w&nbsp;branży papierniczej</span>
        <h2 class="text-balance">Nasza Historia</h2>
        <?php
        // Hardcoded (not pulled from the page's own WordPress content editor
        // like before) so it always matches the styling of the "e-commerce"
        // section below it and can never again go missing or duplicate its
        // own heading when the page is edited in Elementor instead of the
        // normal WordPress editor.
        ?>
        <p style="margin-top:16px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">PaperNest to polska marka z&nbsp;Goleniowa, powstała na bazie ponad 20 lat doświadczenia w&nbsp;branży papierniczej. Naszą specjalnością jest profesjonalne przewijanie i&nbsp;cięcie wzdłużne rolek papierowych oraz produkcja praktycznych, ekologicznych wyrobów papierowych dla firm i&nbsp;klientów indywidualnych.</p>
        <p style="margin-top:14px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">Marka PaperNest została stworzona z&nbsp;pasji do jakości, funkcjonalności i&nbsp;odpowiedzialnej produkcji. Dzięki wieloletniej znajomości procesów przetwórstwa papieru oferujemy produkty dopracowane pod względem trwałości, wydajności i&nbsp;zastosowania.</p>
        <p style="margin-top:14px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">W&nbsp;naszej ofercie znajdują się przede wszystkim: wypełniacze papierowe do zabezpieczania przesyłek, papier dla piskląt wykorzystywany w&nbsp;hodowli drobiu oraz tektura budowlana do ochrony podłóg i&nbsp;innych powierzchni podczas remontów, prac wykończeniowych i&nbsp;budowlanych.</p>
        <p style="margin-top:14px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">Obsługujemy klientów detalicznych i&nbsp;biznesowych w&nbsp;całej Polsce, zapewniając wysoką jakość produktów, fachową obsługę oraz sprawną realizację zamówień. PaperNest to połączenie doświadczenia, nowoczesnego podejścia i&nbsp;ekologicznych rozwiązań dla branży e-commerce, budowlanej i&nbsp;hodowlanej.</p>
        <div class="hero-cta" style="margin-top:28px"><a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" class="btn btn-primary">Skontaktuj się z nami <?php echo papernest_icon( 'arrow' ); ?></a></div>
      </div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <?php
    echo papernest_reveal(
        '<div class="stat-band">
      <div class="stat"><b>100 000+</b><span>Ton papieru w naszej historii produkcji</span></div>
      <div class="stat"><b>25+</b><span>Lat doświadczenia w przetwórstwie papieru</span></div>
      <div class="stat"><b>100+</b><span>Stałych klientów w Polsce i za granicą</span></div>
    </div>'
    );
    ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="split">
      <div class="media">
        <div class="art"><?php papernest_illustration( 'papernest_illustration_about_growth', 'cardboard.svg', 'Rozwój firmy — sprzedaż online' ); ?></div>
        <div class="stat-pill"><span class="ic" style="background:var(--paper-100);display:flex;align-items:center;justify-content:center;color:var(--brand-green-deep)"><?php echo papernest_icon( 'trend' ); ?></span><b>e-commerce</b><span>Nowy kanał</span></div>
      </div>
      <div class="copy">
        <span class="eyebrow">Rozwój firmy</span>
        <h2 class="text-balance">Tradycja produkcji, nowoczesna sprzedaż</h2>
        <p style="margin-top:16px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">W odpowiedzi na zmieniające się potrzeby rynku oraz dynamiczny rozwój handlu internetowego, PaperNest zdecydował się rozszerzyć swoją działalność o sprzedaż online. Uruchomienie kanału e-commerce otworzyło firmę na nowych klientów i umożliwiło jeszcze łatwiejszy dostęp do oferowanych produktów.</p>
        <p style="margin-top:14px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">Dziś PaperNest łączy wieloletnie doświadczenie produkcyjne z nowoczesnym podejściem do sprzedaży i obsługi klienta.</p>
        <div class="hero-cta" style="margin-top:28px"><a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : papernest_page_link( 'sklep' ) ); ?>" class="btn btn-navy">Przejdź do sklepu <?php echo papernest_icon( 'arrow' ); ?></a></div>
      </div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php
    echo papernest_reveal(
        '<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Nasza oferta</span>
      <h2 class="text-balance">Do pakowania. Dla&nbsp;piskląt. Dla&nbsp;budownictwa.</h2>
    </div>'
    );
    ?>
    <div class="use-grid cols-3">
      <?php
      $offer_cards = array(
          array( 'papernest_photo_about_1', 'Zdjęcie: wypełniacz papierowy', 'gift', 'Wypełniacz Papierowy', 'Zabezpiecza przesyłki, w 100% z recyklingu.' ),
          array( 'papernest_photo_about_2', 'Zdjęcie: papier dla piskląt', 'egg', 'Papier Dla Piskląt', 'Bezpieczny start hodowli od pierwszego dnia.' ),
          array( 'papernest_photo_about_3', 'Zdjęcie: tektura budowlana', 'hammer', 'Tektura Budowlana', 'Ochrona podłóg i powierzchni podczas remontu.' ),
      );
      foreach ( $offer_cards as $c ) :
          ob_start();
          ?>
          <div class="use-card">
            <?php papernest_photo_slot( $c[0], $c[1] ); ?>
            <span class="ic"><?php echo papernest_icon( $c[2] ); ?></span>
            <b><?php echo esc_html( $c[3] ); ?></b>
            <span class="text-muted" style="font-size:.85rem"><?php echo esc_html( $c[4] ); ?></span>
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
    $testimonials_html = papernest_testimonial_marquee();
    if ( $testimonials_html ) {
        echo papernest_reveal(
            '<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Opinie</span>
      <h2 class="text-balance">Co mówią o nas klienci?</h2>
    </div>'
        );
        echo $testimonials_html; // phpcs:ignore
    }
    ?>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="cta-banner">
      <div>
        <h2>Zacznij z nami współpracę</h2>
        <p>Współpraca oparta na doświadczeniu, wysokiej jakości i wzajemnym zaufaniu pozwala budować trwałe relacje biznesowe.</p>
      </div>
      <div class="cta-actions">
        <a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>" class="btn" style="background:#fff;color:#4f7309;box-shadow:0 14px 30px rgba(0,0,0,.35)">Skontaktuj się z nami</a>
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
