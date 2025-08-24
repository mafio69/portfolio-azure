// src/main.ts
import { createApp } from 'vue'
import App from './App.vue'
import { registerPlugins } from './plugins'
import './styles/main.css'

const app = createApp(App)

// Rozszerzona obsługa wyjątków dla pluginów
try {
    registerPlugins(app)
} catch (err) {
    // Przechwyć: mogło się nie zainstalować np. Vuetify/router
    // Możesz dodać toast/error UI lub logowanie do konsoli
    console.error('❌ Błąd inicjalizacji pluginów:', err)
    // Opcjonalnie: przekieruj na stronę błędu lub wyświetl alert
}

app.mount('#app')


