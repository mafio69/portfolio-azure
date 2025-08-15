<template>
  <v-container>
    <h2 class="my-4">Projekty</h2>
    <v-row>
      <v-col v-for="p in projects" :key="p.title" cols="12" md="6" lg="4">
        <v-card>
          <v-card-title>{{ p.title }}</v-card-title>
          <v-card-text>{{ p.desc }}</v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <div v-if="error" class="text-error">{{ error }}</div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
const projects = ref([])
const error = ref(null)

onMounted(async () => {
  try {
    const res = await fetch('/api/projects')
    projects.value = await res.json()
  } catch (e: any) {
    error.value = e.message
  }
})
</script>
