# -*- coding: utf-8 -*-
from build import icon, svg_file, reveal, page

CART_ITEMS = [
    dict(art="roll-filler.svg", name="Wypełniacz Papierowy", variant="2 rolki — 420 mb x 35 cm", qty=1, price="87,99"),
    dict(art="cardboard.svg", name="Tektura Budowlana", variant="1 rolka — 15 m² x 1 m", qty=2, price="53,80"),
]


def build_cart():
    rows = "".join(f"""<tr>
        <td><div class="cart-item"><div class="thumb">{svg_file(it['art'])}</div><div><b>{it['name']}</b><span>{it['variant']}</span></div></div></td>
        <td>{it['price']} zł</td>
        <td><div class="qty-input"><button class="minus" type="button">{icon('minus')}</button><input type="text" value="{it['qty']}"><button class="plus" type="button">{icon('plus')}</button></div></td>
        <td style="font-weight:700;color:var(--brand-navy)">{it['price']} zł</td>
        <td><button class="icon-btn" style="width:34px;height:34px" aria-label="Usuń"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 6h16M9 6V4h6v2M6 6l1 14h10l1-14"/></svg></button></td>
      </tr>""" for it in CART_ITEMS)

    content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Koszyk</span></div>
    <h1>Twój koszyk</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="notice-box">
      {icon('info')}
      <p><strong>Podgląd funkcji koszyka.</strong> To statyczny prototyp — przykładowa zawartość poniżej pokazuje docelowy wygląd. Po wdrożeniu na WordPressie koszyk będzie obsługiwany przez WooCommerce (dodawanie, usuwanie i przeliczanie zamówień w czasie rzeczywistym).</p>
    </div>
    <div class="two-col" style="grid-template-columns:2fr 1fr;align-items:start">
      {reveal(f'''<div class="card card-pad" style="overflow-x:auto">
        <table class="cart-table">
          <thead><tr><th>Produkt</th><th>Cena</th><th>Ilość</th><th>Suma</th><th></th></tr></thead>
          <tbody>{rows}</tbody>
        </table>
      </div>''')}
      {reveal(f'''<div class="summary-card">
        <h3 style="font-size:1.1rem;margin-bottom:16px">Podsumowanie zamówienia</h3>
        <div class="summary-row"><span>Wartość produktów</span><span>195,59 zł</span></div>
        <div class="summary-row"><span>Dostawa (InPost Paczkomat)</span><span>14,99 zł</span></div>
        <div class="summary-row total"><span>Razem</span><span>210,58 zł</span></div>
        <a href="zamowienie.html" class="btn btn-primary btn-block" style="margin-top:20px">Przejdź do zamówienia {icon('arrow')}</a>
        <a href="sklep.html" class="btn btn-outline btn-block" style="margin-top:10px">Kontynuuj zakupy</a>
      </div>''')}
    </div>
  </div>
</section>
"""
    page(
        "koszyk.html",
        "Koszyk | PaperNest",
        "Twój koszyk zakupowy w sklepie PaperNest.",
        "sklep.html",
        content,
    )


def build_account():
    content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Moje konto</span></div>
    <h1>Moje konto</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="notice-box">
      {icon('info')}
      <p><strong>Podgląd funkcji konta klienta.</strong> Logowanie, rejestracja i historia zamówień będą obsługiwane przez WooCommerce po wdrożeniu na WordPressie. Poniżej — docelowy układ ekranu logowania oraz panelu klienta.</p>
    </div>

    <div class="two-col" style="margin-bottom:64px">
      {reveal(f'''<div class="card card-pad">
        <h2 style="font-size:1.3rem;margin-bottom:18px">Zaloguj się</h2>
        <form onsubmit="event.preventDefault(); alert('Logowanie zostanie podłączone do WooCommerce po wdrożeniu na WordPressie.');">
          <div class="form-field"><label for="l-user">Login lub e-mail</label><input id="l-user" type="text" required></div>
          <div class="form-field"><label for="l-pass">Hasło</label><input id="l-pass" type="password" required></div>
          <button class="btn btn-primary btn-block" type="submit">Zaloguj się</button>
        </form>
        <a href="#" style="display:block;text-align:center;margin-top:14px;font-size:.85rem;color:var(--ink-500)">Nie pamiętasz hasła?</a>
      </div>''')}
      {reveal(f'''<div class="card card-pad">
        <h2 style="font-size:1.3rem;margin-bottom:18px">Zarejestruj się</h2>
        <form onsubmit="event.preventDefault(); alert('Rejestracja zostanie podłączona do WooCommerce po wdrożeniu na WordPressie.');">
          <div class="form-field"><label for="r-email">Adres e-mail</label><input id="r-email" type="email" required></div>
          <button class="btn btn-outline btn-block" type="submit">Utwórz konto</button>
        </form>
        <p class="text-muted" style="font-size:.82rem;margin-top:14px;line-height:1.6">Link do ustawienia hasła zostanie wysłany na Twój adres e-mail.</p>
      </div>''')}
    </div>

    {reveal('''<div class="section-head"><span class="eyebrow">Panel klienta</span><h2 style="font-size:1.6rem">Podgląd panelu po zalogowaniu</h2></div>''')}
    <div class="two-col reveal" style="grid-template-columns:.9fr 2.5fr;align-items:start">
      <nav class="card card-pad account-tabs">
        <a href="#" class="active">{icon('user')} Pulpit</a>
        <a href="#">{icon('box')} Zamówienia</a>
        <a href="#">{icon('pin')} Adresy</a>
        <a href="#">{icon('shield')} Dane konta</a>
        <a href="#">Wyloguj się</a>
      </nav>
      <div class="card card-pad">
        <h3 style="font-size:1.1rem;margin-bottom:16px">Ostatnie zamówienia</h3>
        <table class="cart-table">
          <thead><tr><th>Zamówienie</th><th>Data</th><th>Status</th><th>Suma</th></tr></thead>
          <tbody>
            <tr><td><strong style="color:var(--brand-navy)">#PN-10245</strong></td><td>02.07.2026</td><td><span class="tag-pill">Wysłane</span></td><td>210,58 zł</td></tr>
            <tr><td><strong style="color:var(--brand-navy)">#PN-10198</strong></td><td>18.06.2026</td><td><span class="tag-pill">Zrealizowane</span></td><td>97,99 zł</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
"""
    page(
        "moje-konto.html",
        "Moje konto | PaperNest",
        "Zaloguj się do swojego konta PaperNest, sprawdź historię zamówień i dane dostawy.",
        "moje-konto.html",
        content,
    )
