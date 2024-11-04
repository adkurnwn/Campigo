<template>
    <footer class="bg-white py-16 px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Column 1 -->
            <div>
                <router-link to="/" class="text-2xl font-bold text-gray-900 mb-6 block">
                    Campigo
                </router-link>
                <p class="text-gray-500">
                    Find your perfect camping experience with us
                </p>
            </div>
            <!-- Column 2 -->
            <div>
                <h4 class="font-semibold mb-6">About</h4>
                <ul class="text-gray-500 space-y-2">
                    <li><router-link to="/about">About Us</router-link></li>
                    <li><router-link to="/features">Features</router-link></li>
                    <li><router-link to="/news">News</router-link></li>
                </ul>
            </div>
            <!-- Column 3 -->
            <div>
                <h4 class="font-semibold mb-6">Company</h4>
                <ul class="text-gray-500 space-y-2">
                    <li><router-link to="/team">Our Team</router-link></li>
                    <li><router-link to="/partner">Partner With Us</router-link></li>
                    <li><router-link to="/faq">FAQ</router-link></li>
                </ul>
            </div>
            <!-- Column 4 -->
            <div>
                <h4 class="font-semibold mb-6">Contact</h4>
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