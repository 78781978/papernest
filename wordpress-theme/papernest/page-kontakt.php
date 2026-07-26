<?php
/**
 * Template Name: Kontakt
 * Ported from tools/pages_contact.py. Form now actually sends mail
 * (inc/contact-form.php) instead of the static prototype's demo alert().
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();

	$phone       = papernest_phone_display();
	$phone_tel   = papernest_phone_tel();
	$email       = papernest_email();
	$address     = papernest_address_line();
	$hours       = papernest_hours();

	$locations = array(
		array( 'user', 'Biuro Sprzedaży', 'Nasz zespół chętnie doradzi, odpowie na pytania i pomoże dobrać najlepsze rozwiązania dopasowane do potrzeb Twojej firmy.', array( $address, $phone, $email ) ),
		array( 'factory', 'Produkcja i Magazyn', 'Wysyłki paletowe i odbiory hurtowe - po wcześniejszym ustaleniu terminu.', array( $address, $phone, $email ) ),
		array( 'pin', 'Odbiory osobiste', 'P.H.U "BOBINEX" w Centrum Wędkarskim "OKOŃ".', array( 'ul. Szczecińska 1A, 72-100 Goleniów', $phone, $email ) ),
		array( 'cart', 'Zamówienia online', 'Zamówienia online realizuje P.H.U "BOBINEX" w Centrum Wędkarskim "OKOŃ".', array( 'ul. Szczecińska 1A, 72-100 Goleniów', $phone, $email ) ),
	);

	$submit_status = isset( $_GET['papernest_contact'] ) ? sanitize_key( wp_unslash( $_GET['papernest_contact'] ) ) : '';
	?>

<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Strona główna</a> <span>/</span> <span>Kontakt</span></div>
    <?php
    papernest_breadcrumb_schema(
        array(
            array( 'name' => 'Strona główna', 'url' => home_url( '/' ) ),
            array( 'name' => 'Kontakt', 'url' => null ),
        )
    );
    ?>
    <h1><?php the_title(); ?></h1>
    <p>Masz pytanie o&nbsp;produkty, wycenę hurtową lub usługę przewijania papieru? Napisz, zadzwoń lub odwiedź nas w&nbsp;Goleniowie.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <p style="text-align:center;font-weight:700;color:var(--heading);margin-bottom:var(--sp-6)">Godziny otwarcia: <?php echo esc_html( $hours ); ?></p>
    <div class="contact-grid cols-4 reveal-stagger">
      <?php
      foreach ( $locations as $loc ) :
          ob_start();
          ?>
          <div class="contact-card">
            <span class="ic"><?php echo papernest_icon( $loc[0] ); ?></span>
            <h3><?php echo esc_html( $loc[1] ); ?></h3>
            <p><?php echo esc_html( $loc[2] ); ?></p>
            <div class="cline-group">
              <?php foreach ( $loc[3] as $line ) : ?>
                <?php if ( $line === $phone ) : ?>
                  <a class="cline" href="<?php echo esc_url( $phone_tel ); ?>"><?php echo esc_html( $line ); ?></a>
                <?php elseif ( $line === $email ) : ?>
                  <a class="cline" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $line ); ?></a>
                <?php elseif ( false !== strpos( $line, ',' ) ) : ?>
                  <?php
                  // Address lines: break deliberately after the street/number
                  // (at the comma) so the postal code + city always lands
                  // together on their own second line, instead of an
                  // uncontrolled wrap that could split the code from the city.
                  list( $street_part, $city_part ) = array_map( 'trim', explode( ',', $line, 2 ) );
                  ?>
                  <span class="cline"><?php echo esc_html( $street_part ); ?>,<br><?php echo esc_html( $city_part ); ?></span>
                <?php else : ?>
                  <span class="cline"><?php echo esc_html( $line ); ?></span>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
          <?php
          echo papernest_reveal( ob_get_clean() );
      endforeach;
      ?>
    </div>
  </div>
</section>

<section class="section-tight" style="padding-top:0">
  <div class="container">
    <?php
    ob_start();
    ?>
    <div class="use-grid cols-3">
      <?php
      papernest_photo_slot( 'papernest_photo_warehouse', 'Magazyn PaperNest', 'wide' );
      papernest_photo_slot( 'papernest_photo_shop', 'Sklep / punkt odbioru', 'wide' );
      papernest_photo_slot( 'papernest_photo_office', 'Biuro sprzedaży', 'wide' );
      ?>
    </div>
    <?php
    echo papernest_reveal( ob_get_clean() );
    ?>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="two-col">
      <?php
      ob_start();
      ?>
      <div class="card card-pad">
        <span class="eyebrow">Napisz do nas</span>
        <h2 style="font-size:1.6rem;margin-bottom:20px">Formularz kontaktowy</h2>
        <?php if ( 'sent' === $submit_status ) : ?>
          <div class="notice-box"><?php echo papernest_icon( 'check' ); ?><p><strong>Dziękujemy!</strong> Twoja wiadomość została wysłana - odpowiemy najszybciej, jak to możliwe.</p></div>
        <?php elseif ( 'error' === $submit_status ) : ?>
          <div class="notice-box"><?php echo papernest_icon( 'info' ); ?><p><strong>Coś poszło nie tak.</strong> Uzupełnij wymagane pola i spróbuj ponownie, albo zadzwoń pod <?php echo esc_html( $phone ); ?>.</p></div>
        <?php endif; ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <input type="hidden" name="action" value="papernest_contact">
          <?php wp_nonce_field( 'papernest_contact_form', 'papernest_contact_nonce' ); ?>
          <div class="form-row">
            <div class="form-field"><label for="c-name">Imię i nazwisko</label><input id="c-name" name="c_name" type="text" required placeholder="Jan Kowalski"></div>
            <div class="form-field"><label for="c-email">Adres e-mail</label><input id="c-email" name="c_email" type="email" required placeholder="jan@firma.pl"></div>
          </div>
          <div class="form-row">
            <div class="form-field"><label for="c-phone">Telefon</label><input id="c-phone" name="c_phone" type="tel" placeholder="+48 500 000 000"></div>
            <div class="form-field"><label for="c-topic">Temat</label>
              <select id="c-topic" name="c_topic"><option>Wycena hurtowa</option><option>Usługa przewijania papieru</option><option>Reklamacja / zwrot</option><option>Inne pytanie</option></select>
            </div>
          </div>
          <div class="form-field"><label for="c-msg">Wiadomość</label><textarea id="c-msg" name="c_msg" rows="5" required placeholder="W czym możemy pomóc?"></textarea></div>
          <button class="btn btn-primary btn-block" type="submit">Wyślij wiadomość <?php echo papernest_icon( 'arrow' ); ?></button>
        </form>
      </div>
      <?php
      echo papernest_reveal( ob_get_clean() );

      ob_start();
      ?>
      <div>
        <span class="eyebrow">Znajdź nas</span>
        <?php
        // Wraps naturally at any comma-less point otherwise, which was
        // splitting the postal code itself mid-number ("72-" / "100
        // Goleniów") on narrow phones -- force the break at the comma
        // instead, so "72-100 Goleniów" always stays on its own line.
        $map_heading_parts = array_map( 'trim', explode( ',', $address, 2 ) );
        ?>
        <h2 style="font-size:1.6rem;margin-bottom:20px">ul. <?php echo esc_html( $map_heading_parts[0] ); ?><?php if ( isset( $map_heading_parts[1] ) ) : ?>,<br><?php echo esc_html( $map_heading_parts[1] ); ?><?php endif; ?></h2>
        <div class="map-wrap">
          <iframe title="Mapa - PaperNest, Goleniów" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            src="https://www.openstreetmap.org/export/embed.html?bbox=14.822%2C53.552%2C14.862%2C53.572&amp;layer=mapnik&amp;marker=53.562%2C14.842"></iframe>
        </div>
        <div class="notice-box" style="margin-top:24px">
          <?php echo papernest_icon( 'info' ); ?>
          <p><strong>Wysyłki paletowe i zamówienia hurtowe</strong> realizujemy po wcześniejszym ustaleniu terminu - zadzwoń pod numer <a href="<?php echo esc_url( $phone_tel ); ?>" style="color:var(--brand-link-green);font-weight:700;white-space:nowrap"><?php echo esc_html( $phone ); ?></a> lub napisz na <a href="mailto:<?php echo esc_attr( $email ); ?>" style="color:var(--brand-link-green);font-weight:700"><?php echo esc_html( $email ); ?></a></p>
        </div>
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
