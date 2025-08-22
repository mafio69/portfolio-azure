<script setup lang="ts">
import { ref, onMounted } from 'vue';

const showDebug = ref(false);
const renderCount = ref(0);

interface Project {
  id: number;
  name: string;
  description: string;
  technologies: string[];
  url: string;
  debug?: {
    renderCount: number;
    lastUpdate: string;
  };
}

// Pobierz dane z globalnej zmiennej
const projectsData = window.__vue_devtool_undefined__ || [];

const projects = ref<Project[]>(projectsData.map(project => ({
  ...project,
  debug: {
    renderCount: 0,
    lastUpdate: new Date().toISOString()
  }
})));

const updateDebugInfo = () => {
  renderCount.value++;
  projects.value = projects.value.map(project => ({
    ...project,
    debug: {
      renderCount: (project.debug?.renderCount || 0) + 1,
      lastUpdate: new Date().toISOString()
    }
  }));
};

const toggleDebug = () => {
  showDebug.value = !showDebug.value;
  updateDebugInfo();
};

onMounted(() => {
  updateDebugInfo();
  console.log('Mounted with projects:', projects.value);
});
</script>

<template>
  <v-container class="py-8">
    <div v-if="showDebug" class="debug-panel mb-4">
      <div class="mb-2">Renderowania: {{ renderCount }}</div>
      <pre class="debug-data">{{ JSON.stringify(projects, null, 2) }}</pre>
      <v-btn @click="toggleDebug" color="error" class="mt-2">
        Ukryj Debug ({{ renderCount }})
      </v-btn>
    </div>
    <div v-else>
      <v-btn @click="toggleDebug" color="info" class="mb-4">
        Pokaż Debug ({{ renderCount }})
      </v-btn>
    </div>

    <div class="projects-grid">
      <v-card
          v-for="project in projects"
          :key="project.id"
          class="project-card"
      >
        <v-card-title>{{ project.name }}</v-card-title>
        <v-card-text>
          <div v-if="showDebug" class="debug-info mb-2">
            <div>ID: {{ project.id }}</div>
            <div>Renderowania: {{ project.debug?.renderCount }}</div>
            <div>Ostatnia aktualizacja: {{ project.debug?.lastUpdate }}</div>
          </div>
          <p>{{ project.description }}</p>
          <div class="technologies">
            <v-chip
                v-for="tech in project.technologies"
                :key="tech"
                class="ma-1"
                color="primary"
                variant="outlined"
                size="small"
            >
              {{ tech }}
            </v-chip>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn
              v-if="project.url"
              color="primary"
              :href="project.url"
              target="_blank"
              variant="tonal"
              prepend-icon="mdi-github"
          >
            Zobacz na GitHub
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>
  </v-container>
</template>

<style scoped>
.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
  padding: 16px;
}

.debug-panel {
  padding: 16px;
  background-color: var(--v-surface-variant);
  border-radius: 4px;
}

.debug-data {
  max-height: 300px;
  overflow: auto;
  background: var(--v-surface-variant);
  padding: 8px;
  border-radius: 4px;
  font-family: monospace;
  font-size: 12px;
}

.debug-info {
  font-size: 12px;
  color: var(--v-text-disabled);
  background: var(--v-surface-variant);
  padding: 8px;
  border-radius: 4px;
}
</style>