import type { App } from 'vue'
import router from '../router'  // SPRAWDŹ czy ta ścieżka jest poprawna!
import vuetify from './vuetify'

export function registerPlugins(app: App) {
    app
        .use(vuetify)
        .use(router)  // Tu się wywala błąd - router jest undefined
}
