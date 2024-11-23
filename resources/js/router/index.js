import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Products from '../views/Products.vue'
import Contact from '../views/Contact.vue'
import Profile from '../views/Profile.vue'
import Sewa from '../views/Sewa.vue'
import NotFound from '../views/NotFound.vue'


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
    {
        path: '/profile',
        name: 'profile',
        component: Profile
    },
    {
        path: '/myrent',
        name: 'sewa',
        component: Sewa
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router