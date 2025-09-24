<template>
    <div class="flex-col">
       
        <div class="relative">
            <!-- Background image with fade effects -->
            <div class="absolute inset-0 z-0 top-20">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-transparent h-96"></div>
                <img :src="bgHomeUrl" class="w-full h-full object-cover opacity-20" alt="background">
            </div>
            
            <!-- Content with relative positioning -->
            <div class="relative z-10">
                <Hero />
                <PopularProducts />
                <WhyChooseUs />
            </div>
        </div>
    </div>
</template>

<script>
import Hero from '../components/Hero.vue';
import PopularProducts from '../components/PopularProducts.vue';
import WhyChooseUs from '../components/WhyChooseUs.vue';
import bgHomeImage from '../../assets/img/bg-home.jpg';

export default {
    name: 'Home',
    components: {
        Hero,
        PopularProducts,
        WhyChooseUs,
    },
    data() {
        return {
            bgHomeUrl: bgHomeImage
        }
    }
}
</script>