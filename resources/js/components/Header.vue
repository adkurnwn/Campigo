<template>
    <header class="bg-white py-4 px-6 flex justify-between items-center">
        <div class="flex items-center">
            <router-link to="/" class="text-2xl font-bold text-gray-900">Campigo</router-link>
        </div>
        <div class="flex items-center lg:hidden">
            <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Links (hidden on small screens, shown on large screens) -->
        <ul id="nav-links" class="hidden lg:flex space-x-6 text-gray-700 font-semibold">
            <li><router-link to="/" class="hover:text-black">Home</router-link></li>
            <li><router-link to="/products" class="hover:text-black">Products</router-link></li>
            <li><router-link to="/contact" class="hover:text-black">Contact</router-link></li>
        </ul>

        <!-- Book Now Button -->
        <a href="/register"
            class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Book Now
        </a>
    </header>

    <!-- Mobile Menu (Initially hidden) -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white py-4 px-10 text-gray-700 space-y-4">
        <router-link to="/" class="block hover:text-black">Home</router-link>
        <router-link to="/products" class="block hover:text-black">Products</router-link>
        <router-link to="/contact" class="block hover:text-black">Contact</router-link>
        <a href="/register"
            class="block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Book Now
        </a>
    </div>
</template>

<script>
export default {
    name: 'Header',
    data() {
        return {
            mobileMenuOpen: false
        }
    },
    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        }
    },
    mounted() {
        document.getElementById('mobile-menu-button').addEventListener('click', this.toggleMobileMenu);
    }
}
</script>