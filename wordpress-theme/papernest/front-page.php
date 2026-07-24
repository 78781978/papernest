<?php
/**
 * Homepage — ported from the static prototype's pages_home.py build().
 * Hero copy/stats stay editable via the Customizer; products pull live from
 * the papernest_product CPT; testimonials pull from the papernest_review CPT.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$shop_url    = papernest_shop_link();
$about_url   = papernest_page_link( 'o-nas' );
$contact_url = papernest_page_link( 'kontakt' );
?>

<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-copy">
        <span class="hero-kicker"><span class="dot"></span>Polski producent od 2001 roku</span>
        <h1>Nikt nie kręci rolek <em>tak dobrze</em> jak my.</h1>
        <p class="lead">Od ponad 25 lat produkujemy wyroby papierowe i świadczymy usługi przewijania papieru. Zapewniamy najwyższą jakość, elastyczne możliwości produkcyjne oraz terminową realizację zamówień dla firm z różnych branż.</p>
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
        <div class="frame"><?php echo papernest_svg_file( 'hero.svg' ); ?></div>
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
      <b>Własna produkcja w Goleniowie</b>
      <p>Cały proces — od przewijania po pakowanie — odbywa się w jednym miejscu. Pełna kontrola jakości na każdym etapie, bez pośredników.</p>
    </div>
    <div class="bento-item span-wide"><span class="ic"><?php echo papernest_icon( 'sparkle' ); ?></span><div><b>Wysoka Jakość</b><span>Surowiec w 100% z recyklingu</span></div></div>
    <div class="bento-item"><span class="ic"><?php echo papernest_icon( 'truck' ); ?></span><b>Szybka Realizacja</b><span>InPost i DPD</span></div>
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
      $featured_products = new WP_Query(
          array(
              'post_type'      => 'papernest_product',
              'posts_per_page' => 3,
              'orderby'        => 'menu_order date',
              'order'          => 'ASC',
          )
      );
      if ( $featured_products->have_posts() ) :
          while ( $featured_products->have_posts() ) :
              $featured_products->the_post();
              $badge      = papernest_product_badge( get_the_ID() );
              $from_price = papernest_product_from_price( get_the_ID() );
              ob_start();
              ?>
              <article class="product-card">
                <a href="<?php the_permalink(); ?>" class="thumb">
                  <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium' ); ?>
                  <?php endif; ?>
                  <?php if ( $badge ) : ?><span class="badge"><?php echo esc_html( $badge ); ?></span><?php endif; ?>
                </a>
                <div class="body">
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <div class="meta">
                    <?php if ( null !== $from_price ) : ?>
                      <span class="price">od <span><?php echo esc_html( papernest_format_price( $from_price ) ); ?></span></span>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-sm">Zobacz <?php echo papernest_icon( 'arrow' ); ?></a>
                  </div>
                </div>
              </article>
              <?php
              echo papernest_reveal( ob_get_clean() );
          endwhile;
          wp_reset_postdata();
      else :
          ?>
          <div class="notice-box"><?php echo papernest_icon( 'info' ); ?><p><strong>Brak produktów.</strong> Dodaj produkty w Produkty → Dodaj produkt, a pojawią się tutaj automatycznie.</p></div>
          <?php
      endif;
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
      <div class="media"><div class="art"><?php echo papernest_svg_file( 'services.svg' ); ?></div></div>
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
    echo papernest_reveal(
        '<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Opinie</span>
      <h2 class="text-balance">Co mówią o nas klienci?</h2>
    </div>'
    );
    echo papernest_testimonial_marquee();
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
