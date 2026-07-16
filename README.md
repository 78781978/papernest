# PaperNest — premium prototyp (statyczny)

Statyczny prototyp HTML/CSS/JS odwzorowujący strukturę i funkcje papernest.pl
(WordPress + Elementor + WooCommerce) w podniesionej, premium wersji wizualnej.
Zachowane: logo (placeholder — patrz niżej), paleta marki, wszystkie podstrony
i funkcje (sklep, koszyk, konto, formularze, cookie banner, strony prawne).

## Podgląd

Otwórz `index.html` bezpośrednio w przeglądarce, albo uruchom lokalny serwer:

```bash
python3 -m http.server 8000
# http://localhost:8000
```

## Struktura

- `*.html` — gotowe, wygenerowane podstrony (17 stron)
- `assets/css/style.css` — cały system designu (kolory, typografia, komponenty)
- `assets/js/main.js` — interakcje (menu mobilne, warianty produktów, koszyk UI, cookie banner)
- `assets/img/logo/mark.svg` — **placeholder** loga (do podmiany na prawdziwe)
- `assets/img/illustrations/` — autorskie ilustracje SVG produktów (do podmiany na zdjęcia, jeśli wolisz)
- `tools/` — generator: strony budowane są z współdzielonego nagłówka/stopki,
  żeby nawigacja i stopka nigdy się nie rozjechały między podstronami.
  Po edycji treści w `tools/pages_*.py` uruchom `python3 tools/run.py`,
  żeby przebudować pliki `.html` w katalogu głównym.
- `tools/legal_src/` — oryginalne treści prawne (Regulamin, Polityka prywatności,
  Reklamacje, Prawo odstąpienia) wyeksportowane 1:1 z WordPressa.

## Do zrobienia przed wdrożeniem

1. **Logo** — podmienić `assets/img/logo/mark.svg` na właściwy plik loga PaperNest.
2. **Zdjęcia produktowe** (opcjonalnie) — obecnie użyto autorskich ilustracji SVG
   zamiast zdjęć (nie miałem dostępu do oryginalnych plików graficznych ze strony).
3. Mapa na stronie Kontakt używa darmowego OpenStreetMap (bez klucza API) —
   można podmienić na Google Maps, jeśli wolisz.
4. Formularze (kontakt, koszyk, logowanie, odstąpienie od umowy) są statyczne —
   po akceptacji designu trzeba je przepiąć pod prawdziwy motyw WordPress/WooCommerce.
