import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Products from '../views/Products.vue'
import Contact from '../views/Rules.vue'
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
        path: '/rules',
        name: 'rules',
        component: Contact
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        path: '/myrent',
        name: 'sewa',
        component: Sewa,
        meta: { requiresAuth: true }
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

// Add navigation guard
router.beforeEach(async (to, from, next) => {
    // Check if route requires auth
    if (to.meta.requiresAuth) {
        // Call your auth check API endpoint
        const response = await fetch('/api/auth-status')
        const data = await response.json()
        
        if (!data.authenticated) {
            // Redirect to login if not authenticated
            next('/login')
            return
        }
    }
    next()
})

export default router