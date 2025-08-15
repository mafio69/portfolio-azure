# Portfolio Azure App

Aplikacja portfolio zaprojektowana do wdrożenia na platformie Azure Static Web Apps. Składa się z backendowego API napisanego w PHP oraz frontendowej aplikacji w Vue.js.

## Architektura

Projekt jest podzielony na dwa główne komponenty:

-   **Backend**: Proste API oparte na frameworku **Slim (PHP)**, które dostarcza dane projektów. W kontekście Azure Static Web Apps, jest on wdrażany jako Aplikacja Funkcji (Function App).
-   **Frontend**: Statyczna aplikacja **Vue.js** z biblioteką komponentów **Vuetify**, odpowiedzialna za interfejs użytkownika i prezentację danych.

Komunikacja między frontendem a backendem na platformie Azure odbywa się poprzez proxy. Zapytania z frontendu do ścieżki `/api` są automatycznie przekierowywane do odpowiedniej funkcji w backendzie.

---

## Backend (API)

Backend został zbudowany przy użyciu PHP i frameworka Slim.

-   **Technologie**: PHP, Slim, PHP-DI
-   **Struktura**: Kod aplikacji znajduje się w katalogu `backend/src/`.

### Endpointy API

-   `GET /api/projects`
    -   **Opis**: Zwraca listę wszystkich dostępnych projektów.
    -   **Format odpowiedzi**: JSON
    -   **Przykładowa odpowiedź**:
        ```json
        [
          {
            "title": "Projekt A",
            "desc": "Opis projektu A"
          },
          {
            "title": "Projekt B",
            "desc": "Opis projektu B"
          }
        ]
        ```

### Uruchomienie lokalne

1.  Przejdź do katalogu backendu:
    ```bash
    cd backend
    ```
2.  Zainstaluj zależności (jeśli nie były instalowane):
    ```bash
    composer install
    ```
3.  Uruchom wbudowany serwer PHP:
    ```bash
    php -S localhost:8000 -t public
    ```
    API będzie dostępne pod adresem `http://localhost:8000`.

---

## Frontend

Frontend to aplikacja typu Single Page Application (SPA) zbudowana w oparciu o Vue.js.

-   **Technologie**: Vue.js, Vite, Vuetify, Vue Router
-   **Struktura**: Kod aplikacji znajduje się w katalogu `frontend/src/`.

### Kluczowe komponenty

-   `App.vue`: Główny komponent aplikacji.
-   `ProjectsGrid.vue`: Komponent odpowiedzialny za pobieranie i wyświetlanie siatki projektów z API.

### Uruchomienie lokalne

1.  Przejdź do katalogu frontendu:
    ```bash
    cd frontend
    ```
2.  Zainstaluj zależności (jeśli nie były instalowane):
    ```bash
    npm install
    ```
3.  Uruchom serwer deweloperski Vite:
    ```bash
    npm run dev
    ```
    Aplikacja będzie dostępna pod adresem wskazanym przez Vite (zazwyczaj `http://localhost:5173`).

### Budowanie aplikacji

Aby stworzyć statyczną wersję produkcyjną aplikacji, wykonaj polecenie:

```bash
npm run build
```
Pliki wynikowe zostaną umieszczone w katalogu `frontend/dist`.

---

## Deployment (Wdrożenie na Azure)

Aplikacja jest przystosowana do wdrożenia jako **Azure Static Web App**. Proces ten zazwyczaj polega na połączeniu repozytorium Git (np. z GitHub) z usługą Azure.

1.  **Utwórz zasób** Azure Static Web App w portalu Azure.
2.  **Połącz repozytorium**: Wskaż swoje repozytorium Git.
3.  **Konfiguracja budowania**:
    -   **App location**: `/frontend`
    -   **Api location**: `/backend`
    -   **Output location**: `dist` (wewnątrz katalogu frontendu)

Po skonfigurowaniu, Azure automatycznie zbuduje i wdroży aplikację po każdym `push` do głównego brancha repozytorium. Plik `staticwebapp.config.json` może być użyty do dalszej konfiguracji routingu i zabezpieczeń.
