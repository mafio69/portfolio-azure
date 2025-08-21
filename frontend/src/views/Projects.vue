<!-- src/views/Projects.vue -->
<template>
  <div class="container">
    <h1>Moje Projekty</h1>

    <!-- Loading state -->
    <div v-if="loading" class="loading">
      <p>Ładowanie projektów...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="error">
      <p>Błąd: {{ error }}</p>
    </div>

    <!-- Projects list -->
    <div v-else class="projects-grid">
      <div
          v-for="project in projects"
          :key="project.id"
          class="project-card"
      >
        <h3>{{ project.title }}</h3>
        <p>{{ project.description }}</p>
        <div class="technologies">
          <span
              v-for="tech in project.technologies"
              :key="tech"
              class="tech-tag"
          >
            {{ tech }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Project {
  id: number
  title: string
  description: string
  technologies: string[]
}

const projects = ref<Project[]>([])
const loading = ref(false)
const error = ref<string | null>(null)

const fetchProjects = async () => {
  loading.value = true
  error.value = null

  try {
    // Połączenie z backendem PHP na porcie 8000
    const response = await fetch('http://localhost:8000/api/projects')

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    projects.value = data

  } catch (err) {
    console.error('Error fetching projects:', err)
    error.value = err instanceof Error ? err.message : 'Unknown error'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchProjects()
})
</script>

<style scoped>
.loading, .error {
  text-align: center;
  padding: 2rem;
}

.error {
  color: #e74c3c;
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.project-card {
  background: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 1.5rem;
}

.project-card h3 {
  margin-top: 0;
  color: #2c3e50;
}

.technologies {
  margin-top: 1rem;
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tech-tag {
  background: #007bff;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
}
</style>
