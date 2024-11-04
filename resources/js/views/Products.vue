<template>

    <div class="container mx-auto px-6">
        <SearchBar />
        <ProductSection />
    </div>

</template>

<script>
import ProductSection from '../components/ProductSection.vue';
import SearchBar from '../components/SearchBar.vue';

export default {
    name: 'Products',
    components: {
        ProductSection,
        SearchBar,
    }
}
</script>