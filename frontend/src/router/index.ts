// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Projects from '@/views/Projects.vue'
import About from '@/views/About.vue'
import Contact from '@/views/Contact.vue'
import Home from '@/views/Home.vue'

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/projects', name: 'Projects', component: Projects },
    { path: '/about', name: 'About', component: About },
    { path: '/contact', name: 'Contact', component: Contact }
]

// WAŻNE: Musi być export default
export default createRouter({
    history: createWebHistory(),
    routes
})

