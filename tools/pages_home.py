# -*- coding: utf-8 -*-
from build import icon, svg_file, reveal, page

TESTIMONIALS = [
    dict(initials="BP", name="Bracia Pikuła", role="Fermy BKK Pikuła", title="Doskonała Jakość",
         text="Jesteśmy bardzo zadowoleni z jakości papieru dla piskląt. Produkt jest wytrzymały, dobrze spełnia swoją funkcję i sprawdza się w codziennej pracy na fermie. Doceniamy również terminowe dostawy oraz profesjonalną obsługę."),
    dict(initials="JB", name="J. Bednarczyk", role="Jar-Pol", title="Skuteczna ochrona",
         text="Tektura budowlana doskonale sprawdza się podczas prac wykończeniowych i remontowych. Skutecznie zabezpiecza podłogi oraz inne powierzchnie przed uszkodzeniami i zabrudzeniami. Doceniamy wysoką jakość!"),
    dict(initials="AI", name="Adam Ilnicki", role="Agrofirma Witkowo", title="Rzetelna Obsługa",
         text="Papier dla piskląt spełnił nasze oczekiwania pod względem jakości i funkcjonalności. Produkt jest trwały, wygodny w użytkowaniu i doskonale sprawdza się podczas odchowu piskląt. Cenimy sobie rzetelną obsługę."),
    dict(initials="WP", name="W. Pazdańska", role="PaperNest Professional", title="Przyjazna obsługa, świetny wypełniacz",
         text="Bardzo miła i pomocna obsługa. Wypełniacz papierowy jest wysokiej jakości, skutecznie chroni produkty podczas transportu i świetnie sprawdza się podczas pakowania zamówień z naszego sklepu internetowego."),
]

