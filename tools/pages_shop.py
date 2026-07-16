# -*- coding: utf-8 -*-
from build import icon, svg_file, reveal, page, product_contact_block, photo_placeholder

PRODUCTS = {
    "wypelniacz": dict(
        name="Wypełniacz Papierowy", family="wypelniacz", art="roll-filler.svg",
        tag="Ekologiczny", short="Ekologiczny wypełniacz papierowy do paczek — 100% surowca z recyklingu, w pełni biodegradowalny.",
        detail_url="produkt-wypelniacz.html",
        variants=[
            dict(label="1 rolka", sub="420 mb x 35 cm", price="45,00", old="49,00"),
            dict(label="2 rolki", sub="840 mb łącznie", price="87,99", old="97,99"),
            dict(label="4 rolki", sub="1680 mb łącznie", price="172,99", old="184,99"),
            dict(label="Paleta 58 szt.", sub="zamówienia hurtowe", price="2500,00", old="2650,00"),
        ],
        specs=[("Wymiary rolki","wysokość 35 cm, długość nawoju 420 mb, średnica 26 cm"),
               ("Waga","10 kg"), ("Gramatura","70 g/m²"), ("Typ papieru","szary makulaturowy")],
        desc="""<p><strong>Wypełniacz papierowy PaperNest</strong> to nowoczesne i odpowiedzialne rozwiązanie do zabezpieczania przesyłek. Wykonany w 100% z papieru pochodzącego z recyklingu, jest w pełni biodegradowalny i zgodny z ideą zero waste.</p>
        <p>Doskonale sprawdza się przy wypełnianiu pustych przestrzeni w kartonach, zabezpieczaniu produktów podczas transportu oraz estetycznym pakowaniu zamówień z Twojego sklepu internetowego.</p>""",
    ),
    "papier-dla-pisklat": dict(
        name="Papier Dla Piskląt", family="pisklaki", art="chick-paper.svg",
        tag="Dla hodowców", short="Zielony papier pod paszę — wspiera start stada od pierwszych godzin życia piskląt.",
        detail_url="produkt-papier-dla-pisklat.html",
        variants=[
            dict(label="1 rolka", sub="200 mb x 64 cm", price="51,49", old="54,99"),
            dict(label="2 rolki", sub="400 mb łącznie", price="99,99", old="107,99"),
            dict(label="4 rolki", sub="800 mb łącznie", price="195,99", old="212,99"),
            dict(label="Paleta 62 szt.", sub="zamówienia hurtowe", price="2850,00", old="2915,00"),
        ],
        specs=[("Wymiary rolki","wysokość 64 cm, długość nawoju 200 mb, średnica 20 cm"),
               ("Waga","6 kg"), ("Gramatura","43 g/m²"), ("Typ papieru","zielony makulaturowy")],
        desc="""<p>Pierwsze dni życia piskląt to kluczowy moment, który bezpośrednio wpływa na zdrowie, rozwój i wyniki całej hodowli. <strong>Papier dla piskląt PaperNest</strong> to sprawdzone rozwiązanie, które wspiera start stada od pierwszych godzin — tworzy idealną powierzchnię do podania paszy.</p>
        <p>Dzięki temu pisklęta szybciej odnajdują paszę i wodę, a szeleszczący dźwięk papieru przyciąga je i pobudza do aktywności. Zwiększa się pobranie paszy i wody, dzięki czemu wyrównuje się rozwój całego stada.</p>""",
    ),
    "tektura-budowlana": dict(
        name="Tektura Budowlana", family="tektura", art="cardboard.svg",
        tag="Do remontu", short="Papier ochronny w rolce do zabezpieczania podłóg i powierzchni podczas remontu.",
        detail_url="produkt-tektura-budowlana.html",
        variants=[
            dict(label="1 rolka", sub="15 m² x 1 m", price="26,90", old="29,99"),
            dict(label="2 rolki", sub="ok. 30 m² łącznie", price="53,49", old=None),
            dict(label="4 rolki", sub="ok. 60 m² łącznie", price="104,99", old=None),
            dict(label="Paleta 96 szt.", sub="zamówienia hurtowe", price="2490,00", old="2830,00"),
        ],
        specs=[("Wymiary rolki","wysokość 100 cm, długość nawoju 15 mb, średnica 26 cm"),
               ("Waga","10 kg"), ("Gramatura","280 g/m²"), ("Typ papieru","szary makulaturowy")],
        desc="""<p>Profesjonalny <strong>papier budowlany marki PaperNest</strong> (znany również jako tektura w rolce) to niezbędne rozwiązanie zarówno dla ekip remontowych, jak i osób samodzielnie odnawiających wnętrza.</p>
        <p>Wysoka jakość i wytrzymałość materiału sprawiają, że skutecznie chroni powierzchnie przed zabrudzeniami z farby, a także zabezpiecza podłoże przed uszkodzeniami mechanicznymi — takimi jak zarysowania spowodowane drabiną, narzędziami czy upadającymi elementami.</p>""",
    ),
}


