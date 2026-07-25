<?php
/**
 * Homepage — ported from the static prototype's pages_home.py build().
 * Hero copy/stats stay editable via the Customizer; products pull live from
 * WooCommerce; testimonials pull from the papernest_testimonial CPT.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : papernest_page_link( 'sklep' );
$about_url = papernest_page_link( 'o-nas' );
$contact_url = papernest_page_link( 'kontakt' );
?>

<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-copy">
        <span class="hero-kicker"><span class="dot"></span>Polski producent od 2001 roku</span>
        <h1>Nikt nie kręci rolek <em>tak dobrze</em> jak my.</h1>
        <p class="lead">Od ponad 25 lat produkujemy wyroby papierowe i&nbsp;świadczymy usługi przewijania papieru. Zapewniamy najwyższą jakość, elastyczne możliwości produkcyjne oraz terminową realizację zamówień dla firm z&nbsp;różnych branż.</p>
        <div class="hero-cta">
          <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-primary">Zobacz Nasze Produkty <?php echo papernest_icon( 'arrow' ); ?></a>
          <a href="<?php echo esc_url( $about_url ); ?>" class="btn btn-outline">Poznaj naszą historię</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><b>25+</b><span>Lat doświadczenia</span></div>
          <div class="stat"><b>100+</b><span>Stałych klientów</span></div>
          <div class="stat"><b>100 000+</b><span>Ton papieru</span></div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="frame"><?php papernest_illustration( 'papernest_illustration_hero', 'hero.svg', 'PaperNest' ); ?></div>
        <div class="float-card c1"><span class="ic"><?php echo papernest_icon( 'leaf' ); ?></span><div><b>100% recykling</b><span>Ekologiczny surowiec</span></div></div>
        <div class="float-card c2"><span class="ic"><?php echo papernest_icon( 'shield' ); ?></span><div><b>Bezpieczna płatność</b><span>BLIK, karta, przelew</span></div></div>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <div class="bento-grid">
    <div class="bento-item is-feature">
      <span class="tag-pill"><?php echo papernest_icon( 'leaf' ); ?> Certyfikowany recykling</span>
      <span class="ic"><?php echo papernest_icon( 'factory' ); ?></span>
      <b>Własna produkcja w&nbsp;Goleniowie</b>
      <p>Cały proces — od przewijania po pakowanie — odbywa się w&nbsp;jednym miejscu. Pełna kontrola jakości na każdym etapie, bez pośredników.</p>
    </div>
    <div class="bento-item span-wide"><span class="ic"><?php echo papernest_icon( 'sparkle' ); ?></span><div><b>Wysoka Jakość</b><span>Surowiec w&nbsp;100% z&nbsp;recyklingu</span></div></div>
    <div class="bento-item"><span class="ic"><?php echo papernest_icon( 'truck' ); ?></span><b>Szybka Realizacja</b><span>InPost i&nbsp;DPD</span></div>
    <div class="bento-item"><span class="ic"><?php echo papernest_icon( 'shield' ); ?></span><b>Bezpieczna Płatność</b><span>Obsługa przez paynow</span></div>
  </div>
</div>

<section class="section">
  <div class="container">
    <?php
    echo papernest_reveal(
        '<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Nasza oferta</span>
      <h2 class="text-balance">Trzy produkty. Jedna, sprawdzona jakość.</h2>
      <p>Papier z recyklingu przetwarzamy na rolki dopasowane do pakowania, hodowli drobiu i prac remontowych.</p>
    </div>'
    );
    ?>
    <div class="product-grid reveal-stagger">
      <?php
      if ( class_exists( 'WooCommerce' ) ) {
          // One tile per product category (not per product — the catalog has
          // multiple products per category, e.g. different pack sizes of the
          // same item), so this stays "3 different things" regardless of how
          // many product listings exist within each category.
          // childless => true skips umbrella/parent categories (e.g. a
          // "Nasze Produkty" wrapper containing the real categories as its
          // children) so the 3 tiles are the actual product families, not
          // a mix of a parent and its own children.
          $categories = get_terms(
              array(
                  'taxonomy'   => 'product_cat',
                  'hide_empty' => true,
                  'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
                  'childless'  => true,
                  'number'     => 3,
              )
          );
          if ( $categories && ! is_wp_error( $categories ) ) {
              $tile_index = 0;
              foreach ( $categories as $cat ) {
                  $sample = wc_get_products(
                      array(
                          'category' => array( $cat->slug ),
                          'limit'    => 1,
                          'status'   => 'publish',
                          'orderby'  => 'menu_order',
                          'order'    => 'ASC',
                      )
                  );
                  if ( empty( $sample ) ) {
                      continue;
                  }
                  $product   = $sample[0];
                  $cat_link  = get_term_link( $cat );
                  $tile_index++;
                  // Editable straight from the Customizer, so the client can
                  // swap this tile's photo without touching the WooCommerce
                  // product itself; falls back to the product's own featured
                  // image when nothing has been uploaded here.
                  $custom_image = get_theme_mod( 'papernest_home_product_' . $tile_index, '' );
                  ob_start();
                  ?>
                  <article class="product-card">
                    <a href="<?php echo esc_url( $cat_link ); ?>" class="thumb">
                      <?php if ( $custom_image ) : ?>
                        <img src="<?php echo esc_url( $custom_image ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>" loading="lazy">
                      <?php else : ?>
                        <?php echo $product->get_image( 'medium' ); // phpcs:ignore ?>
                      <?php endif; ?>
                    </a>
                    <div class="body">
                      <h3><a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat->name ); ?></a></h3>
                    </div>
                  </article>
                  <?php
                  echo papernest_reveal( ob_get_clean() );
              }
          }
      } else {
          ?>
          <div class="notice-box"><?php echo papernest_icon( 'info' ); ?><p><strong>WooCommerce nie jest aktywne.</strong> Zainstaluj i aktywuj wtyczkę WooCommerce, a następnie dodaj produkty — pojawią się tutaj automatycznie.</p></div>
          <?php
      }
      ?>
    </div>
  </div>
</section>

<section class="section section-tight" style="padding-top:0">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="split">
      <div class="copy">
        <span class="eyebrow" style="font-size:1rem">Usługi przemysłowe</span>
        <h2 class="text-balance">Zainteresowany usługą przewijania papieru?</h2>
        <p style="margin-top:16px;color:var(--ink-600);font-size:1.05rem;line-height:1.75">Oferujemy profesjonalne przewijanie papieru oraz cięcie wzdłużne rolek, dostosowane do indywidualnych wymagań klientów. Realizujemy zamówienia dla branży opakowaniowej, budowlanej, spożywczej i wielu innych.</p>
        <ul class="check-list">
          <li><span class="tick"><?php echo papernest_icon( 'check' ); ?></span>Precyzyjne przewijanie papieru na wybrane szerokości i średnice</li>
          <li><span class="tick"><?php echo papernest_icon( 'check' ); ?></span>Profesjonalne cięcie wzdłużne rolek papierowych</li>
          <li><span class="tick"><?php echo papernest_icon( 'check' ); ?></span>Elastyczna, terminowa realizacja zamówień</li>
        </ul>
        <div class="hero-cta" style="margin-top:32px"><a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-navy">Dowiedz się więcej <?php echo papernest_icon( 'arrow' ); ?></a></div>
      </div>
      <div class="media"><div class="art"><?php papernest_illustration( 'papernest_illustration_services', 'services.svg', 'Usługi przemysłowe' ); ?></div></div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<section class="section section-tight">
  <div class="container">
    <?php
    echo papernest_reveal(
        '<div class="stat-band">
      <div class="stat"><b>25+</b><span>Lat doświadczenia w przetwórstwie papieru</span></div>
      <div class="stat"><b>100+</b><span>Stałych klientów w Polsce i za granicą</span></div>
      <div class="stat"><b>100%</b><span>Surowca pochodzącego z recyklingu</span></div>
    </div>'
    );
    ?>
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
        <p>Dołącz do grona naszych klientów i przekonaj się, jak wspólnie możemy tworzyć rozwiązania, które wspierają rozwój Twojej firmy.</p>
      </div>
      <div class="cta-actions">
        <a href="<?php echo esc_url( $shop_url ); ?>" class="btn" style="background:#fff;color:#4f7309;box-shadow:0 14px 30px rgba(0,0,0,.35)">Przejdź do sklepu</a>
        <a href="<?php echo esc_url( $contact_url ); ?>" class="btn btn-ghost-dark">Skontaktuj się <?php echo papernest_icon( 'arrow' ); ?></a>
      </div>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<?php get_footer(); ?>
