<template>
  <v-container class="py-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
      <h1 class="text-h3 font-weight-bold text-primary mb-4">
        Moje Projekty
      </h1>
      <p class="text-h6 text-secondary max-w-2xl mx-auto">
        Oto wybrane projekty, nad którymi pracowałem. Każdy z nich przedstawia różne aspekty moich umiejętności technicznych.
      </p>
    </div>

    <!-- Projects Grid -->
    <v-row v-if="projects.length > 0">
      <v-col 
        v-for="p in projects" 
        :key="p.id" 
        cols="12" 
        md="6" 
        lg="4"
      >
        <v-card
          elevation="4"
          hover
          class="h-100 transition-all"
          @click="viewProject(p)"
        >
          <v-img
            :src="p.image || '/api/placeholder/400/200'"
            height="200"
            cover
            class="white--text align-end"
            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
          >
            <v-card-title class="text-white">{{ p.name }}</v-card-title>
          </v-img>

          <v-card-text class="pb-2">
            <p class="text-body-1 mb-3">{{ p.description }}</p>
            
            <v-chip-group v-if="p.technologies">
              <v-chip
                v-for="tech in p.technologies"
                :key="tech"
                small
                color="primary"
                variant="outlined"
              >
                {{ tech }}
              </v-chip>
            </v-chip-group>
          </v-card-text>

          <v-card-actions>
            <v-btn
              color="primary"
              variant="text"
              @click.stop="viewProject(p)"
            >
              <v-icon left>mdi-eye</v-icon>
              Zobacz szczegóły
            </v-btn>
            
            <v-spacer></v-spacer>
            
            <v-btn
              v-if="p.url"
              :href="p.url"
              target="_blank"
              color="secondary"
              variant="outlined"
              size="small"
              @click.stop
            >
              <v-icon left>mdi-open-in-new</v-icon>
              Demo
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <!-- Loading State -->
    <div v-else-if="loading" class="text-center py-12">
      <v-progress-circular
        indeterminate
        color="primary"
        size="64"
      ></v-progress-circular>
      <p class="text-h6 mt-4">Ładowanie projektów...</p>
    </div>

    <!-- Error State -->
    <v-alert
      v-if="error"
      type="error"
      variant="tonal"
      class="mt-4"
    >
      <v-alert-title>Wystąpił błąd</v-alert-title>
      {{ error }}
    </v-alert>

    <!-- Empty State -->
    <div v-else-if="projects.length === 0 && !loading" class="text-center py-12">
      <v-icon size="64" color="secondary" class="mb-4">mdi-folder-open</v-icon>
      <p class="text-h6">Brak projektów do wyświetlenia</p>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const projects = ref([])
const error = ref(null)
const loading = ref(true)

const viewProject = (project: any) => {
  console.log('Viewing project:', project)
  // TODO: Navigate to project details page
  // router.push(`/project/${project.id}`)
}

onMounted(async () => {
  try {
    loading.value = true
    const res = await fetch('/api/projects')
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`)
    }
    
    projects.value = await res.json()
  } catch (e: any) {
    error.value = e.message || 'Nie udało się załadować projektów'
    console.error('Error loading projects:', e)
  } finally {
    loading.value = false
  }
})
</script>