def price_num(p):
    return p.replace(",", ".")


def build_shop():
    cards = ""
    for slug, p in PRODUCTS.items():
        v = p["variants"][0]
        cards += reveal(f"""<article class="product-card" data-family="{p['family']}">
          <div class="thumb">{svg_file(p['art'])}<span class="badge">{p['tag']}</span></div>
          <div class="body">
            <h3>{p['name']}</h3>
            <div class="meta">
              <span class="price"><span class="old-price">{v['old']} zł</span>{v['price']} zł<small>/ {v['label'].lower()}</small></span>
              <a href="{p['detail_url']}" class="btn btn-outline btn-sm">Wybierz {icon('arrow')}</a>
            </div>
          </div>
        </article>""")
        for extra in p["variants"][1:]:
            old_price_html = f'<span class="old-price">{extra["old"]} zł</span>' if extra["old"] else ""
            cards += reveal(f"""<article class="product-card" data-family="{p['family']}">
              <div class="thumb">{svg_file(p['art'])}<span class="badge">{extra['label']}</span></div>
              <div class="body">
                <h3>{p['name']} — {extra['label']}</h3>
                <div class="meta">
                  <span class="price">{old_price_html}{extra['price']} zł</span>
                  <a href="{p['detail_url']}" class="btn btn-outline btn-sm">Wybierz {icon('arrow')}</a>
                </div>
              </div>
            </article>""")

    content = f"""
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Sklep</span></div>
    <h1>Sklep PaperNest</h1>
    <p>Papier w rolkach prosto od producenta — wypełniacz do paczek, papier dla piskląt i tektura budowlana. Dostępne w wariantach 1, 2, 4 rolki oraz na palecie.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="shop-toolbar">
      <div class="shop-filters">
        <button class="filter-chip active" data-filter="all">Wszystkie produkty</button>
        <button class="filter-chip" data-filter="wypelniacz">Wypełniacz papierowy</button>
        <button class="filter-chip" data-filter="pisklaki">Papier dla piskląt</button>
        <button class="filter-chip" data-filter="tektura">Tektura budowlana</button>
      </div>
      <div class="shop-sort">Sortuj:
        <select><option>Polecane</option><option>Cena rosnąco</option><option>Cena malejąco</option></select>
      </div>
    </div>
    <div class="product-grid cols-4 reveal-stagger">
      {cards}
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="notice-box">
      {icon('info')}
      <p><strong>Płatności i dostawa.</strong> W sklepie planujemy udostępnić płatności online przez paynow (BLIK, szybki przelew, karta) oraz dostawę InPost i DPD. Szczegóły znajdziesz na stronie <a href="platnosc-i-dostawa.html" style="color:var(--brand-link-green);text-decoration:underline">Płatność i Dostawa</a>.</p>
    </div>
  </div>
</section>
"""
    page(
        "sklep.html",
        "Sklep | Wypełniacz, papier dla piskląt, tektura budowlana — PaperNest",
        "Kup wypełniacz papierowy, papier dla piskląt i tekturę budowlaną prosto od producenta. Warianty 1, 2, 4 rolki oraz paleta.",
        "sklep.html",
        content,
    )
    return cards