def build():
    content = f"""
<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div class="hero-copy">
        <span class="hero-kicker"><span class="dot"></span>Polski producent od 2001 roku</span>
        <h1>Nikt nie kręci rolek <em>tak dobrze</em> jak my.</h1>
        <p class="lead">Od ponad 25 lat produkujemy wyroby papierowe i świadczymy usługi przewijania papieru. Zapewniamy najwyższą jakość, elastyczne możliwości produkcyjne oraz terminową realizację zamówień dla firm z różnych branż.</p>
        <div class="hero-cta">
          <a href="sklep.html" class="btn btn-primary">Zobacz Nasze Produkty {icon('arrow')}</a>
          <a href="o-nas.html" class="btn btn-outline">Poznaj naszą historię</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><b>25+</b><span>Lat doświadczenia</span></div>
          <div class="stat"><b>100+</b><span>Stałych klientów</span></div>
          <div class="stat"><b>100 000+</b><span>Ton papieru</span></div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="frame">{svg_file('hero.svg')}</div>
        <div class="float-card c1"><span class="ic">{icon('leaf')}</span><div><b>100% recykling</b><span>Ekologiczny surowiec</span></div></div>
        <div class="float-card c2"><span class="ic">{icon('shield')}</span><div><b>Bezpieczna płatność</b><span>BLIK, karta, przelew</span></div></div>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <div class="bento-grid">
    <div class="bento-item is-feature">
      <span class="tag-pill">{icon('leaf')} Certyfikowany recykling</span>
      <span class="ic">{icon('factory')}</span>
      <b>Własna produkcja w Goleniowie</b>
      <p>Cały proces — od przewijania po pakowanie — odbywa się w jednym miejscu. Pełna kontrola jakości na każdym etapie, bez pośredników.</p>
    </div>
    <div class="bento-item span-wide"><span class="ic">{icon('sparkle')}</span><div><b>Wysoka Jakość</b><span>Surowiec w 100% z recyklingu</span></div></div>
    <div class="bento-item"><span class="ic">{icon('truck')}</span><b>Szybka Realizacja</b><span>InPost i DPD</span></div>
    <div class="bento-item"><span class="ic">{icon('shield')}</span><b>Bezpieczna Płatność</b><span>Obsługa przez paynow</span></div>
  </div>
</div>

<section class="section">
  <div class="container">
    {reveal('''<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Nasza oferta</span>
      <h2 class="text-balance">Trzy produkty. Jedna, sprawdzona jakość.</h2>
      <p>Papier z recyklingu przetwarzamy na rolki dopasowane do pakowania, hodowli drobiu i prac remontowych.</p>
    </div>''')}
    <div class="product-grid reveal-stagger">
      {reveal(f'''<article class="product-card">
        <div class="thumb">{svg_file('roll-filler.svg')}<span class="badge">Ekologiczny</span></div>
        <div class="body">
          <h3>Wypełniacz Papierowy</h3>
          <p>Materiał do pakowania paczek i zabezpieczania przesyłek. 100% papier z recyklingu, w pełni biodegradowalny, zgodny z ideą zero waste.</p>
          <div class="meta"><span class="price">od <span>45 zł</span><small>/ rolka</small></span><a href="produkt-wypelniacz.html" class="btn btn-outline btn-sm">Zobacz {icon('arrow')}</a></div>
        </div>
      </article>''')}
      {reveal(f'''<article class="product-card">
        <div class="thumb">{svg_file('chick-paper.svg')}<span class="badge">Dla hodowców</span></div>
        <div class="body">
          <h3>Papier Dla Piskląt</h3>
          <p>Sprawdzone podłoże na pierwsze dni odchowu — pomaga pisklętom szybciej odnaleźć paszę i wodę od pierwszych godzin.</p>
          <div class="meta"><span class="price">od <span>51,49 zł</span><small>/ rolka</small></span><a href="produkt-papier-dla-pisklat.html" class="btn btn-outline btn-sm">Zobacz {icon('arrow')}</a></div>
        </div>
      </article>''')}
      {reveal(f'''<article class="product-card">
        <div class="thumb">{svg_file('cardboard.svg')}<span class="badge">Do remontu</span></div>
        <div class="body">
          <h3>Tektura Budowlana</h3>
          <p>Niezbędne rozwiązanie dla ekip remontowych — chroni podłogi i powierzchnie przed zabrudzeniami i uszkodzeniami mechanicznymi.</p>
          <div class="meta"><span class="price">od <span>26,90 zł</span><small>/ rolka</small></span><a href="produkt-tektura-budowlana.html" class="btn btn-outline btn-sm">Zobacz {icon('arrow')}</a></div>
        </div>
      </article>''')}
    </div>
  </div>
</section>

<section class="section section-tight">
  <div class="container">
    {reveal(f'''<div class="split">
      <div class="copy">
        <span class="eyebrow">Usługi przemysłowe</span>
        <h2 class="text-balance">Zainteresowany usługą przewijania papieru?</h2>
        <p style="margin-top:16px;color:var(--ink-600);font-size:1.05rem;line-height:1.75">Oferujemy profesjonalne przewijanie papieru oraz cięcie wzdłużne rolek, dostosowane do indywidualnych wymagań klientów. Realizujemy zamówienia dla branży opakowaniowej, budowlanej, spożywczej i wielu innych.</p>
        <ul class="check-list">
          <li><span class="tick">{icon('check')}</span>Precyzyjne przewijanie papieru na wybrane szerokości i średnice</li>
          <li><span class="tick">{icon('check')}</span>Profesjonalne cięcie wzdłużne rolek papierowych</li>
          <li><span class="tick">{icon('check')}</span>Elastyczna, terminowa realizacja zamówień</li>
        </ul>
        <div class="hero-cta" style="margin-top:32px"><a href="kontakt.html" class="btn btn-navy">Dowiedz się więcej {icon('arrow')}</a></div>
      </div>
      <div class="media"><div class="art">{svg_file('services.svg')}</div></div>
    </div>''')}
  </div>
</section>

<section class="section section-tight">
  <div class="container">
    {reveal(f'''<div class="stat-band">
      <div class="stat"><b>25+</b><span>Lat doświadczenia w przetwórstwie papieru</span></div>
      <div class="stat"><b>100+</b><span>Stałych klientów w Polsce i za granicą</span></div>
      <div class="stat"><b>100%</b><span>Surowca pochodzącego z recyklingu</span></div>
    </div>''')}
  </div>
</section>

<section class="section">
  <div class="container">
    {reveal('''<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Opinie</span>
      <h2 class="text-balance">Co mówią o nas klienci?</h2>
    </div>''')}
    <div class="testimonial-track reveal-stagger">
      {"".join(reveal(f'''<div class="testi-card">
        <div class="stars">{icon('star')*5}</div>
        <b style="color:var(--heading);font-size:.98rem">{t['title']}</b>
        <blockquote>{t['text']}</blockquote>
        <div class="who"><span class="avatar">{t['initials']}</span><div><b>{t['name']}</b><span>{t['role']}</span></div></div>
      </div>''') for t in TESTIMONIALS)}
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    {reveal(f'''<div class="cta-banner">
      <div>
        <h2>Zacznij z nami współpracę</h2>
        <p>Dołącz do grona naszych klientów i przekonaj się, jak wspólnie możemy tworzyć rozwiązania, które wspierają rozwój Twojej firmy.</p>
      </div>
      <div class="cta-actions">
        <a href="sklep.html" class="btn" style="background:#fff;color:#3d5708;box-shadow:0 14px 30px rgba(0,0,0,.25)">Przejdź do sklepu</a>
        <a href="kontakt.html" class="btn btn-ghost-dark">Skontaktuj się {icon('arrow')}</a>
      </div>
    </div>''')}
  </div>
</section>
"""
    page(
        "index.html",
        "PaperNest | Producent papieru w rolkach i wypełniaczy papierowych",
        "PaperNest produkuje papier w rolkach, wypełniacze papierowe, tekturę budowlaną i papier dla piskląt. Oferujemy przewijanie i cięcie papieru.",
        "index.html",
        content,
    )
