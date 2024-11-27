<template>
    <header
        class="py-4 px-6 flex justify-between items-center sticky top-0 z-50 transition-colors duration-300"
        :class="{'bg-teal': isAtTop, 'bg-white bg-opacity-25 backdrop-filter backdrop-blur-lg': !isAtTop}">
        <router-link to="/" class="flex items-center gap-2">
            <img src="/storage/app/public/img/campigo.png" alt="Campigo Logo" class="h-8 w-auto" />
            <div class="text-2xl font-bold text-teal-600">
                Campigo
            </div>
        </router-link>
        <div class="flex items-center lg:hidden">
            <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Links (hidden on small screens, shown on large screens) -->
        <ul id="nav-links" class="hidden lg:flex space-x-12 font-bold">
            <li>
                <router-link to="/" class="relative py-2 transition-all duration-300 ease-in-out hover:text-black"
                    :class="{
                        'bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent': $route.path === '/',
                        'text-gray-600': $route.path !== '/'
                    }">
                    <span>Home</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 transform origin-left transition-transform duration-300 ease-out"
                        :class="$route.path === '/' ? 'scale-x-100' : 'scale-x-0'"></span>
                </router-link>
            </li>
            <li>
                <router-link to="/products"
                    class="relative py-2 transition-all duration-300 ease-in-out hover:text-black" :class="{
                        'bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent': $route.path === '/products',
                        'text-gray-600': $route.path !== '/products'
                    }">
                    <span>Products</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 transform origin-left transition-transform duration-300 ease-out"
                        :class="$route.path === '/products' ? 'scale-x-100' : 'scale-x-0'"></span>
                </router-link>
            </li>
            <li>
                <router-link to="/rules"
                    class="relative py-2 transition-all duration-300 ease-in-out hover:text-black" :class="{
                        'bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent': $route.path === '/contact',
                        'text-gray-600': $route.path !== '/rules'
                    }">
                    <span>Rules</span>
                    <span
                        class="absolute bottom-0 left-0 w-full h-0.5 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 transform origin-left transition-transform duration-300 ease-out"
                        :class="$route.path === '/rules' ? 'scale-x-100' : 'scale-x-0'"></span>
                </router-link>
            </li>
        </ul>

        <!-- Desktop Profile/Book Now Button -->
        <div class="relative flex gap-2">
            <button @click="toggleCart"
            class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white p-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105 relative"
            title="Cart">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 8M17 13l1.6 8M9 21h6" />
            </svg>
            <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ cartCount }}</span>
            </button>

            <div v-if="isAuthenticated" class="flex gap-2">
            <router-link to="/myrent"
                class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white p-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105"
                title="My Rentals">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </router-link>

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
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div 
                v-show="isDropdownOpen"
                class="absolute right-0 mt-12 w-48 rounded-md shadow-lg py-1 z-50 bg-gradient-to-r from-teal-600/90 via-green-600/90 to-blue-600/90 backdrop-blur-sm origin-top-right"
                >
                <router-link 
                    to="/profile"
                    class="block px-4 py-2 text-sm text-white hover:bg-white/10 transition-colors flex justify-between items-center"
                >
                    Profile
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </router-link>
                <div class="border-t border-white/20 my-1"></div>
                <button 
                    @click="handleLogout"
                    class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-red-500/20 transition-colors flex justify-between items-center"
                >
                    Logout
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
                </div>
            </Transition>
            </div>
            <a v-else href="/login"
            class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Login
            </a>
        </div>

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
            Login
        </a>
    </div>

    <!-- Replace the ModalComponent with CartModal -->
    <CartModal ref="cartModalRef" />

    <!-- Logout Confirmation Modal -->
    <Transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showLogoutModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900">Konfirmasi Logout</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar dari akun?</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button @click="confirmLogout"
                                class="inline-flex w-full justify-center rounded-md bg-gradient-to-r from-red-600 to-red-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:from-red-700 hover:to-red-800 sm:ml-3 sm:w-auto">
                                Logout
                            </button>
                            <button @click="showLogoutModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script>
import CartModal from '../components/CartModal.vue';
import { ref } from 'vue';

export default {
    name: 'Header',
    components: {
        CartModal
    },
    data() {
        return {
            mobileMenuOpen: false,
            isAuthenticated: false,
            isDropdownOpen: false,
            showLogoutModal: false,
            cartCount: 0,
            isAtTop: true,
        }
    },
    setup() {
        const cartModalRef = ref(null);
        return {
            cartModalRef
        }
    },
    created() {
        this.checkAuthStatus();
        this.updateCartCount();
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
        handleLogout() {
            this.showLogoutModal = true;
            this.isDropdownOpen = false;
        },
        async confirmLogout() {
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
        },
        async updateCartCount() {
            try {
                const response = await fetch('/api/cart/count');
                const data = await response.json();
                this.cartCount = data.count;
            } catch (error) {
                console.error('Error fetching cart count:', error);
            }
        },
        toggleCart() {
            this.cartModalRef.openModal();
            this.updateCartCount();
        },
        handleScroll() {
            this.isAtTop = window.scrollY === 0;
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
        document.getElementById('mobile-menu-button').addEventListener('click', this.toggleMobileMenu);
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
        window.removeEventListener('scroll', this.handleScroll);
    }
}
</script>