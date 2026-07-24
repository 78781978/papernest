# Motyw WordPress „PaperNest” — instrukcja instalacji

Ten katalog zawiera gotowy motyw WordPress (`papernest/`) zbudowany na
podstawie zaakceptowanego prototypu — ten sam wygląd, ale wszystko można już
edytować z panelu wp-admin. Sklep działa na **WooCommerce**.

## 1. Wymagania

- WordPress (najnowsza wersja, hosting z PHP 7.4+)
- Wtyczka **WooCommerce** (darmowa, z katalogu wtyczek WordPress)

## 2. Instalacja motywu

1. Spakuj folder `papernest/` do pliku `papernest.zip` (albo użyj gotowego
   pliku zip, jeśli go otrzymałaś/eś razem z tą instrukcją).
2. W panelu WordPress: **Wygląd → Motywy → Dodaj nowy → Wyślij motyw** →
   wybierz `papernest.zip` → **Zainstaluj** → **Aktywuj**.

## 3. Instalacja WooCommerce (sklep)

1. **Wtyczki → Dodaj nową** → wyszukaj „WooCommerce” → **Zainstaluj** →
   **Aktywuj**.
2. WooCommerce zapyta o mały kreator konfiguracji (adres firmy, waluta PLN,
   metody wysyłki/płatności) — możesz go przejść lub pominąć, dane da się
   zmienić później w **WooCommerce → Ustawienia**.
3. WooCommerce sam utworzy strony Sklep, Koszyk, Zamówienie i Moje konto.
4. **Ważne — tryb „Coming soon”:** świeżo zainstalowane WooCommerce domyślnie
   ukrywa sklep pod komunikatem „Coming soon”/„Wkrótce”. Kiedy będziesz
   gotowa pokazać sklep realnym klientom: **WooCommerce → Ustawienia →
   Ogólne** (albo baner na górze ekranu) → wyłącz tryb „Coming soon”.
5. **Waluta:** upewnij się, że w **WooCommerce → Ustawienia → Ogólne**
   waluta jest ustawiona na **PLN (zł)** — kreator zwykle ustawia to
   automatycznie po wybraniu Polski jako kraju, ale warto zerknąć.

## 4. Treść startowa (strony, przykładowe produkty, opinie, portfolio)

Motyw **nie** importuje niczego automatycznie przy aktywacji — celowo, żeby
nie namieszać na stronie, na której jest już jakaś treść. Import startowy
uruchamiasz ręcznie, najlepiej **po** instalacji WooCommerce (punkt 3
powyżej):

**Wygląd → Treść startowa → Importuj / uzupełnij treść startową**

Przycisk można kliknąć bezpiecznie wielokrotnie — nic nie zduplikuje.
Utworzy/uzupełni:

- strony: O nas, Portfolio, Kontakt, Płatność i Dostawa, Regulamin, Politykę
  prywatności, Reklamacje, Prawo do odstąpienia od umowy, Deklarację
  dostępności, Odstąpienie od umowy,
- menu główne (Home, Sklep, O Nas, Portfolio, Kontakt),
- 3 przykładowe produkty w WooCommerce (Wypełniacz Papierowy, Papier Dla
  Piskląt, Tektura Budowlana) — każdy z 4 wariantami cenowymi (1 rolka, 2
  rolki, 4 rolki, paleta), tak jak w prototypie,
- 4 przykładowe opinie klientów,
- 12 kafelków portfolio („zastosowania”),
- **dwie metody płatności włączone od razu: przelew tradycyjny i za
  pobraniem** (patrz punkt 5) — bez tego sklep nie przyjąłby żadnego
  zamówienia.

## 5. Płatności

Import startowy od razu włącza w WooCommerce dwie metody płatności, które
nie wymagają żadnego konta ani kluczy API:

- **Przelew tradycyjny** — klient dostaje dane do przelewu w mailu z
  potwierdzeniem. Numer konta wpisz w **WooCommerce → Ustawienia →
  Płatności → Przelew bankowy**.
- **Za pobraniem** — płatność kurierowi przy odbiorze.

