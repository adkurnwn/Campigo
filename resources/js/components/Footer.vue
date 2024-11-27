<template>
    <footer class="bg-gradient-to-b from-white to-teal-100 pb-8">
        <div class="w-full h-4 bg-teal-600 mb-8"></div>
        <div class="px-2">
            <div class="flex grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto justify-around">
                <!-- Column 1 -->
                <div class="flex-col space-y-4">
                    <div>
                        <router-link to="/" class="flex items-center gap-2">
                            <img src="/storage/app/public/img/campigo.png" alt="Campigo Logo" class="h-8 w-auto" />
                            <div class="text-2xl font-bold text-teal-600">
                                Campigo
                            </div>
                        </router-link>
                    </div>
                    <div>
                        <p class="text-gray-500">
                            Find your perfect camping experience with us
                        </p>
                    </div>
                </div>
                <!-- Column 2 -->
                <div>
                    <h4 class="font-semibold mb-6 text-teal-600">About</h4>
                    <ul class="text-gray-500 space-y-2">
                        <li><router-link to="/about">About Us</router-link></li>
                        <li><router-link to="/features">Features</router-link></li>
                        <li><router-link to="/news">News</router-link></li>
                    </ul>
                </div>
                <!-- Column 3 -->
                <div>
                    <h4 class="font-semibold mb-6 text-teal-600">Company</h4>
                    <ul class="text-gray-500 space-y-2">
                        <li><router-link to="/team">Our Team</router-link></li>
                        <li><router-link to="/partner">Partner With Us</router-link></li>
                        <li><router-link to="/faq">FAQ</router-link></li>
                    </ul>
                </div>
                <!-- Column 4 -->
                <div>
                    <h4 class="font-semibold mb-6 text-teal-600">Contact</h4>
                    <ul class="text-gray-500 space-y-2">
                        <li><router-link to="/getting-started">Getting Started</router-link></li>
                        <li><router-link to="/pricing">Pricing</router-link></li>
                        <li><router-link to="/resources">Resources</router-link></li>
                    </ul>
                </div>
            </div>
            <div class="text-center text-gray-500 text-sm mt-16">
                {{ currentYear }} all Right Reserved | Term of use campigo
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