<template>
    <div class="container mx-auto px-4 flex flex-col items-center justify-center min-h-screen">
        <h1 class="text-4xl font-bold mb-4">404 - Page Not Found</h1>
        <p class="text-gray-600 mb-4">Halaman ini tidak tersedia atau sedang dalam perbaikan.</p>
        <router-link to="/" class="text-teal-600 hover:text-teal-900">Kembali ke Home</router-link>
    </div>
</template>

<script>
export default {
    name: 'NotFound',
    components: {
        
    }
}
</script>