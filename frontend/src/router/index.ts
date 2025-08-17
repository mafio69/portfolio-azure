import { createRouter, createWebHistory } from 'vue-router'
import ProjectsGrid from '../components/ProjectsGrid.vue'
import Contact from '../components/Contact.vue'

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: ProjectsGrid },
        { path: '/contact', component: Contact }
    ]
})
