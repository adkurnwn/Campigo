import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Products from '../views/Products.vue'
import Contact from '../views/Contact.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/products',
        name: 'products',
        component: Products
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact
    },
    // Comment out or remove the register route
    // {
    //     path: '/register',
    //     name: 'register',
    //     component: Register
    // },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router