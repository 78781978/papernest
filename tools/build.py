#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
PaperNest — generator statycznego prototypu premium.
Zbiera wspólny header/footer i wypełnia go treścią każdej podstrony,
żeby nawigacja i stopka pozostały identyczne na całym serwisie.

Uruchomienie:  python3 tools/build.py
Wynik trafia do katalogu głównego repo (pliki .html obok assets/).
"""
import os, re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

# ---------------------------------------------------------------- ICONS ----
ICON = {
"phone": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5.5c0-1.1.9-2 2-2h2.1c.5 0 1 .4 1.1.9l.9 3.7c.1.4 0 .9-.3 1.2L7.4 10.7a13.7 13.7 0 0 0 5.9 5.9l1.4-1.4c.3-.3.8-.4 1.2-.3l3.7.9c.5.1.9.6.9 1.1V19c0 1.1-.9 2-2 2h-1C9.8 21 3 14.2 3 6.5v-1Z"/></svg>',
"mail": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.5 6.5h17v11h-17z"/><path d="m4 7 8 6 8-6"/></svg>',
"pin": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.6 7-11.5A7 7 0 0 0 5 9.5C5 14.4 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>',
"clock": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/></svg>',
"facebook": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8.5h-2c-.8 0-1.5.7-1.5 1.5v2h3.4l-.4 3h-3v7.5h-3V15h-2v-3h2v-2.2C8.5 7 10 5.5 12.6 5.5H15v3Z"/></svg>',
"cart": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 12.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 8H6"/><circle cx="10" cy="21" r="1.3"/><circle cx="17" cy="21" r="1.3"/></svg>',
"user": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.5 20c1.4-3.4 4.3-5 7.5-5s6.1 1.6 7.5 5"/></svg>',
"search": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m20 20-4.3-4.3"/></svg>',
"check": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 9.5 18 20 6"/></svg>',
"arrow": '<svg class="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
"star": '<svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1 5.9L10 14.9l-5.2 2.8 1-5.9L1.5 7.7l5.9-.8L10 1.5Z"/></svg>',
"truck": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h11v9H3z"/><path d="M14 11h4l3 3v2h-7z"/><circle cx="7.5" cy="18" r="1.6"/><circle cx="17.5" cy="18" r="1.6"/></svg>',
"shield": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v6c0 4.5-3 7.7-7 9-4-1.3-7-4.5-7-9V6l7-3Z"/><path d="m9 12 2 2 4-4.2"/></svg>',
"factory": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V11l5 3.2V11l5 3.2V8l6 4v9H3Z"/><path d="M7 21v-4M12 21v-4M17 21v-4"/></svg>',
"leaf": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19c9 0 14-5 14-14-9 0-14 5-14 14Z"/><path d="M5 19c0-5 3-8 8-9"/></svg>',
"chevronUp": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 15 6-6 6 6"/></svg>',
"reel": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="3"/><path d="M12 3.5V6M12 18v2.5M3.5 12H6M18 12h2.5"/></svg>',
"box": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8.5 12 4l9 4.5-9 4.5-9-4.5Z"/><path d="M3 8.5V17l9 4.5 9-4.5V8.5"/><path d="M12 13v8.5"/></svg>',
"scissors": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2.4"/><circle cx="6" cy="18" r="2.4"/><path d="M20 4 7.6 15.6M8 8.4 20 20"/></svg>',
"egg": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21c4 0 6.5-3.4 6.5-7.6C18.5 8 15 3 12 3S5.5 8 5.5 13.4C5.5 17.6 8 21 12 21Z"/></svg>',
"hammer": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m14.5 6.5 3 3-8 8-3.5.5.5-3.5 8-8Z"/><path d="M13 4l3 3M4 20l3.5-3.5"/></svg>',
"sparkle": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/></svg>',
"trend": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17 9.5 10.5 14 15 21 7"/><path d="M15 7h6v6"/></svg>',
"gift": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v4H3z"/><path d="M5 13h14v8H5zM12 9v12"/><path d="M12 9c-1.5 0-3.5-.6-3.5-2.5S10 4 12 6c0-2 2-3 3.5-1.5S13.5 9 12 9Z"/></svg>',
"palette": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a9 8 0 1 0 0 16c1.4 0 2-1 2-2s-.6-1.4-1-2c-.5-.7 0-2 1.3-2H16a4 4 0 0 0 4-4c0-3.3-3.6-6-8-6Z"/><circle cx="8" cy="11" r="1"/><circle cx="12" cy="8" r="1"/><circle cx="16" cy="11" r="1"/></svg>',
"wrench": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5l-6 6L6 20l2.7-2.7 6-6a4 4 0 0 0 5-5.4l-2.8 2.8-2-2 2.8-2.8Z"/></svg>',
"vet": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 8V4M15 8V4M9 4h0M15 4h0"/><path d="M7 8c0 3 1.5 4.5 5 4.5S17 11 17 8"/><path d="M12 12.5V17"/><circle cx="12" cy="19" r="2"/></svg>',
"die": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h.01M15 9h.01M9 15h.01M15 15h.01M12 12h.01"/></svg>',
"layers": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 13 9 5 9-5M3 8l9 5 9-5"/></svg>',
"blueprint": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h13l3 3v13H4Z"/><path d="M8 9h6M8 13h9M8 17h6"/></svg>',
"pallet": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18M3 13h18"/><path d="M5 9v9M12 9v9M19 9v9M3 18h18"/></svg>',
"minus": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M5 12h14"/></svg>',
"plus": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>',
"info": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5.5M12 8v.01"/></svg>',
"image": '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="15" rx="2"/><circle cx="8.5" cy="10" r="1.6"/><path d="m4 17 5-5 3.5 3.5L17 11l3 3.5"/></svg>',
}

def icon(name, cls=""):
    """Ikony są tu zawsze dekoracyjne (towarzyszą widocznemu tekstowi obok) —
    aria-hidden chroni przed dublowaniem treści w czytnikach ekranu (WCAG 1.1.1)."""
    svg = ICON[name]
    attrs = 'aria-hidden="true" focusable="false"'
    if cls:
        attrs = f'class="{cls}" ' + attrs
    svg = svg.replace("<svg ", f"<svg {attrs} ", 1)
    return svg

def logo_mark():
    """Znak marki, zawsze wyświetlany obok widocznego tekstu "PaperNest" —
    dekoracyjny z punktu widzenia czytnika ekranu."""
    svg = open(os.path.join(ROOT, "assets/img/logo/mark.svg"), encoding="utf-8").read()
    return svg.replace("<svg ", '<svg aria-hidden="true" focusable="false" ', 1)

# ---------------------------------------------------------------- NAV ----
NAV_ITEMS = [
    ("index.html", "Home"),
    ("sklep.html", "Sklep"),
    ("o-nas.html", "O Nas"),
    ("portfolio.html", "Portfolio"),
    ("kontakt.html", "Kontakt"),
]

def header(active):
    links = ""
    for href, label in NAV_ITEMS:
        cls = " active" if href == active else ""
        links += f'<li><a href="{href}" class="{cls.strip()}">{label}</a></li>'
    return f"""
