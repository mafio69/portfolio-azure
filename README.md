# Portfolio Azure App

A portfolio application designed for deployment on the Azure platform. It consists of a backend API written in PHP and a frontend application in Vue.js.

## Architecture

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

The frontend is a Single Page Application (SPA) built with Vue.js.

-   **Technologies**: Vue.js, Vite, Vuetify, Vue Router
-   **Structure**: The application code is located in the `frontend/src/` directory.

### Key Components

-   `App.vue`: The main application component.
-   `ProjectsGrid.vue`: The component responsible for fetching and displaying the grid of projects from the API.

### Local Setup

1.  Navigate to the frontend directory:
    ```bash
    cd frontend
    ```
2.  Install the dependencies (if not already installed):
    ```bash
    npm install
    ```
3.  Start the Vite development server:
    ```bash
    npm run dev
    ```
    The application will be available at the address provided by Vite (usually `http://localhost:5173`).

### Building the Application

To create a static production build of the application, run the following command:

```bash
npm run build
```

The resulting files will be placed in the 'frontend/dist' directory.

---

## Deployment (Deployment to Azure)

### Deploy a containerized application to Azure
This application is prepared to be deployed as a containerized application. Deployment is done by uploading the appropriate Docker image to Azure Container Registry (ACR) or another container registry.

---

## Troubleshooting

### API connection error (e.g. network error in browser console)

When the frontend can't connect to the backend, the most common cause is an unrunning Docker container.

**Solution:**

1. Check if the Docker containers are running using the 'docker ps' command. You should see a container for this project.
2. If the container is not running, go to the root directory of the project and run it using 'docker-compose up -d'.
3. Check the container logs for errors: 'docker-compose logs app'.
