# -*- coding: utf-8 -*-
import os, re
from build import icon, reveal, page, ROOT, photo_placeholder, EMAIL

SRC = os.path.join(ROOT, "tools", "legal_src")


def linkify_email(html):
    """Zamienia wszystkie wystąpienia adresu e-mail w treści (poza już istniejącymi
    linkami/atrybutami) na klikalne mailto: — dotyczy to całej treści prawnej."""
    pattern = re.compile(re.escape(EMAIL))

    def repl(m):
        return f'<a href="mailto:{EMAIL}">{EMAIL}</a>'

    # nie dotykaj wystąpień już wewnątrz atrybutu href="mailto:...."
    return re.sub(r'(?<!mailto:)' + re.escape(EMAIL), lambda m: f'<a href="mailto:{EMAIL}">{EMAIL}</a>', html)


def read(name):
    with open(os.path.join(SRC, name), encoding="utf-8") as f:
        return linkify_email(f.read())


def add_anchors(html):
    """Wstrzykuje id do nagłówków h6 (paragrafy regulaminu), zwraca (html, toc)."""
    toc = []
    counter = [0]

    def repl(m):
        counter[0] += 1
        anchor = f"par-{counter[0]}"
        inner = re.sub(r"<[^>]+>", " ", m.group(1))
        inner = re.sub(r"\s+", " ", inner).strip()
        toc.append((anchor, inner))
        return f'<h6 id="{anchor}">{m.group(1)}</h6>'

    html = re.sub(r"<h6>(.*?)</h6>", repl, html, flags=re.S)
    return html, toc


def legal_page(slug, title, desc, h5_note, raw_html, active=None, with_toc=False, updated="17 czerwca 2026"):
    html, toc = add_anchors(raw_html) if with_toc else (raw_html, [])

    toc_html = ""
    if toc:
        items = "".join(f'<a href="#{a}">{t}</a>' for a, t in toc)
        toc_html = f'''<aside class="legal-toc reveal" style="position:sticky;top:110px">
          <h4>Spis paragrafów</h4>{items}
        </aside>'''

    layout_open = '<div class="two-col cols-toc" style="align-items:start">' if toc_html else '<div>'

    content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>{title.split('|')[0].strip()}</span></div>
    <h1>{title.split('|')[0].strip()}</h1>
    <p>Ostatnia aktualizacja: {updated}</p>
  </div>
</section>

<section class="section">
  <div class="container">
    {layout_open}
      {toc_html}
      <div class="legal-shell reveal" style="max-width:none">
        {f'<div class="legal-note">{h5_note}</div>' if h5_note else ''}
        {html}
      </div>
    </div>
  </div>
