<!-- ProdukSection.vue -->
<template>
    <section class="py-16 bg-gradient-to-b from-teal-50 to-white">
        <div class="container mx-auto bg-gradient-to-b from-teal-50">
            

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <ProductCard v-for="product in products" :key="product.id" :product="product" />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ProductCard from "@/components/ProductCard.vue";

const products = ref([]);

// Contoh data produk statis
const staticProducts = [
    { id: 1, name: "Product 1", description: "Description 1", price: 100 },
    { id: 2, name: "Product 2", description: "Description 2", price: 200 },
    { id: 3, name: "Product 3", description: "Description 3", price: 300 },
    { id: 4, name: "Product 4", description: "Description 4", price: 400 },
    { id: 5, name: "Product 5", description: "Description 5", price: 500 },
    { id: 6, name: "Product 6", description: "Description 6", price: 600 },
    { id: 7, name: "Product 7", description: "Description 7", price: 700 },
    { id: 8, name: "Product 8", description: "Description 8", price: 800 },
    { id: 9, name: "Product 9", description: "Description 9", price: 900 },
    { id: 10, name: "Product 10", description: "Description 10", price: 1000 },
    { id: 11, name: "Product 11", description: "Description 11", price: 1100 },
    { id: 12, name: "Product 12", description: "Description 12", price: 1200 },
    { id: 13, name: "Product 13", description: "Description 13", price: 1300 },
    { id: 14, name: "Product 14", description: "Description 14", price: 1400 },
    { id: 15, name: "Product 15", description: "Description 15", price: 1500 }
];

onMounted(() => {
    products.value = staticProducts;
});
</script>
