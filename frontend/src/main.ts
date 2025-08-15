import { createApp } from 'vue'
import App from './App.vue'
import './styles/main.css'

// Direct imports for Vuetify and Vue Router
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles' // Import styles directly here

import { createRouter, createWebHistory } from 'vue-router'
import ProjectsGrid from './components/ProjectsGrid.vue' // Assuming this path is correct from main.ts perspective

// Create Vuetify instance
const vuetify = createVuetify({
  components,
  directives,
})

// Create Vue Router instance
const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: ProjectsGrid }
  ]
})

const app = createApp(App)

app.use(vuetify) // Use Vuetify
app.use(router)  // Use Vue Router

app.mount('#app')
