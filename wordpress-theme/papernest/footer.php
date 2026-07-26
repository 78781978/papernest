<?php
/**
 * Site footer — ported from the static prototype's footer() in tools/build.py.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>
<footer class="site-footer">
  <div class="container footer-top reveal">
    <div class="footer-brand">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand">
        <span class="brand-logo-plate"><?php echo papernest_logo( 'footer-logo' ); ?></span>
      </a>
      <p>PaperNest to producent wyrobów z&nbsp;papieru z&nbsp;25-letnim doświadczeniem na rynku. Oferujemy wypełniacze papierowe, papiery do kurników oraz papiery remontowe – dla firm z&nbsp;całej Polski.</p>
      <div class="footer-social">
        <a href="<?php echo esc_url( papernest_facebook_url() ); ?>" aria-label="Facebook" target="_blank" rel="noopener"><?php echo papernest_icon( 'facebook' ); ?></a>
      </div>
    </div>
    <div class="footer-col">
      <h3><button type="button" class="footer-col-toggle" aria-expanded="false"><span class="footer-col-ic"><?php echo papernest_icon( 'info' ); ?></span>Informacje<span class="footer-col-chevron" aria-hidden="true"></span></button></h3>
      <div class="footer-col-body">
      <ul>
        <li><a href="<?php echo esc_url( papernest_page_link( 'regulamin' ) ); ?>">Regulamin</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'polityka-prywatnosci' ) ); ?>">Polityka prywatności</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'reklamacje' ) ); ?>">Reklamacje</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'odstapienie' ) ); ?>">Odstąpienie od umowy</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'platnosc-i-dostawa' ) ); ?>">Płatność i Dostawa</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'dostepnosc' ) ); ?>">Deklaracja dostępności</a></li>
        <li><button type="button" class="link-btn" data-open-consent>Zarządzaj zgodami</button></li>
      </ul>
      </div>
    </div>
    <div class="footer-col">
      <h3><button type="button" class="footer-col-toggle" aria-expanded="false"><span class="footer-col-ic"><?php echo papernest_icon( 'user' ); ?></span>Konto<span class="footer-col-chevron" aria-hidden="true"></span></button></h3>
      <div class="footer-col-body">
      <ul>
        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <li><a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">Moje Konto</a></li>
        <li><a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>">Zamówienia</a></li>
        <li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>">Koszyk</a></li>
        <li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Sklep</a></li>
        <?php else : ?>
        <li><a href="<?php echo esc_url( papernest_page_link( 'moje-konto' ) ); ?>">Moje Konto</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'moje-konto' ) ); ?>">Zamówienia</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'koszyk' ) ); ?>">Koszyk</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'sklep' ) ); ?>">Sklep</a></li>
        <?php endif; ?>
      </ul>
      </div>
    </div>
    <div class="footer-col">
      <h3><button type="button" class="footer-col-toggle" aria-expanded="false"><span class="footer-col-ic"><?php echo papernest_icon( 'factory' ); ?></span>Firma<span class="footer-col-chevron" aria-hidden="true"></span></button></h3>
      <div class="footer-col-body">
      <ul>
        <li><a href="<?php echo esc_url( papernest_page_link( 'o-nas' ) ); ?>">O nas</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'portfolio' ) ); ?>">Portfolio</a></li>
        <li><a href="<?php echo esc_url( papernest_page_link( 'kontakt' ) ); ?>">Kontakt</a></li>
      </ul>
      </div>
    </div>
    <div class="footer-col">
      <h3><button type="button" class="footer-col-toggle" aria-expanded="false"><span class="footer-col-ic"><?php echo papernest_icon( 'pin' ); ?></span>Kontakt<span class="footer-col-chevron" aria-hidden="true"></span></button></h3>
      <div class="footer-col-body">
      <div>
      <div class="contact-line"><?php echo papernest_icon( 'pin' ); ?><span><?php echo esc_html( papernest_address_line() ); ?></span></div>
      <div class="contact-line" style="margin-top:.7em"><a href="<?php echo esc_url( papernest_phone_tel() ); ?>"><?php echo papernest_icon( 'phone' ); ?><span><?php echo esc_html( papernest_phone_display() ); ?></span></a></div>
      <div class="contact-line" style="margin-top:.7em"><a href="mailto:<?php echo esc_attr( papernest_email() ); ?>"><?php echo papernest_icon( 'mail' ); ?><span><?php echo esc_html( papernest_email() ); ?></span></a></div>
      </div>
      </div>
    </div>
  </div>
  <div class="container footer-bottom">
    <span><?php echo esc_html( gmdate( 'Y' ) ); ?> © Copyright by PaperNest — P.H.U „Bobinex” Grzegorz Działkowski</span>
    <span class="footer-credit">Projekt i&nbsp;realizacja: <a href="https://www.facebook.com/profile.php?id=61591915780293" target="_blank" rel="noopener">VERO STUDIO</a></span>
  </div>
</footer>
<button class="to-top" aria-label="Wróć na górę"><?php echo papernest_icon( 'chevronUp' ); ?></button>

<!-- ============ Baner zgody na cookies (pierwsza warstwa RODO) ============ -->
<div class="cookie-banner" role="dialog" aria-modal="false" aria-labelledby="cookie-title" aria-describedby="cookie-desc" aria-hidden="true">
  <div class="cookie-banner-icon" aria-hidden="true"><?php echo papernest_icon( 'shield' ); ?></div>
  <div class="cookie-banner-body">
    <p id="cookie-title"><strong>Dbamy o Twoją prywatność.</strong></p>
    <p id="cookie-desc">Używamy plików cookies, aby zapewnić prawidłowe działanie strony, analizować ruch oraz — za Twoją zgodą — dopasowywać treści i działania marketingowe. Szczegóły znajdziesz w <a href="<?php echo esc_url( papernest_page_link( 'polityka-prywatnosci' ) ); ?>">Polityce prywatności</a>. Zgodę możesz wycofać lub zmienić w każdej chwili w stopce strony.</p>
    <div class="row">
      <button class="btn btn-primary btn-sm" type="button" data-cookie-action="accept-all">Akceptuję wszystkie</button>
      <button class="btn btn-outline btn-sm" type="button" data-cookie-action="reject">Odrzuć opcjonalne</button>
      <button class="btn-text" type="button" data-open-consent>Dostosuj ustawienia</button>
    </div>
  </div>
</div>

<!-- ============ Pełny panel preferencji (druga warstwa RODO) ============ -->
<div class="consent-scrim" data-consent-scrim hidden></div>
<div class="consent-modal" role="dialog" aria-modal="true" aria-labelledby="consent-modal-title" hidden>
  <div class="consent-modal-head">
    <h2 id="consent-modal-title">Ustawienia prywatności</h2>
    <button class="icon-btn" type="button" aria-label="Zamknij ustawienia prywatności" data-close-consent>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M5 5l14 14M19 5 5 19"/></svg>
    </button>
  </div>
  <div class="consent-modal-body">
    <p>Wybierz, na jakie kategorie plików cookie się zgadzasz. Niezbędne pliki cookie są zawsze aktywne, ponieważ bez nich strona nie działałaby prawidłowo. Więcej informacji znajdziesz w <a href="<?php echo esc_url( papernest_page_link( 'polityka-prywatnosci' ) ); ?>">Polityce prywatności</a>.</p>

    <div class="consent-category">
      <div class="consent-category-head">
        <label class="switch">
          <input type="checkbox" checked disabled aria-describedby="c-necessary-desc">
          <span class="switch-track" aria-hidden="true"></span>
          <span class="switch-label">Niezbędne</span>
        </label>
        <span class="tag-pill">Zawsze aktywne</span>
      </div>
      <p id="c-necessary-desc">Umożliwiają podstawowe działanie strony: bezpieczeństwo, zapamiętanie zgód, obsługę koszyka. Nie można ich wyłączyć.</p>
    </div>

    <div class="consent-category">
      <div class="consent-category-head">
        <label class="switch">
          <input type="checkbox" data-consent-cat="functional" aria-describedby="c-functional-desc">
          <span class="switch-track" aria-hidden="true"></span>
          <span class="switch-label">Funkcjonalne</span>
        </label>
      </div>
      <p id="c-functional-desc">Umożliwiają dodatkowe funkcje strony, np. zapamiętanie preferencji czy osadzone treści (mapa, czat).</p>
    </div>

    <div class="consent-category">
      <div class="consent-category-head">
        <label class="switch">
          <input type="checkbox" data-consent-cat="analytics" aria-describedby="c-analytics-desc">
          <span class="switch-track" aria-hidden="true"></span>
          <span class="switch-label">Analityczne</span>
        </label>
      </div>
      <p id="c-analytics-desc">Pomagają zrozumieć, jak odwiedzający korzystają ze strony — liczbę odwiedzin, źródła ruchu, popularność podstron.</p>
    </div>

    <div class="consent-category">
      <div class="consent-category-head">
        <label class="switch">
          <input type="checkbox" data-consent-cat="marketing" aria-describedby="c-marketing-desc">
          <span class="switch-track" aria-hidden="true"></span>
          <span class="switch-label">Marketingowe</span>
        </label>
      </div>
      <p id="c-marketing-desc">Służą do dopasowania reklam i mierzenia skuteczności działań reklamowych na tej i innych stronach.</p>
    </div>
  </div>
  <div class="consent-modal-foot">
    <button class="btn btn-outline btn-sm" type="button" data-cookie-action="reject">Odrzuć opcjonalne</button>
    <button class="btn btn-outline btn-sm" type="button" data-cookie-action="accept-all">Akceptuj wszystkie</button>
    <button class="btn btn-primary btn-sm" type="button" data-cookie-action="save">Zapisz preferencje</button>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
