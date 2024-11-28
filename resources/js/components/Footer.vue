<template>
    <footer class="bg-gradient-to-b from-white to-teal-100 pb-8">
        <div class="w-full h-4 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 mb-8"></div>
        <div class="px-4 md:px-6">
            <!-- Grid container with mobile-first layout -->
            <div class="max-w-7xl mx-auto">
                <!-- Main content grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Brand Column -->
                    <div class="space-y-4">
                        <router-link to="/" class="flex items-center gap-2">
                            <img src="/storage/app/public/img/campigo.png" alt="Campigo Logo" class="h-8 w-auto" />
                            <div class="text-2xl font-bold bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent">
                                Campigo
                            </div>
                        </router-link>
                        <p class="text-gray-500">
                            Find your perfect camping experience with us
                        </p>
                    </div>

                    <!-- Menu Columns -->
                    <div class="space-y-6 md:space-y-0 md:grid md:grid-cols-3 md:col-span-3 md:gap-8">
                        <!-- About Column -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-lg bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent">About</h4>
                            <ul class="space-y-2">
                                <li><router-link to="/about" class="text-gray-500 hover:text-teal-600 transition-colors">About Us</router-link></li>
                                <li><router-link to="/features" class="text-gray-500 hover:text-teal-600 transition-colors">Features</router-link></li>
                                <li><router-link to="/news" class="text-gray-500 hover:text-teal-600 transition-colors">News</router-link></li>
                            </ul>
                        </div>

                        <!-- Company Column -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-lg bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent">Company</h4>
                            <ul class="space-y-2">
                                <li><router-link to="/team" class="text-gray-500 hover:text-teal-600 transition-colors">Our Team</router-link></li>
                                <li><router-link to="/partner" class="text-gray-500 hover:text-teal-600 transition-colors">Partner With Us</router-link></li>
                                <li><router-link to="/faq" class="text-gray-500 hover:text-teal-600 transition-colors">FAQ</router-link></li>
                            </ul>
                        </div>

                        <!-- Contact Column -->
                        <div class="space-y-4">
                            <h4 class="font-semibold text-lg bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 bg-clip-text text-transparent">Contact</h4>
                            <ul class="space-y-2">
                                <li><router-link to="/getting-started" class="text-gray-500 hover:text-teal-600 transition-colors">Getting Started</router-link></li>
                                <li><router-link to="/pricing" class="text-gray-500 hover:text-teal-600 transition-colors">Pricing</router-link></li>
                                <li><router-link to="/resources" class="text-gray-500 hover:text-teal-600 transition-colors">Resources</router-link></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Copyright notice -->
                <div class="text-center text-gray-500 text-sm mt-16 pt-8 border-t border-gray-200">
                    {{ currentYear }} all Right Reserved | Term of use campigo
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'Footer',
    computed: {
        currentYear() {
            return new Date().getFullYear()
        }
    }
}
</script>