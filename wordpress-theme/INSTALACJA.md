# Motyw WordPress „PaperNest” — instrukcja instalacji

Ten katalog zawiera gotowy motyw WordPress (`papernest/`) zbudowany na
podstawie zaakceptowanego prototypu — ten sam wygląd, ale wszystko można już
edytować z panelu wp-admin. Sklep (produkty, koszyk, zamówienia) jest
**własnym, wbudowanym w motyw rozwiązaniem — bez WooCommerce**: prościej,
lżej i bez ryzyka niedopasowania stylu, które WooCommerce potrafi wprowadzać.

## 1. Wymagania

- WordPress (najnowsza wersja, hosting z PHP 7.4+)
- Żadnych dodatkowych wtyczek e-commerce nie trzeba instalować — sklep
  działa od razu po aktywacji motywu.

## 2. Instalacja motywu

1. Spakuj folder `papernest/` do pliku `papernest.zip` (albo użyj gotowego
   pliku zip, jeśli go otrzymałaś/eś razem z tą instrukcją).
2. W panelu WordPress: **Wygląd → Motywy → Dodaj nowy → Wyślij motyw** →
   wybierz `papernest.zip` → **Zainstaluj** → **Aktywuj**.

## 3. Treść startowa (strony, przykładowe produkty, opinie, portfolio)

Motyw **nie** importuje niczego automatycznie — celowo, żeby aktywacja
motywu na stronie, na której jest już jakaś treść, niczego nie namieszała.
Import startowy uruchamiasz ręcznie, kiedy sama/sam zdecydujesz:

**Wygląd → Treść startowa → Importuj / uzupełnij treść startową**

Przycisk można kliknąć bezpiecznie wielokrotnie — nic nie zduplikuje.
Utworzy/uzupełni:

- strony: Sklep, Koszyk, Zamówienie, Status zamówienia, O nas, Portfolio,
  Kontakt, Płatność i Dostawa, Regulamin, Politykę prywatności, Reklamacje,
  Prawo do odstąpienia od umowy, Deklarację dostępności, Odstąpienie od
  umowy,
- menu główne (Home, Sklep, O Nas, Portfolio, Kontakt),
- 3 przykładowe produkty (Wypełniacz Papierowy, Papier Dla Piskląt, Tektura
  Budowlana) — każdy z 4 wariantami cenowymi (1 rolka, 2 rolki, 4 rolki,
  paleta), tak jak w prototypie,
- 4 przykładowe opinie klientów,
- 12 kafelków portfolio („zastosowania”).

## 4. Jak działa sklep (bez WooCommerce)

- **Produkty** — osobna sekcja „Produkty” w menu wp-admin. Każdy produkt ma
  nazwę, opis, zdjęcie główne, do 4 dodatkowych zdjęć w galerii oraz dowolną
  liczbę wariantów cenowych (nazwa, podtytuł, cena, opcjonalna cena przed
  obniżką) — edytowane w tabelce na stronie produktu, bez żadnego
  dodatkowego panelu.
- **Koszyk** — po stronie serwera (sesja), klient dodaje produkty, zmienia
  ilości i usuwa pozycje; ceny zawsze liczone od nowa z aktualnych danych
  produktu (nikt nie może „podmienić” ceny w przeglądarce).
- **Zamówienie** — klient podaje dane, wybiera dostawę (Paczkomat InPost /
  kurier DPD) i płatność (**przelew tradycyjny** lub **za pobraniem** — tylko
  przy kurierze). Po złożeniu zamówienia system wysyła e-mail z
  potwierdzeniem do klienta i powiadomienie do Ciebie.
- **Zamówienia** — osobna sekcja w menu wp-admin: lista wszystkich zamówień
  z danymi klienta, sumą, sposobem płatności/dostawy i statusem, który
  zmienisz jednym kliknięciem wprost z listy (Nowe → W realizacji → Wysłane
  → Zrealizowane / Anulowane). Zmiana statusu na „Wysłane” automatycznie
  wysyła klientowi e-mail.
- **Ustawienia sklepu** (Produkty → Ustawienia sklepu) — numer konta do
  przelewów, koszt wysyłki Paczkomatem i kurierem, próg darmowej dostawy,
  włącz/wyłącz płatność za pobraniem.

### Płatności online (BLIK, karta, szybki przelew)

Na start klienci płacą **przelewem tradycyjnym** (dane do przelewu w mailu
z potwierdzeniem) albo **za pobraniem**. Prawdziwe płatności online wymagają
konta u dostawcy płatności (np. paynow, Przelewy24, PayU, Tpay) i jego
kluczy API — to zawsze wymaga działania właściciela konta, żadne
rozwiązanie (WooCommerce też) nie obejdzie tego kroku. Gdy założysz takie
konto, podłączenie go do tego sklepu to już tylko dopisanie jednej
integracji — z przyjemnością to zrobimy.

## 5. Co można edytować i gdzie

| Co | Gdzie w wp-admin |
|---|---|
| Logo | Wygląd → Dostosuj → Identyfikacja strony |
| Telefon, e-mail, adres, godziny, link do Facebooka | Wygląd → Dostosuj → Dane kontaktowe PaperNest |
| Zdjęcia (magazyn, sklep, biuro, karty „O nas”, logotypy kurierów) | Wygląd → Dostosuj → Zdjęcia na stronie |
| Produkty, warianty i ceny, zdjęcia produktów, opisy | Produkty |
| Ustawienia sklepu (konto bankowe, koszty wysyłki) | Produkty → Ustawienia sklepu |
| Zamówienia i ich status | Zamówienia |
| Opinie klientów | Opinie klientów (menu boczne) |
| Kafelki portfolio + zdjęcia | Portfolio / Zastosowania (menu boczne) |
| Treść stron (O nas, Regulamin, Polityka prywatności, itd.) | Strony → edytuj dowolną |
| Menu | Wygląd → Menu |

## 6. Formularze (kontakt i zamówienia)

Formularz kontaktowy i potwierdzenia zamówień wysyłają e-mail przez
wbudowaną funkcję WordPressa (`wp_mail`). Wiele hostingów domyślnie dobrze
obsługuje wysyłkę maili, ale jeśli wiadomości nie będą docierać, zalecamy
doinstalować darmową wtyczkę **WP Mail SMTP** i podłączyć ją pod prawdziwą
skrzynkę e-mail (np. Gmail/Office 365) — to najczęstsza przyczyna
„znikających” maili z formularzy na WordPressie.

## 7. Bezpośrednie odnośniki (permalinki)

Zalecamy: **Ustawienia → Bezpośrednie odnośniki → Nazwa wpisu**, żeby adresy
stron wyglądały ładnie (np. `papernest.pl/o-nas/`).

## 8. Uwaga dot. testów

Cały sklep (produkty, koszyk, wybór dostawy/płatności, składanie zamówienia,
e-maile, panel zamówień w wp-admin) został zbudowany i **realnie przetestowany
od początku do końca na działającym WordPressie** — łącznie z edycją produktu
i zapisem zmian w panelu administracyjnym. To jest inaczej niż przy
poprzedniej wersji z WooCommerce, której nie dało się uruchomić w środowisku,
w którym motyw powstawał — tym razem nie ma tego ograniczenia.
