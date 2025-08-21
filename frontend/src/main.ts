// src/main.ts
import { createApp } from 'vue'
import App from './App.vue'
import { registerPlugins } from './plugins'
import './styles/main.css'

const app = createApp(App)

registerPlugins(app)  // Tu jest router rejestrowany przez plugins

app.mount('#app')

