# Mapa Drogowa Projektu Portfolio

Ten dokument śledzi postęp prac nad aplikacją oraz definiuje przyszłe zadania.

---

## ✅ Zrealizowane

- [x] Analiza projektu i użytych technologii.
- [x] Stworzenie podstawowej dokumentacji (`README.md`).
- [x] Inicjalizacja repozytorium Git i połączenie z GitHub.
- [x] Ustalenie workflow (feature branching + Pull Request).
- [x] Ustalenie zasady: push brancha tylko po lokalnym przetestowaniu działania aplikacji.
- [x] Stworzenie mapy drogowej projektu (`ROADMAP.md`).

---

## 🚀 Mapa Drogowa / Zadania do wykonania

### Faza 1: Wygląd i Doświadczenie Użytkownika (UI/UX)

- [ ] **Analiza i wybór stylu**: Znalezienie darmowego, estetycznego szablonu lub dopracowanie stylów Vuetify, aby aplikacja wyglądała nowocześnie i profesjonalnie.
- [ ] **Implementacja layoutu**: Zastosowanie wybranego stylu/szablonu w całej aplikacji (m.in. nawigacja, stopka, kolorystyka, typografia).
- [ ] **Stworzenie widoku "O Mnie"**: Zaprojektowanie i dodanie nowej podstrony z informacjami o autorze portfolio.
- [ ] **Poprawa responsywności (RWD)**: Upewnienie się, że aplikacja wygląda i działa doskonale na urządzeniach mobilnych.

### Faza 2: Rozbudowa Funkcjonalności

- [ ] **Formularz kontaktowy**: Dodanie formularza kontaktowego z walidacją pól po stronie frontendu.
- [ ] **Endpoint API dla formularza**: Stworzenie w backendzie endpointu, który będzie odbierał dane z formularza (na razie może je tylko logować lub zwracać statyczną odpowiedź).
- [ ] **Dynamiczne dane projektów**: Zastąpienie statycznie zakodowanej listy projektów w PHP (`InMemoryProjectRepository`) rozwiązaniem opartym na bazie danych (np. Azure Cosmos DB, MySQL lub plik JSON jako prosta baza danych).
- [ ] **Szczegóły projektu**: Możliwość kliknięcia w projekt na liście, aby przejść do osobnej podstrony z jego szczegółowym opisem i galerią.

### Faza 3: Testy i Wdrożenie

- [ ] **Testy jednostkowe**: Dodanie podstawowych testów jednostkowych dla logiki biznesowej w backendzie (PHPUnit).
- [ ] **Testy E2E**: Skonfigurowanie prostych testów End-to-End dla frontendu, które sprawdzą kluczowe ścieżki użytkownika (np. przy użyciu Cypress lub Playwright).
- [ ] **Konfiguracja CI/CD**: Ustawienie GitHub Actions do automatycznego budowania, testowania i wdrażania aplikacji na Azure Static Web Apps po każdym Pull Requescie do `main`.