</section>
"""
    page(slug, title, desc, active, content)


def build():
    regulamin_html, _ = add_anchors(read("regulamin.html"))
    legal_page(
        "regulamin.html",
        "Regulamin Sklepu | PaperNest",
        "Regulamin sklepu internetowego www.papernest.pl — warunki sprzedaży, zamówień i reklamacji.",
        None,
        read("regulamin.html"),
        with_toc=True,
    )
    legal_page(
        "polityka-prywatnosci.html",
        "Polityka Prywatności | PaperNest",
        "Polityka prywatności sklepu internetowego www.papernest.pl — zasady przetwarzania danych osobowych zgodnie z RODO.",
        None,
        read("polityka-prywatnosci.html"),
    )
    legal_page(
        "reklamacje.html",
        "Reklamacje | PaperNest",
        "Zasady reklamacji produktów w sklepie PaperNest — gwarancja, rękojmia i tryb zgłaszania niezgodności towaru z umową.",
        None,
        read("reklamacje.html"),
    )
    legal_page(
        "prawo-do-odstapienia-od-umowy.html",
        "Prawo do odstąpienia od umowy | PaperNest",
        "Zasady odstąpienia od umowy zawartej na odległość w sklepie PaperNest — terminy, koszty i procedura zwrotu.",
        None,
        read("prawo-do-odstapienia.html"),
    )

    # Płatność i Dostawa — treść oryginalna, przebudowana na spójny układ (bez inline styles)
    platnosc_content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Płatność i Dostawa</span></div>
    <h1>Płatność i Dostawa</h1>
    <p>W PaperNest zależy nam na prostym i bezpiecznym procesie zakupu. Poniżej znajdziesz informacje o metodach płatności oraz sposobach dostawy zamówień.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">
      {reveal(f'''<div class="card card-pad">
        <span class="eyebrow">Płatności</span>
        <h2 style="font-size:1.4rem;margin-bottom:14px">Metody płatności</h2>
        <p style="color:var(--ink-600);line-height:1.75;margin-bottom:18px">W sklepie planujemy udostępnić płatności online przez <strong style="color:var(--heading)">paynow</strong> — markę mBanku, który odpowiada za bezpieczeństwo transakcji.</p>
        <ul class="icon-list">
          <li>{icon('shield')}<span><strong style="color:var(--heading)">BLIK</strong> — szybka płatność kodem BLIK.</span></li>
          <li>{icon('trend')}<span><strong style="color:var(--heading)">Szybkie przelewy online</strong> — wygodne płatności z większości polskich banków.</span></li>
          <li>{icon('cart')}<span><strong style="color:var(--heading)">Karty płatnicze</strong> — płatność kartą, jeśli metoda będzie dostępna w koszyku.</span></li>
        </ul>
        <div class="logo-strip">{photo_placeholder("logo paynow", cls="logo-slot")}</div>
      </div>''')}
      {reveal(f'''<div class="card card-pad">
        <span class="eyebrow">Dostawa</span>
        <h2 style="font-size:1.4rem;margin-bottom:14px">Metody dostawy</h2>
        <p style="color:var(--ink-600);line-height:1.75;margin-bottom:18px">Dostępne metody dostawy zależą od rodzaju produktu, gabarytu zamówienia oraz adresu dostawy. Dla standardowych przesyłek korzystamy z usług InPost oraz DPD.</p>
        <ul class="icon-list">
          <li>{icon('box')}<span><strong style="color:var(--heading)">InPost Paczkomat®</strong> — odbiór w wybranym automacie paczkowym, wygodny dla mniejszych zamówień.</span></li>
          <li>{icon('truck')}<span><strong style="color:var(--heading)">Kurier InPost</strong> — dostawa na wskazany adres: firma, dom lub magazyn.</span></li>
          <li>{icon('truck')}<span><strong style="color:var(--heading)">Kurier DPD</strong> — dostawa kurierska zgodnie z zasadami przewoźnika.</span></li>
        </ul>
        <div class="logo-strip">{photo_placeholder("logo InPost", cls="logo-slot")}{photo_placeholder("logo DPD", cls="logo-slot")}</div>
      </div>''')}
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    {reveal(f'''<div class="card card-pad" style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap">
      <span class="ic" style="width:52px;height:52px;border-radius:14px;background:var(--paper-100);color:var(--brand-green-deep);display:flex;align-items:center;justify-content:center;flex:none">{icon('pallet')}</span>
      <div style="flex:1;min-width:260px">
        <h2 style="font-size:1.3rem;margin-bottom:10px">Większe zamówienia</h2>
        <p style="color:var(--ink-600);line-height:1.75">W przypadku większych zamówień, produktów o niestandardowych wymiarach albo zamówień hurtowych koszt i sposób dostawy mogą być ustalane indywidualnie. Jeśli potrzebujesz większej ilości papieru, <a href="kontakt.html" style="color:var(--brand-link-green);text-decoration:underline">skontaktuj się z nami</a> przed zakupem — pomożemy dobrać najlepsze rozwiązanie logistyczne.</p>
      </div>
    </div>''')}
    <p class="text-muted" style="font-size:.85rem;margin-top:24px">Informacje na tej stronie mają charakter organizacyjny i mogą zostać doprecyzowane przed pełnym uruchomieniem sklepu. Masz pytania o płatność, dostawę lub termin realizacji zamówienia? Skorzystaj z <a href="kontakt.html" style="color:var(--brand-link-green);text-decoration:underline">formularza kontaktowego</a>.</p>
  </div>
</section>
"""
    page(
        "platnosc-i-dostawa.html",
        "Płatność i Dostawa | PaperNest",
        "Metody płatności (BLIK, przelew, karta) i dostawy (InPost, DPD) w sklepie PaperNest.",
        "sklep.html",
        platnosc_content,
    )

    # Odstąpienie od umowy — narzędzie / formularz (funkcja zachowana, oryginał: shortcode polski_withdrawal_lookup)
    odstapienie_content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Odstąpienie od umowy</span></div>
    <h1>Odstąpienie od umowy</h1>
    <p>Masz 14 dni na odstąpienie od umowy zawartej na odległość bez podania przyczyny. Wypełnij formularz poniżej, aby wygenerować oświadczenie o odstąpieniu.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">
      {reveal(f'''<div class="card card-pad">
        <span class="eyebrow">Formularz odstąpienia</span>
        <h2 style="font-size:1.4rem;margin-bottom:18px">Znajdź swoje zamówienie</h2>
        <form onsubmit="event.preventDefault(); alert('To formularz demonstracyjny prototypu. Po wdrożeniu na WordPressie połączy się z wyszukiwarką zamówień (wtyczka polski_withdrawal_lookup) i wygeneruje gotowe oświadczenie o odstąpieniu od umowy.');">
          <div class="form-field"><label for="o-order">Numer zamówienia</label><input id="o-order" type="text" required placeholder="np. PN-10245"></div>
          <div class="form-field"><label for="o-email">Adres e-mail podany przy zamówieniu</label><input id="o-email" type="email" required placeholder="jan@firma.pl"></div>
          <div class="form-field"><label for="o-reason">Powód zwrotu (opcjonalnie)</label><textarea id="o-reason" rows="4" placeholder="Nie musisz podawać przyczyny odstąpienia od umowy."></textarea></div>
          <button class="btn btn-primary btn-block" type="submit">{icon('check')} Wygeneruj oświadczenie o odstąpieniu</button>
        </form>
      </div>''')}
      {reveal(f'''<div class="card card-pad">
        <span class="eyebrow">Jak to działa</span>
        <h2 style="font-size:1.4rem;margin-bottom:18px">3 kroki do zwrotu</h2>
        <ul class="icon-list">
          <li>{icon('check')}<span><strong style="color:var(--heading)">Krok 1.</strong> Podaj numer zamówienia i e-mail — system znajdzie Twoje zamówienie i przygotuje gotowe oświadczenie.</span></li>
          <li>{icon('check')}<span><strong style="color:var(--heading)">Krok 2.</strong> Wydrukuj lub prześlij oświadczenie mailowo na adres <a href="mailto:gd@papernest.pl" style="color:var(--brand-link-green);text-decoration:underline;font-weight:700">gd@papernest.pl</a> w ciągu 14 dni.</span></li>
          <li>{icon('check')}<span><strong style="color:var(--heading)">Krok 3.</strong> Odeślij produkt na adres: P.H.U „Bobinex” Grzegorz Działkowski, ul. Szczecińska 1A, 72-100 Goleniów.</span></li>
        </ul>
        <div class="legal-note" style="margin-top:24px">Pełne warunki odstąpienia od umowy — w tym wyjątki i terminy zwrotu płatności — znajdziesz w dokumencie <a href="prawo-do-odstapienia-od-umowy.html" style="color:var(--brand-link-green);text-decoration:underline">Prawo do odstąpienia od umowy</a>.</div>
      </div>''')}
    </div>
  </div>
</section>
"""
    page(
        "odstapienie.html",
        "Odstąpienie od umowy | PaperNest",
        "Formularz odstąpienia od umowy zawartej na odległość — znajdź zamówienie i wygeneruj oświadczenie o zwrocie.",
        "sklep.html",
        odstapienie_content,
    )
