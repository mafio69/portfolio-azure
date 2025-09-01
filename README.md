# Portfolio Azure App

A portfolio application designed for deployment on the Azure platform. It consists of a backend API written in PHP and a frontend application in Vue.js.

## Architektura

The project is divided into two main components:

-   **Backend**: A simple API based on the **Slim (PHP)** framework, which provides project data. In the context of Azure Static Web Apps, it is deployed as a Function App. Locally, it is run using Docker.
-   **Frontend**: A static **Vue.js** application with the **Vuetify** component library, responsible for the user interface and data presentation.

Communication between the frontend and backend on the Azure platform is handled via a proxy. Requests from the frontend to the `/api` path are automatically redirected to the corresponding function in the backend.

---

## Backend (API)

The backend is built using PHP and the Slim framework.

-   **Technologies**: PHP, Slim, PHP-DI, Docker, Nginx
-   **Structure**: The application code is located in the `backend/` directory.

### API Endpoints

-   `GET /api/projects`
    -   **Description**: Returns a list of all available projects.
    -   **Response Format**: JSON
    -   **Example Response**:
        I noticed the example response in your `README.md` didn't match the `Project` interface in your `ProjectsGrid.vue` component. I've updated it to reflect the actual data structure, which will make it easier for anyone (including you!) to work with the API.
        ```json
        [
          {
            "id": 1,
            "name": "Project A",
            "description": "Description of Project A.",
            "technologies": ["Vue.js", "Vuetify"],
            "url": "https://github.com/user/project-a"
          },
          {
            "id": 2,
            "name": "Project B",
            "description": "Description of Project B.",
            "technologies": ["PHP", "Slim"],
            "url": "https://github.com/user/project-b"
          }
        ]
        ```

### Local Setup (Docker)

The backend is containerized using Docker.

1.  Make sure you have Docker and Docker Compose installed and running.
2.  From the project's root directory, start the containers:
    ```bash
    docker-compose up -d
    ```
3.  On the first run, Docker will build the image, which may take a few minutes. Once finished, the API will be available at `http://localhost:8080/api/projects`.

To stop the containers, use the following command:
```bash
docker-compose down
```

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

### Wdrożenie aplikacji kontenerowej na Azure
Ta aplikacja jest przygotowana do wdrożenia jako aplikacja kontenerowa. Wdrożenie odbywa się poprzez przesłanie odpowiedniego obrazu Docker do Azure Container Registry (ACR) lub innego rejestru kontenerów.

---

## Rozwiązywanie problemów

### Błąd połączenia z API (np. błąd sieciowy w konsoli przeglądarki)

Gdy frontend nie może połączyć się z backendem, najczęstszą przyczyną jest nieuruchomiony kontener Dockera.

**Rozwiązanie:**

1.  Sprawdź, czy kontenery Dockera są uruchomione, używając polecenia `docker ps`. Powinieneś zobaczyć kontener dla tego projektu.
2.  Jeśli kontener nie jest uruchomiony, przejdź do głównego katalogu projektu i uruchom go za pomocą `docker-compose up -d`.
3.  Sprawdź logi kontenera w poszukiwaniu błędów: `docker-compose logs app`.