def build_products():
    for slug, p in PRODUCTS.items():
        variants_html = ""
        for i, v in enumerate(p["variants"]):
            active = " active" if i == 0 else ""
            old_attr = f' data-old-price="{price_num(v["old"])}"' if v["old"] else ""
            variants_html += f'<button class="variant-chip{active}" data-price="{price_num(v["price"])}"{old_attr}>{v["label"]}<br><small style="font-weight:500;opacity:.7">{v["sub"]}</small></button>'

        first = p["variants"][0]
        old_html = f'<span class="price-old">{first["old"]} zł</span>' if first["old"] else '<span class="price-old" style="display:none"></span>'
        save_html = ""
        if first["old"]:
            pct = round((1 - float(price_num(first["price"])) / float(price_num(first["old"]))) * 100)
            save_html = f'<span class="save-badge">-{pct}%</span>'
        else:
            save_html = '<span class="save-badge" style="display:none"></span>'

        specs_rows = "".join(f"<tr><td>{k}</td><td>{v}</td></tr>" for k, v in p["specs"])

        others = [o for o in PRODUCTS.values() if o["name"] != p["name"]]

        content = f"""
<section class="page-hero" style="padding-block:40px 32px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <a href="sklep.html">Sklep</a> <span>/</span> <span>{p['name']}</span></div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="pd-grid">
      <div class="pd-gallery reveal">
        <div class="frame">{svg_file(p['art'])}</div>
        <div class="pd-gallery-thumbs">
          {photo_placeholder("Zdjęcie 1")}
          {photo_placeholder("Zdjęcie 2")}
          {photo_placeholder("Zdjęcie 3")}
          {photo_placeholder("Zdjęcie 4")}
        </div>
      </div>
      <div class="pd-info reveal">
        <span class="tag-pill">{p['tag']}</span>
        <h1 style="margin-top:16px;font-size:clamp(1.8rem,3vw,2.5rem)">{p['name']}</h1>
        <div class="stars" style="margin-top:10px">{icon('star')*5}<span style="color:var(--ink-500);font-size:.85rem;margin-left:.5em;font-weight:600">(24 opinie)</span></div>
        <div class="price-row">
          <span class="price-now">{first['price']} zł</span>
          {old_html}
          {save_html}
        </div>
        {product_contact_block()}

        <div class="variant-group">
          <h4>Wybierz wariant</h4>
          <div class="variant-options">{variants_html}</div>
        </div>

        <div class="qty-row">
          <div class="qty-input">
            <button class="minus" type="button" aria-label="Zmniejsz ilość">{icon('minus')}</button>
            <input type="text" value="1" inputmode="numeric" aria-label="Ilość">
            <button class="plus" type="button" aria-label="Zwiększ ilość">{icon('plus')}</button>
          </div>
          <button class="btn btn-primary btn-block" type="button">{icon('cart')} Dodaj do koszyka</button>
        </div>

        <div class="trust-row">
          <div class="item">{icon('truck')} Wysyłka InPost / DPD</div>
          <div class="item">{icon('shield')} Bezpieczna płatność</div>
          <div class="item">{icon('leaf')} 100% recykling</div>
        </div>

        <div class="tabs">
          <button class="tab-btn active" data-tab="opis">Opis produktu</button>
          <button class="tab-btn" data-tab="specyfikacja">Specyfikacja</button>
          <button class="tab-btn" data-tab="dostawa">Dostawa i zwroty</button>
        </div>
        <div class="tab-panel active" data-tab="opis">{p['desc']}</div>
        <div class="tab-panel" data-tab="specyfikacja"><table class="spec-table">{specs_rows}</table></div>
        <div class="tab-panel" data-tab="dostawa">
          <p style="font-size:.95rem;line-height:1.75;color:var(--ink-600)">Wysyłka Paczkomatem InPost lub kurierem InPost / DPD. Zamówienia hurtowe i paletowe realizujemy indywidualnie — <a href="kontakt.html" style="color:var(--brand-link-green);text-decoration:underline">skontaktuj się z nami</a>. Zwroty na zasadach opisanych w <a href="prawo-do-odstapienia-od-umowy.html" style="color:var(--brand-link-green);text-decoration:underline">prawie odstąpienia od umowy</a>.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Zobacz też</span>
      <h2>Pozostałe produkty PaperNest</h2>
    </div>
    <div class="product-grid">
      {"".join(f'''<article class="product-card">
        <div class="thumb">{svg_file(o["art"])}<span class="badge">{o["tag"]}</span></div>
        <div class="body">
          <h3>{o["name"]}</h3>
          <div class="meta"><span class="price">od <span>{o["variants"][0]["price"]} zł</span></span><a href="{o["detail_url"]}" class="btn btn-outline btn-sm">Zobacz {icon('arrow')}</a></div>
        </div>
      </article>''' for o in others)}
    </div>
  </div>
</section>
"""
        page(
            p["detail_url"],
            f"{p['name']} | Sklep PaperNest",
            p["short"],
            "sklep.html",
            content,
        )
