<template>
    <div class="container mx-auto px-4">
        <Hero />
        <PopularProducts />
        
        <!-- Add your home page content here -->
    </div>
</template>

<script>
import Hero from '../components/Hero.vue';
import PopularProducts from '../components/PopularProducts.vue';
export default {
    name: 'Home',
    components: {
        Hero,
        PopularProducts,
    }
}
</script>