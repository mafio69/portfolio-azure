<script setup lang="ts">
import type { PropType } from 'vue';

// Definicja interfejsu dla pojedynczego projektu
interface Project {
  id: number;
  name: string;
  description: string;
  technologies: string[];
  url: string;
}

// Definicja propsów komponentu
// Komponent akceptuje tablicę projektów
defineProps({
  projects: {
    type: Array as PropType<Project[]>,
    required: true,
  },
});
</script>

<template>
  <div class="projects-grid">
    <!-- Iteracja po projektach, dodano `index` do śledzenia kolejności -->
    <v-card
        v-for="(project, index) in projects"
        :key="project.id"
        class="project-card"
        elevation="2"
        hover
        :style="{ 'animation-delay': index * 100 + 'ms' }"
    >
      <v-card-title class="headline">{{ project.name }}</v-card-title>
      <v-card-text>
        <p class="body-1">{{ project.description }}</p>
        <div class="technologies mt-4">
          <!-- Etykiety dla technologii -->
          <v-chip
              v-for="tech in project.technologies"
              :key="tech"
              class="ma-1 tech-chip"
              color="primary"
              variant="outlined"
              size="small"
          >
            {{ tech }}
          </v-chip>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <!-- Przycisk prowadzący do repozytorium projektu -->
        <v-btn
            v-if="project.url"
            color="primary"
            :href="project.url"
            target="_blank"
            variant="tonal"
            prepend-icon="mdi-github"
            class="github-btn"
        >
          Zobacz na GitHub
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<style scoped>
/* Definicja animacji wejścia */
@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}

.project-card {
  display: flex;
  flex-direction: column;
  height: 100%;
  
  /* Zastosowanie animacji */
  opacity: 0; /* Start from invisible */
  animation: fade-in-up 0.5s ease-out forwards;
  
  /* Płynne przejście dla efektu hover */
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Efekt powiększenia przy najechaniu myszką */
.project-card:hover {
  transform: scale(1.03);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.project-card .v-card-text {
  flex-grow: 1;
}

/* Dodatkowe, subtelne efekty dla chipów i przycisku */
.tech-chip, .github-btn {
  transition: transform 0.2s ease;
}

.tech-chip:hover, .github-btn:hover {
  transform: translateY(-2px);
}
</style>