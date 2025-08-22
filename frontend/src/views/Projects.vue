<!-- src/views/Projects.vue -->
<template>
  <v-container>
    <h1 class="text-h4 my-4 fancy-title">Moje Projekty</h1>

    <!-- Wyświetla animację ładowania, gdy dane są pobierane -->
    <div v-if="loading" class="text-center pa-12">
      <v-progress-circular
        indeterminate
        color="primary"
        :size="70"
        :width="7"
      ></v-progress-circular>
      <p class="mt-4">Ładowanie projektów...</p>
    </div>

    <!-- Wyświetla komunikat o błędzie, jeśli wystąpił problem z API -->
    <v-alert
      v-else-if="error"
      type="error"
      border="start"
      variant="tonal"
      title="Błąd ładowania danych"
    >
      Nie udało się załadować projektów. Spróbuj ponownie później.
      <br>
      <small>Szczegóły: {{ error }}</small>
    </v-alert>

    <!-- Wyświetla siatkę projektów po pomyślnym załadowaniu -->
    <ProjectsGrid v-else :projects="projects" />

  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import ProjectsGrid from '@/components/ProjectsGrid.vue'; // Import komponentu siatki

// Definicja interfejsu dla projektu
interface Project {
  id: number;
  name: string;
  description: string;
  url: string;
  technologies: string[];
}

// Reaktwyne zmienne stanu
const projects = ref<Project[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

// Funkcja do pobierania danych o projektach z API
const fetchProjects = async () => {
  loading.value = true;
  error.value = null;

  try {
    // Adres URL do API backendu
    const response = await fetch('http://localhost:8000/api/projects');

    if (!response.ok) {
      throw new Error(`Błąd HTTP! Status: ${response.status}`);
    }

    const data = await response.json();
    projects.value = data;

  } catch (err) {
    console.error('Błąd podczas pobierania projektów:', err);
    error.value = err instanceof Error ? err.message : 'Wystąpił nieznany błąd';
  } finally {
    loading.value = false;
  }
};

// Pobierz dane po zamontowaniu komponentu
onMounted(() => {
  fetchProjects();
});
</script>

<style scoped>
.fancy-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  color: #3f51b5; /* Vuetify primary color */
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
  overflow: hidden; /* Hide the text initially */
  white-space: nowrap; /* Keep text on one line */
  animation: reveal-text 1.5s forwards; /* Apply animation */
}

@keyframes reveal-text {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}
</style>
