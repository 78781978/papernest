# Motyw WordPress „PaperNest” — instrukcja instalacji

Ten katalog zawiera gotowy motyw WordPress (`papernest/`) zbudowany na
podstawie zaakceptowanego prototypu — ten sam wygląd, ale wszystko można już
edytować z panelu wp-admin, a sklep działa na WooCommerce.

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

## 4. Treść startowa (strony, przykładowe produkty, opinie, portfolio)

Motyw sam wypełnia się treścią startową przy pierwszej aktywacji. Jeśli
WooCommerce zostało zainstalowane **po** aktywacji motywu (co jest normalną
kolejnością — patrz punkty 2 i 3 powyżej), dokończ import ręcznie:

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
- 12 kafelków portfolio („zastosowania”).

## 5. Co można edytować i gdzie

| Co | Gdzie w wp-admin |
|---|---|
| Logo | Wygląd → Dostosuj → Identyfikacja strony |
| Telefon, e-mail, adres, godziny, link do Facebooka | Wygląd → Dostosuj → Dane kontaktowe PaperNest |
| Zdjęcia (magazyn, sklep, biuro, karty „O nas”, logotypy płatności/kurierów) | Wygląd → Dostosuj → Zdjęcia na stronie |
| Produkty, ceny wariantów, zdjęcia produktów, opisy | Produkty |
| Opinie klientów | Opinie klientów (menu boczne) |
| Kafelki portfolio + zdjęcia | Portfolio / Zastosowania (menu boczne) |
| Treść stron (O nas, Regulamin, Polityka prywatności, itd.) | Strony → edytuj dowolną |
| Menu | Wygląd → Menu |

Zdjęcia produktów w sklepie i galeria na stronie produktu to standardowe pola
WooCommerce („Obraz produktu” i „Galeria zdjęć produktu”) — nie trzeba nic
dodatkowo konfigurować.

## 6. Formularz kontaktowy

Formularz na stronie Kontakt wysyła e-mail przez wbudowaną funkcję WordPressa
(`wp_mail`) na adres ustawiony w Dane kontaktowe PaperNest. Wiele hostingów
domyślnie dobrze obsługuje wysyłkę maili, ale jeśli wiadomości nie będą
docierać, zalecamy doinstalować darmową wtyczkę **WP Mail SMTP** i podłączyć
ją pod prawdziwą skrzynkę e-mail (np. Gmail/Office 365) — to najczęstsza
przyczyna „znikających” maili z formularzy na WordPressie.

## 7. Bezpośrednie odnośniki (permalinki)

Zalecamy: **Ustawienia → Bezpośrednie odnośniki → Nazwa wpisu**, żeby adresy
stron wyglądały ładnie (np. `papernest.pl/o-nas/`).

## 8. Uwaga dot. testów

Motyw został przetestowany lokalnie na WordPressie (strony, menu, opinie,
portfolio, formularze, RWD na telefonie) i wygląda identycznie jak
zaakceptowany prototyp. Integracja z WooCommerce (sklep, koszyk, płatność,
warianty produktów) została napisana zgodnie ze standardowym, udokumentowanym
sposobem integrowania motywów z WooCommerce, ale ze względu na ograniczenia
środowiska, w którym motyw powstawał, nie dało się samego WooCommerce
zainstalować i przeklikać na miejscu. Prosimy o rzucenie okiem na stronę
sklepu, produktu, koszyka i zamówienia po instalacji — jeśli coś będzie nie
tak, szybko to poprawimy.
