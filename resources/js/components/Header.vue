<template>
    <header
        class="bg-white bg-opacity-25 py-4 px-6 flex justify-between items-center sticky top-0 z-50 backdrop-filter backdrop-blur-lg">
        <div class="flex items-center">
            <router-link to="/" class="text-2xl font-bold text-teal-600">Campigo</router-link>
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

        <!-- Desktop Profile/Book Now Button -->
        <div class="relative" v-if="isAuthenticated">
            <button @click="toggleDropdown"
                class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white p-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105"
                title="Profile">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>

            <!-- Desktop Dropdown Menu -->
            <div v-show="isDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profile
                </router-link>
                <button @click="handleLogout"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                </button>
            </div>
        </div>
        <a v-else href="/register"
            class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Book Now
        </a>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden" aria-label="Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </header>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="lg:hidden hidden bg-white py-4 px-10 text-gray-700 space-y-4">
        <router-link to="/" class="block hover:text-black">Home</router-link>
        <router-link to="/products" class="block hover:text-black">Products</router-link>
        <router-link to="/contact" class="block hover:text-black">Contact</router-link>

        <!-- Mobile Profile/Book Now Button -->
        <div v-if="isAuthenticated" class="space-y-2">
            <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Profile
            </router-link>
            <button @click="handleLogout"
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Logout
            </button>
        </div>
        <a v-else href="/register"
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
            mobileMenuOpen: false,
            isAuthenticated: false,
            isDropdownOpen: false,
        }
    },
    created() {
        this.checkAuthStatus();
    },
    methods: {
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        async checkAuthStatus() {
            try {
                const response = await fetch('/api/auth-status');
                const data = await response.json();
                this.isAuthenticated = data.authenticated;
            } catch (error) {
                console.error('Error checking authentication status:', error);
            }
        },
        async handleLogout() {
            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });
                window.location.href = '/';
            } catch (error) {
                console.error('Logout failed:', error);
            }
        },
        handleClickOutside(event) {
            if (!event.target.closest('.relative')) {
                this.isDropdownOpen = false;
            }
        }
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
        document.getElementById('mobile-menu-button').addEventListener('click', this.toggleMobileMenu);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    }
}
</script>