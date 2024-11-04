<template>
    <div class="container mx-auto px-4">
        <Hero />
        
        <!-- Add your home page content here -->
    </div>
</template>

<script>
import Hero from '../components/Hero.vue';
export default {
    name: 'Home',
    components: {
        Hero,
    }
}
</script>