**Prawdziwe płatności online** (BLIK, karta, szybki przelew — np. przez
paynow, o którym mowa na stronie Płatność i Dostawa) wymagają założenia
konta u dostawcy płatności i podłączenia jego wtyczki/kluczy API w
**WooCommerce → Ustawienia → Płatności** — to jedyny krok, którego nikt poza
Tobą nie może wykonać, niezależnie od tego, jak zbudowany jest sklep.

## 6. Dostawa

W **WooCommerce → Ustawienia → Dostawa** skonfiguruj strefę wysyłki dla
Polski z metodami **Paczkomat InPost** i **Kurier DPD** (jako stawki stałe —
WooCommerce nie ma tego wbudowanego pod tymi nazwami, trzeba dodać ręcznie
jako metody „Stała stawka” w strefie wysyłki „Polska”, i nazwać je
odpowiednio).

## 7. Co można edytować i gdzie

| Co | Gdzie w wp-admin |
|---|---|
| Logo | Wygląd → Dostosuj → Identyfikacja strony |
| Telefon, e-mail, adres, godziny, link do Facebooka | Wygląd → Dostosuj → Dane kontaktowe PaperNest |
| Zdjęcia (magazyn, sklep, biuro, karty „O nas”, logotypy kurierów) | Wygląd → Dostosuj → Zdjęcia na stronie |
| Produkty, warianty i ceny, zdjęcia produktów, opisy | Produkty |
| Zamówienia i ich status | WooCommerce → Zamówienia |
| Płatności i dostawa | WooCommerce → Ustawienia |
| Opinie klientów | Opinie klientów (menu boczne) |
| Kafelki portfolio + zdjęcia | Portfolio / Zastosowania (menu boczne) |
| Treść stron (O nas, Regulamin, Polityka prywatności, itd.) | Strony → edytuj dowolną |
| Menu | Wygląd → Menu |

Zdjęcia produktów w sklepie i galeria na stronie produktu to standardowe pola
WooCommerce („Obraz produktu” i „Galeria zdjęć produktu”) — nie trzeba nic
dodatkowo konfigurować.

## 8. Formularz kontaktowy

Formularz na stronie Kontakt wysyła e-mail przez wbudowaną funkcję WordPressa
(`wp_mail`) na adres ustawiony w Dane kontaktowe PaperNest. Wiele hostingów
domyślnie dobrze obsługuje wysyłkę maili, ale jeśli wiadomości nie będą
docierać, zalecamy doinstalować darmową wtyczkę **WP Mail SMTP** i podłączyć
ją pod prawdziwą skrzynkę e-mail (np. Gmail/Office 365).

## 9. Bezpośrednie odnośniki (permalinki) i język

Zalecamy: **Ustawienia → Bezpośrednie odnośniki → Nazwa wpisu**, żeby adresy
stron wyglądały ładnie. W **Ustawienia → Ogólne → Język strony** ustaw
„Polski” — WordPress i WooCommerce same dociągną polskie tłumaczenia
interfejsu (przyciski typu „Add to cart” zamienią się na „Dodaj do koszyka”
itd.) i nie trzeba nic tłumaczyć ręcznie.

## 10. Uwaga dot. testów

Cały motyw i sklep zostały przetestowane na żywo na działającym WordPressie
z prawdziwym WooCommerce (nie na sucho): strona sklepu, karta produktu z
wariantami, dodawanie do koszyka, koszyk, formularz zamówienia z wyborem
metody płatności — wszystko sprawdzone i poprawione tam, gdzie źle
wyglądało (m.in. usunięty domyślny, niestylowany pasek boczny WooCommerce
oraz dostosowany wygląd nowego, blokowego koszyka i checkoutu WooCommerce do
kolorów marki). Jedynego ostatniego kroku — finalnego złożenia zamówienia —
nie udało się dokładnie zweryfikować w środowisku testowym z powodu
technicznego ograniczenia niezwiązanego z motywem (środowisko testowe nie
miało dostępu do prawdziwej bazy MySQL, a używana tam zamienna baza SQLite
ma udokumentowane ograniczenia zgodności z najnowszym WooCommerce). Na
prawdziwym hostingu z bazą MySQL — czyli praktycznie wszędzie — ten krok
korzysta z podstawowej, od lat sprawdzonej funkcji samego WooCommerce.
Mimo to prosimy o przetestowanie złożenia jednego prawdziwego zamówienia po
instalacji — jeśli coś będzie nie tak, szybko to poprawimy.
