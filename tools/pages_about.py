# -*- coding: utf-8 -*-
from build import icon, svg_file, reveal, page
from pages_home import TESTIMONIALS


def build():
    content = f"""
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>O nas</span></div>
    <h1>Nasza Historia</h1>
    <p>Ponad 20 lat doświadczenia w przetwórstwie papieru — od lokalnego zakładu w Goleniowie po markę PaperNest, znaną w całej Polsce.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    {reveal(f'''<div class="history-block">
      <div class="history-media">
        <div class="frame">{svg_file('about.svg')}</div>
        <div class="badge-ring"><b>25+</b><span>lat na rynku</span></div>
      </div>
      <div class="copy">
        <span class="eyebrow">Od 20+ lat w branży papierniczej</span>
        <p style="font-size:1.05rem;line-height:1.8;color:var(--ink-700);margin-bottom:16px">P.H.U „Bobinex” Grzegorz Działkowski to polska firma z Goleniowa, z wieloletnim doświadczeniem w branży papierniczej, specjalizująca się w przewijaniu papieru oraz cięciu wzdłużnym rolek papierowych. Fundamentem działalności jest ponad 20 lat praktyki i znajomości procesów związanych z przetwórstwem papieru.</p>
        <p style="font-size:1.05rem;line-height:1.8;color:var(--ink-700);margin-bottom:16px">Z pasji do tworzenia praktycznych i ekologicznych produktów powstała marka <strong style="color:var(--heading)">PaperNest</strong>, której celem jest dostarczanie wysokiej jakości wyrobów papierowych oraz profesjonalnych usług przetwórczych. Firma nieustannie rozwija swoje możliwości produkcyjne, stawiając na niezawodność, terminowość i indywidualne podejście do każdego zamówienia.</p>
        <p style="font-size:1.05rem;line-height:1.8;color:var(--ink-700)">Obecnie PaperNest oferuje trzy główne grupy produktów: wypełniacze papierowe do zabezpieczania przesyłek, papier dla piskląt wykorzystywany w hodowli drobiu oraz tekturę budowlaną przeznaczoną do ochrony powierzchni podczas prac remontowych. Obsługujemy klientów indywidualnych i przedsiębiorstwa w Polsce oraz za granicą.</p>
        <div class="hero-cta" style="margin-top:28px"><a href="kontakt.html" class="btn btn-primary">Skontaktuj się z nami {icon('arrow')}</a></div>
      </div>
    </div>''')}
  </div>
</section>

<section class="section-tight">
  <div class="container">
    {reveal('''<div class="stat-band">
      <div class="stat"><b>100 000+</b><span>Ton papieru w naszej historii produkcji</span></div>
      <div class="stat"><b>25+</b><span>Lat doświadczenia w przetwórstwie papieru</span></div>
      <div class="stat"><b>100+</b><span>Stałych klientów w Polsce i za granicą</span></div>
    </div>''')}
  </div>
</section>

<section class="section">
  <div class="container">
    {reveal(f'''<div class="split">
      <div class="media">
        <div class="art">{svg_file('services.svg')}</div>
        <div class="stat-pill"><span class="ic" style="width:44px;height:44px;border-radius:12px;background:var(--paper-100);display:flex;align-items:center;justify-content:center;color:var(--brand-green-deep)">{icon('trend')}</span><div><b>e-commerce</b><span>Nowy kanał sprzedaży</span></div></div>
      </div>
      <div class="copy">
        <span class="eyebrow">Rozwój firmy</span>
        <h2 class="text-balance">Tradycja produkcji, nowoczesna sprzedaż</h2>
        <p style="margin-top:16px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">W odpowiedzi na zmieniające się potrzeby rynku oraz dynamiczny rozwój handlu internetowego, PaperNest zdecydował się rozszerzyć swoją działalność o sprzedaż online. Uruchomienie kanału e-commerce otworzyło firmę na nowych klientów i umożliwiło jeszcze łatwiejszy dostęp do oferowanych produktów.</p>
        <p style="margin-top:14px;font-size:1.02rem;line-height:1.8;color:var(--ink-600)">Dziś PaperNest łączy wieloletnie doświadczenie produkcyjne z nowoczesnym podejściem do sprzedaży i obsługi klienta. Wierzymy, że połączenie tradycji, jakości i innowacyjnych rozwiązań pozwala budować trwałe relacje biznesowe.</p>
        <div class="hero-cta" style="margin-top:28px"><a href="sklep.html" class="btn btn-navy">Przejdź do sklepu {icon('arrow')}</a></div>
      </div>
    </div>''')}
  </div>
</section>

<section class="section">
  <div class="container">
    {reveal('''<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Nasza oferta</span>
      <h2 class="text-balance">Do pakowania. Dla piskląt. Dla budownictwa.</h2>
    </div>''')}
    <div class="use-grid" style="grid-template-columns:repeat(3,1fr)">
      {reveal(f'''<div class="use-card"><span class="ic">{icon('gift')}</span><b>Wypełniacz Papierowy</b><span class="text-muted" style="font-size:.85rem">Zabezpiecza przesyłki, w 100% z recyklingu.</span></div>''')}
      {reveal(f'''<div class="use-card"><span class="ic">{icon('egg')}</span><b>Papier Dla Piskląt</b><span class="text-muted" style="font-size:.85rem">Bezpieczny start hodowli od pierwszego dnia.</span></div>''')}
      {reveal(f'''<div class="use-card"><span class="ic">{icon('hammer')}</span><b>Tektura Budowlana</b><span class="text-muted" style="font-size:.85rem">Ochrona podłóg i powierzchni podczas remontu.</span></div>''')}
    </div>
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
        <p>Współpraca oparta na doświadczeniu, wysokiej jakości i wzajemnym zaufaniu pozwala budować trwałe relacje biznesowe i osiągać ambitne cele bez ograniczeń.</p>
      </div>
      <div class="cta-actions">
        <a href="kontakt.html" class="btn" style="background:#fff;color:#6b5226;box-shadow:0 14px 30px rgba(0,0,0,.35)">Skontaktuj się z nami</a>
      </div>
    </div>''')}
  </div>
</section>
"""
    page(
        "o-nas.html",
        "O nas | Producent papieru PaperNest",
        "PaperNest to polski producent papieru w rolkach, wypełniaczy papierowych, papieru dla piskląt i tektury budowlanej. Oferujemy przewijanie i cięcie papieru.",
        "o-nas.html",
        content,
    )
