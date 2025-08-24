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
import { ref, onMounted, onBeforeUnmount } from 'vue';
import ProjectsGrid from '@/components/ProjectsGrid.vue';

interface Project {
  id: number;
  name: string;
  description: string;
  url: string;
  technologies: string[];
}

const projects = ref<Project[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

// Baza URL do API z env; w DEV wskazuje na http://localhost:8080/api, w PROD na App Service /api
const API_BASE = import.meta.env.VITE_API_URL?.replace(/\/+$/, '') || 'http://localhost:8080/api';

const controller = new AbortController();

const fetchProjects = async () => {
  loading.value = true;
  error.value = null;

  try {
    // timeout 10s
    const timeoutId = setTimeout(() => controller.abort(), 10000);

    const response = await fetch(`${API_BASE}/projects`, {
      method: 'GET',
      signal: controller.signal,
      headers: {
        'Accept': 'application/json'
      }
    });

    clearTimeout(timeoutId);

    if (!response.ok) {
      const text = await response.text().catch(() => '');
      // spróbuj wyciągnąć komunikat z JSON, jeśli to JSON
      let details = '';
      try {
        const maybe = JSON.parse(text);
        details = typeof maybe?.message === 'string' ? ` ${maybe.message}` : '';
      } catch { /* ignore */ }

      throw new Error(`HTTP ${response.status}${details}`);
    }

    const data = await response.json();
    projects.value = Array.isArray(data) ? data : [];
  } catch (err: unknown) {
    if ((err as any)?.name === 'AbortError') {
      error.value = 'Przerwano żądanie (timeout).';
    } else {
      console.error('Błąd podczas pobierania projektów:', err);
      error.value = err instanceof Error ? err.message : 'Wystąpił nieznany błąd';
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchProjects();
});

onBeforeUnmount(() => {
  controller.abort();
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
