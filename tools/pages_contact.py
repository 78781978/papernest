# -*- coding: utf-8 -*-
from build import icon, reveal, page, photo_placeholder, PHONE_TEL, PHONE_DISPLAY, EMAIL

LOCATIONS = [
    dict(ic="user", title="Biuro Sprzedaży",
         text="Nasz zespół chętnie doradzi, odpowie na pytania i pomoże dobrać najlepsze rozwiązania dopasowane do potrzeb Twojej firmy.",
         lines=["I Brygady Legionów 12-14, 72-100 Goleniów", "538 989 005", "gd@papernest.pl", "Godziny otwarcia: Pon–Pt 8:00–16:00"]),
    dict(ic="factory", title="Produkcja i Magazyn",
         text="Wysyłki paletowe i odbiory hurtowe — po wcześniejszym ustaleniu terminu.",
         lines=["I Brygady Legionów 12-14, 72-100 Goleniów", "538 989 005", "gd@papernest.pl"]),
    dict(ic="pin", title="Odbiory osobiste",
         text='P.H.U "BOBINEX" w Centrum Wędkarskim "OKOŃ".',
         lines=["ul. Szczecińska 1A, 72-100 Goleniów", "538 989 005", "gd@papernest.pl"]),
    dict(ic="cart", title="Zamówienia online",
         text='Zamówienia online realizuje P.H.U "BOBINEX" w Centrum Wędkarskim "OKOŃ".',
         lines=["ul. Szczecińska 1A, 72-100 Goleniów", "538 989 005", "gd@papernest.pl"]),
]


def cline(l):
    if l == PHONE_DISPLAY:
        return f'<a class="cline cline-nowrap" href="{PHONE_TEL}">{l}</a>'
    if l == EMAIL:
        return f'<a class="cline" href="mailto:{l}">{l}</a>'
    return f'<span class="cline">{l}</span>'


def build():
    cards = "".join(reveal(f'''<div class="contact-card">
      <span class="ic">{icon(loc['ic'])}</span>
      <h3>{loc['title']}</h3>
      <p>{loc['text']}</p>
      <div class="cline-group">{"".join(cline(l) for l in loc['lines'])}</div>
    </div>''') for loc in LOCATIONS)

    content = f"""
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Kontakt</span></div>
    <h1>Skontaktuj się z nami</h1>
    <p>Masz pytanie o produkty, wycenę hurtową lub usługę przewijania papieru? Napisz, zadzwoń lub odwiedź nas w Goleniowie.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-grid reveal-stagger" style="grid-template-columns:repeat(4,1fr)">{cards}</div>
  </div>
</section>

<section class="section-tight" style="padding-top:0">
  <div class="container">
    {reveal(f'''<div class="use-grid" style="grid-template-columns:repeat(3,1fr)">
      {photo_placeholder("Magazyn PaperNest", cls="wide")}
      {photo_placeholder("Sklep / punkt odbioru", cls="wide")}
      {photo_placeholder("Biuro sprzedaży", cls="wide")}
    </div>''')}
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="two-col">
      {reveal(f'''<div class="card card-pad">
        <span class="eyebrow">Napisz do nas</span>
        <h2 style="font-size:1.6rem;margin-bottom:20px">Formularz kontaktowy</h2>
        <form onsubmit="event.preventDefault(); this.reset(); alert('Dziękujemy! To formularz demonstracyjny prototypu — po wdrożeniu na WordPressie zostanie połączony z wysyłką maila.');">
          <div class="form-row">
            <div class="form-field"><label for="c-name">Imię i nazwisko</label><input id="c-name" type="text" required placeholder="Jan Kowalski"></div>
            <div class="form-field"><label for="c-email">Adres e-mail</label><input id="c-email" type="email" required placeholder="jan@firma.pl"></div>
          </div>
          <div class="form-row">
            <div class="form-field"><label for="c-phone">Telefon</label><input id="c-phone" type="tel" placeholder="+48 500 000 000"></div>
            <div class="form-field"><label for="c-topic">Temat</label>
              <select id="c-topic"><option>Wycena hurtowa</option><option>Usługa przewijania papieru</option><option>Reklamacja / zwrot</option><option>Inne pytanie</option></select>
            </div>
          </div>
          <div class="form-field"><label for="c-msg">Wiadomość</label><textarea id="c-msg" rows="5" required placeholder="W czym możemy pomóc?"></textarea></div>
          <button class="btn btn-primary btn-block" type="submit">Wyślij wiadomość {icon('arrow')}</button>
        </form>
      </div>''')}
      {reveal(f'''<div>
        <span class="eyebrow">Znajdź nas</span>
        <h2 style="font-size:1.6rem;margin-bottom:20px">Goleniów, ul. I Brygady Legionów 12-14</h2>
        <div class="map-wrap">
          <iframe title="Mapa — PaperNest, Goleniów" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
            src="https://www.openstreetmap.org/export/embed.html?bbox=14.822%2C53.552%2C14.862%2C53.572&amp;layer=mapnik&amp;marker=53.562%2C14.842"></iframe>
        </div>
        <div class="notice-box" style="margin-top:24px">
          {icon('info')}
          <p><strong>Wysyłki paletowe i zamówienia hurtowe</strong> realizujemy po wcześniejszym ustaleniu terminu — zadzwoń pod numer <a href="{PHONE_TEL}" style="color:var(--brand-link-green);font-weight:700;white-space:nowrap">{PHONE_DISPLAY}</a> lub napisz na <a href="mailto:{EMAIL}" style="color:var(--brand-link-green);font-weight:700">{EMAIL}</a>.</p>
        </div>
      </div>''')}
    </div>
  </div>
</section>
"""
    page(
        "kontakt.html",
        "Kontakt | PaperNest — producent papieru w rolkach",
        "Skontaktuj się z PaperNest — biuro sprzedaży, produkcja i magazyn w Goleniowie. Telefon: 538 989 005, e-mail: gd@papernest.pl.",
        "kontakt.html",
        content,
    )
