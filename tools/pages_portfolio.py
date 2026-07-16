# -*- coding: utf-8 -*-
from build import icon, svg_file, reveal, page

VALUES = [
    ("sparkle", "Satysfakcja", "Jakość, która przewyższa oczekiwania."),
    ("trend", "Innowacja", "Innowacyjne rozwiązania dla trwałego rozwoju i sukcesu."),
    ("shield", "Rozpoznawalność", "Budujemy markę, która pozostawia trwałe wrażenie."),
    ("leaf", "Rozwój", "Stawiamy na ciągły rozwój i nieustanne doskonalenie."),
]

USES = [
    ("box", "E-commerce i logistyka"),
    ("palette", "Zajęcia kreatywne"),
    ("egg", "Podłoże pokarmu dla piskląt"),
    ("gift", "ECO pakowanie prezentów"),
    ("truck", "Wyściółka transporterów"),
    ("wrench", "Papier dla mechaników"),
    ("leaf", "Papier dla florystów"),
    ("shield", "Tektura zabezpieczająca"),
    ("layers", "Tektura tapicerska"),
    ("vet", "Papier dla weterynarzy"),
    ("die", "Tektura do wykrojników"),
    ("blueprint", "Tektura do makiet"),
]


def build():
    value_cards = "".join(reveal(f'''<div class="use-card">
      <span class="ic">{icon(ic)}</span><b>{title}</b>
      <span class="text-muted" style="font-size:.85rem">{desc}</span>
    </div>''') for ic, title, desc in VALUES)

    use_cards = "".join(reveal(f'''<div class="use-card">
      <span class="ic">{icon(ic)}</span><b>{title}</b>
    </div>''') for ic, title in USES)

    content = f"""
<section class="page-hero">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Portfolio</span></div>
    <h1>Zobacz nasze produkty w różnych zastosowaniach</h1>
    <p>PaperNest w różnych zastosowaniach — odkryj, gdzie nasz papier w rolkach sprawdza się najlepiej.</p>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="use-grid" style="grid-template-columns:repeat(4,1fr)">{value_cards}</div>
  </div>
</section>

<section class="section">
  <div class="container">
    {reveal('''<div class="section-head center">
      <span class="eyebrow" style="justify-content:center">Przykłady zastosowań</span>
      <h2 class="text-balance">Jeden papier, dziesiątki możliwości</h2>
      <p>Od logistyki e-commerce po pracownie kreatywne i gabinety weterynaryjne — nasze rolki dopasowują się do branży klienta.</p>
    </div>''')}
    <div class="use-grid reveal-stagger">{use_cards}</div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    {reveal(f'''<div class="cta-banner">
      <div>
        <h2>Nie znalazłeś swojego zastosowania?</h2>
        <p>Doradzimy, który papier PaperNest najlepiej sprawdzi się w Twojej branży — napisz do nas lub zadzwoń.</p>
      </div>
      <div class="cta-actions">
        <a href="kontakt.html" class="btn" style="background:#fff;color:#3d5708;box-shadow:0 14px 30px rgba(0,0,0,.25)">Skontaktuj się {icon('arrow')}</a>
      </div>
    </div>''')}
  </div>
</section>
"""
    page(
        "portfolio.html",
        "Portfolio | Zastosowania produktów PaperNest",
        "Zobacz, w jakich branżach i zastosowaniach sprawdza się papier w rolkach PaperNest — od e-commerce po weterynarię.",
        "portfolio.html",
        content,
    )