<a class="skip-link" href="#main">Przejdź do treści</a>
<header class="site-header">
  <div class="container header-row">
    <a href="index.html" class="brand" aria-label="PaperNest — strona główna">
      <span class="brand-mark">{logo_mark()}</span>
      <span class="brand-word">PaperNest<small>Producent papieru w rolkach</small></span>
    </a>
    <nav class="main-nav" id="main-nav">
      <ul>{links}</ul>
    </nav>
    <div class="header-actions">
      <button class="icon-btn" type="button" aria-label="Szukaj">{icon('search')}</button>
      <a class="icon-btn" href="moje-konto.html" aria-label="Moje konto">{icon('user')}</a>
      <a class="icon-btn" href="koszyk.html" aria-label="Koszyk">{icon('cart')}<span class="cart-count">0</span></a>
      <button class="nav-toggle" id="nav-toggle" aria-label="Otwórz menu" aria-expanded="false" aria-controls="main-nav"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="nav-scrim"></div>
"""

def footer():
    return f"""
<footer class="site-footer">
  <div class="container footer-top">
    <div class="footer-brand">
      <a href="index.html" class="brand">
        <span class="brand-mark">{logo_mark()}</span>
        <span class="brand-word">PaperNest<small>Producent papieru w rolkach</small></span>
      </a>
      <p>PaperNest to producent z 25-letnim doświadczeniem na rynku. Oferujemy wypełniacze papierowe na rolkach, papiery do kurników oraz papiery remontowe – dla firm z całej Polski.</p>
      <div class="footer-social">
        <a href="https://pl-pl.facebook.com/Papernestapp/" aria-label="Facebook" target="_blank" rel="noopener">{icon('facebook')}</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Informacje</h4>
      <ul>
        <li><a href="regulamin.html">Regulamin</a></li>
        <li><a href="polityka-prywatnosci.html">Polityka prywatności</a></li>
        <li><a href="reklamacje.html">Reklamacje</a></li>
        <li><a href="odstapienie.html">Odstąpienie od umowy</a></li>
        <li><a href="platnosc-i-dostawa.html">Płatność i Dostawa</a></li>
        <li><a href="dostepnosc.html">Deklaracja dostępności</a></li>
        <li><button type="button" class="link-btn" data-open-consent>Zarządzaj zgodami</button></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Konto</h4>
      <ul>
        <li><a href="moje-konto.html">Moje Konto</a></li>
        <li><a href="moje-konto.html">Zamówienia</a></li>
        <li><a href="koszyk.html">Koszyk</a></li>
        <li><a href="sklep.html">Sklep</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Firma</h4>
      <ul>
        <li><a href="o-nas.html">O nas</a></li>
        <li><a href="portfolio.html">Portfolio</a></li>
        <li><a href="kontakt.html">Kontakt</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Kontakt</h4>
      <div class="contact-line">{icon('pin')}<span>I Brygady Legionów 12-14<br>72-100 Goleniów</span></div>
      <div class="contact-line" style="margin-top:.7em"><a href="{PHONE_TEL}">{icon('phone')}<span>{PHONE_DISPLAY}</span></a></div>
      <div class="contact-line" style="margin-top:.7em"><a href="mailto:{EMAIL}">{icon('mail')}<span>{EMAIL}</span></a></div>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>2026 © Copyright by PaperNest — P.H.U „Bobinex” Grzegorz Działkowski</span>
    <div class="langs">
      <span class="active">Polski</span><span>English</span><span>Deutsch</span><span>Español</span><span>Français</span>
    </div>
  </div>
