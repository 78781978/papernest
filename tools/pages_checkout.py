# -*- coding: utf-8 -*-
from build import icon, reveal, page


def build():
    content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <a href="koszyk.html">Koszyk</a> <span>/</span> <span>Zamówienie</span></div>
    <h1>Finalizacja zamówienia</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="notice-box">
      {icon('info')}
      <p><strong>Podgląd procesu zamówienia.</strong> To statyczny prototyp formularza zakupowego. Po wdrożeniu na WordPressie krok ten obsłuży WooCommerce wraz z bramką płatności paynow.</p>
    </div>
    <div class="two-col" style="grid-template-columns:2fr 1fr;align-items:start">
      {reveal(f'''<div class="card card-pad">
        <h2 style="font-size:1.2rem;margin-bottom:18px">Dane do wysyłki</h2>
        <form onsubmit="event.preventDefault(); alert('To formularz demonstracyjny prototypu — płatność zostanie podłączona do paynow po wdrożeniu na WordPressie.');">
          <div class="form-row">
            <div class="form-field"><label>Imię</label><input type="text" required></div>
            <div class="form-field"><label>Nazwisko</label><input type="text" required></div>
          </div>
          <div class="form-field"><label>Adres e-mail</label><input type="email" required></div>
          <div class="form-field"><label>Telefon</label><input type="tel" required></div>
          <div class="form-field"><label>Ulica i numer</label><input type="text" required></div>
          <div class="form-row">
            <div class="form-field"><label>Kod pocztowy</label><input type="text" required placeholder="72-100"></div>
            <div class="form-field"><label>Miejscowość</label><input type="text" required placeholder="Goleniów"></div>
          </div>

          <div class="variant-group">
            <h4>Sposób dostawy</h4>
            <div class="variant-options">
              <button type="button" class="variant-chip active">InPost Paczkomat</button>
              <button type="button" class="variant-chip">Kurier InPost</button>
              <button type="button" class="variant-chip">Kurier DPD</button>
            </div>
          </div>
          <div class="variant-group">
            <h4>Sposób płatności</h4>
            <div class="variant-options">
              <button type="button" class="variant-chip active">BLIK</button>
              <button type="button" class="variant-chip">Przelew online</button>
              <button type="button" class="variant-chip">Karta płatnicza</button>
            </div>
          </div>

          <button class="btn btn-primary btn-block" type="submit" style="margin-top:32px">{icon('shield')} Zamawiam i płacę</button>
        </form>
      </div>''')}
      {reveal(f'''<div class="summary-card">
        <h3 style="font-size:1.1rem;margin-bottom:16px">Twoje zamówienie</h3>
        <div class="summary-row"><span>Wypełniacz Papierowy × 1</span><span>87,99 zł</span></div>
        <div class="summary-row"><span>Tektura Budowlana × 2</span><span>107,60 zł</span></div>
        <div class="summary-row"><span>Dostawa</span><span>14,99 zł</span></div>
        <div class="summary-row total"><span>Razem</span><span>210,58 zł</span></div>
      </div>''')}
    </div>
  </div>
</section>
"""
    page(
        "zamowienie.html",
        "Zamówienie | PaperNest",
        "Finalizacja zamówienia w sklepie PaperNest — dane do wysyłki, dostawa i płatność.",
        "sklep.html",
        content,
    )
