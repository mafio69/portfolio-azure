import { createRouter, createWebHistory } from 'vue-router'
import ProjectsGrid from '../components/ProjectsGrid.vue'

export default createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', component: ProjectsGrid }
    ]
})