</footer>
<button class="to-top" aria-label="Wróć na górę">{icon('chevronUp')}</button>

<!-- ============ Baner zgody na cookies (pierwsza warstwa RODO) ============ -->
<div class="cookie-banner" role="dialog" aria-modal="false" aria-labelledby="cookie-title" aria-describedby="cookie-desc" aria-hidden="true">
  <div class="cookie-banner-icon" aria-hidden="true">{icon('shield')}</div>
  <div class="cookie-banner-body">
    <p id="cookie-title"><strong>Dbamy o Twoją prywatność.</strong></p>
    <p id="cookie-desc">Używamy plików cookies, aby zapewnić prawidłowe działanie strony, analizować ruch oraz — za Twoją zgodą — dopasowywać treści i działania marketingowe. Szczegóły znajdziesz w <a href="polityka-prywatnosci.html">Polityce prywatności</a>. Zgodę możesz wycofać lub zmienić w każdej chwili w stopce strony.</p>
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
    <p>Wybierz, na jakie kategorie plików cookie się zgadzasz. Niezbędne pliki cookie są zawsze aktywne, ponieważ bez nich strona nie działałaby prawidłowo. Więcej informacji znajdziesz w <a href="polityka-prywatnosci.html">Polityce prywatności</a>.</p>

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
"""

HEAD = """<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{title}</title>
<meta name="description" content="{desc}">
<link rel="icon" href="assets/img/logo/mark.svg" type="image/svg+xml">
<link rel="canonical" href="https://papernest.pl/{slug}">
<meta property="og:title" content="{title}">
<meta property="og:description" content="{desc}">
<meta property="og:type" content="website">
<meta property="og:locale" content="pl_PL">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
"""

def page(slug, title, desc, active, content, extra_head=""):
    html = f"""<!DOCTYPE html>
<html lang="pl-PL">
<head>
{HEAD.format(title=title, desc=desc, slug=slug)}{extra_head}</head>
<body>
{header(active)}
<main id="main">
{content}
</main>
{footer()}
<script src="assets/js/main.js"></script>
</body>
</html>
"""
    with open(os.path.join(ROOT, slug), "w", encoding="utf-8") as f:
        f.write(html)
    print("built", slug)

def svg_file(path):
    """Duże ilustracje są czysto dekoracyjne — produkt/sekcja jest już opisana
    tekstem obok, więc czytnik ekranu ma je pomijać (WCAG 1.1.1)."""
    svg = open(os.path.join(ROOT, "assets/img/illustrations", path), encoding="utf-8").read()
    return svg.replace("<svg ", '<svg aria-hidden="true" focusable="false" ', 1)

def reveal(content, extra_cls=""):
    return f'<div class="reveal {extra_cls}">{content}</div>'

# ---------------------------------------------------------------- SHARED CONTACT ----
PHONE_DISPLAY = "538 989 005"
PHONE_TEL = "tel:+48538989005"
EMAIL = "gd@papernest.pl"

def product_contact_block():
    """Stały blok kontaktowy wyświetlany na każdej karcie produktu pod ceną —
    zastępuje krótki opis, żeby klient od razu widział, do kogo pisać/dzwonić."""
    return f"""<div class="product-contact">
      <p>Masz pytania lub potrzebujesz większego zamówienia?<br>Skontaktuj się z nami:</p>
      <p class="pc-name">Grzegorz Działkowski</p>
      <a class="pc-line" href="{PHONE_TEL}">{icon('phone')}tel. {PHONE_DISPLAY}</a>
      <a class="pc-line" href="mailto:{EMAIL}">{icon('mail')}{EMAIL}</a>
    </div>"""

def photo_placeholder(label, cls=""):
    """Wizualne miejsce zarezerwowane pod przyszłe zdjęcie — wyraźnie oznaczone,
    żeby przy podmianie na WordPressie było wiadomo, gdzie wstawić plik."""
    return f"""<div class="photo-slot {cls}">
      <span class="photo-slot-ic">{icon('image')}</span>
      <span class="photo-slot-label">{label}</span>
    </div>"""
