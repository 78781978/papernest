# -*- coding: utf-8 -*-
from build import icon, reveal, page


def build():
    content = f"""
<section class="page-hero" style="padding-block:56px 40px">
  <div class="container">
    <div class="crumbs"><a href="index.html">Strona główna</a> <span>/</span> <span>Deklaracja dostępności</span></div>
    <h1>Deklaracja dostępności</h1>
    <p>P.H.U „Bobinex" Grzegorz Działkowski zobowiązuje się zapewnić dostępność serwisu papernest.pl zgodnie z ustawą z dnia 4 kwietnia 2019 r. o dostępności cyfrowej stron internetowych i aplikacji mobilnych podmiotów publicznych oraz wytycznymi WCAG 2.1.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col" style="grid-template-columns:.7fr 2fr;align-items:start">
      <aside class="legal-toc reveal" style="position:sticky;top:110px">
        <h4>Spis treści</h4>
        <a href="#status">Status zgodności</a>
        <a href="#ulatwienia">Zastosowane ułatwienia</a>
        <a href="#ograniczenia">Znane ograniczenia</a>
        <a href="#przygotowanie">Przygotowanie deklaracji</a>
        <a href="#kontakt">Zgłaszanie uwag</a>
        <a href="#procedura">Procedura odwoławcza</a>
        <a href="#dostep-alternatywny">Dostęp alternatywny</a>
      </aside>
      <div class="legal-shell reveal" style="max-width:none">

        <div class="legal-note">
          Ten serwis jest <strong>statycznym prototypem projektowym</strong> przygotowanym do akceptacji wyglądu. Poniższa deklaracja opisuje stan dostępności prototypu i intencje wdrożeniowe — po uruchomieniu docelowej strony na WordPressie deklaracja zostanie zaktualizowana i opublikowana pod stałym adresem.
        </div>

        <h5 id="status">Status zgodności</h5>
        <p>Serwis jest <strong>częściowo zgodny</strong> z ustawą o dostępności cyfrowej z powodu niezgodności lub wyłączeń wymienionych poniżej. Projekt został przygotowany z myślą o spełnieniu wytycznych <strong>WCAG 2.1 na poziomie AA</strong>.</p>

        <h5 id="ulatwienia">Zastosowane ułatwienia</h5>
        <ul class="icon-list">
          <li>{icon('check')}<span>Kontrast tekstu do tła zweryfikowany matematycznie — minimum 4,5:1 dla tekstu podstawowego (WCAG 1.4.3), a w większości przypadków ponad 7:1 (poziom AAA).</span></li>
          <li>{icon('check')}<span>Pełna obsługa klawiatury — nawigacja, menu mobilne, warianty produktów, panel preferencji cookie i formularze działają bez użycia myszy.</span></li>
          <li>{icon('check')}<span>Widoczny wskaźnik fokusu (focus-visible) na wszystkich elementach interaktywnych.</span></li>
          <li>{icon('check')}<span>Link „Przejdź do treści" (skip link) pozwalający pominąć powtarzalną nawigację.</span></li>
          <li>{icon('check')}<span>Poszanowanie systemowego ustawienia „ogranicz animacje" (prefers-reduced-motion) — wszystkie animacje są wtedy wyłączane.</span></li>
          <li>{icon('check')}<span>Semantyczna struktura nagłówków (h1–h6), punkty orientacyjne (nav, main, footer) i etykiety pól formularzy powiązane z opisami (label, aria-describedby).</span></li>
          <li>{icon('check')}<span>Panel zgód na cookies zbudowany na natywnych, dostępnych komponentach (checkbox jako switch) z pułapką fokusu (focus trap) i obsługą klawisza Esc.</span></li>
          <li>{icon('check')}<span>Elementy czysto dekoracyjne (ikony SVG przy tekście) są ukryte przed czytnikami ekranu, aby nie dublować treści.</span></li>
        </ul>

        <h5 id="ograniczenia">Znane ograniczenia</h5>
        <ul class="icon-list">
          <li>{icon('info')}<span>Mapa na stronie Kontakt (osadzona z OpenStreetMap) nie posiada w pełni dostępnej alternatywy tekstowej — adres znajduje się jednak w pełni w tekście obok mapy.</span></li>
          <li>{icon('info')}<span>Ilustracje produktowe SVG są dekoracyjne (produkty opisane są tekstowo w kartach) — nie zastępują one zdjęć rzeczywistych produktów.</span></li>
          <li>{icon('info')}<span>Formularze (kontakt, logowanie, koszyk) są w tej wersji statyczne/demonstracyjne — pełna walidacja i komunikaty błędów zostaną wdrożone wraz z podłączeniem do WordPressa/WooCommerce.</span></li>
        </ul>

        <h5 id="przygotowanie">Przygotowanie deklaracji dostępności</h5>
        <p>Deklarację sporządzono dnia <strong>16 lipca 2026 r.</strong> Deklarację sporządzono na podstawie samooceny przeprowadzonej metodą przeglądu eksperckiego (analiza kontrastu, nawigacji klawiaturą i struktury semantycznej) oraz automatycznych narzędzi wspierających audyt dostępności.</p>

        <h5 id="kontakt">Zgłaszanie uwag i problemów z dostępnością</h5>
        <p>Jeśli napotkasz problem z dostępnością treści na tej stronie, skontaktuj się z nami:</p>
        <ul class="icon-list">
          <li>{icon('mail')}<span>E-mail: <strong>gd@papernest.pl</strong></span></li>
          <li>{icon('phone')}<span>Telefon: <strong>538 989 005</strong></span></li>
        </ul>
        <p>Tą samą drogą można składać wnioski o udostępnienie informacji niedostępnej oraz żądania zapewnienia dostępności.</p>

        <h5 id="procedura">Procedura odwoławcza</h5>
        <p>Jeżeli zgłoszenie problemu z dostępnością nie zostanie rozpatrzone w sposób satysfakcjonujący, masz prawo złożyć skargę do <strong>Rzecznika Praw Obywatelskich</strong> (<a href="https://www.rpo.gov.pl">www.rpo.gov.pl</a>).</p>

        <h5 id="dostep-alternatywny">Dostęp alternatywny</h5>
        <p>Jeśli nie możesz skorzystać z elektronicznej formy kontaktu, informacje o produktach i ofercie PaperNest uzyskasz telefonicznie pod numerem <strong>538 989 005</strong> (Pon–Pt, 8:00–16:00) lub odwiedzając biuro sprzedaży pod adresem I Brygady Legionów 12-14, 72-100 Goleniów.</p>

      </div>
    </div>
  </div>
</section>
"""
    page(
        "dostepnosc.html",
        "Deklaracja dostępności | PaperNest",
        "Deklaracja dostępności cyfrowej serwisu PaperNest zgodnie z WCAG 2.1 i ustawą o dostępności cyfrowej.",
        None,
        content,
    )
