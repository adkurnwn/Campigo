<template>
    <section class="py-10 mx-8">
        <div class="h-auto">
            <div class="text-3xl font-playfair font-bold p-6 text-center bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-transparent bg-clip-text">
            Alat Camping Terpopuler
            </div>
        </div>
        <div class="container mx-auto bg-gradient-to-b ">
            <!-- Decorative Elements -->
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <ProductCard
                    v-for="barang in topBarangs"
                    :key="barang.id"
                    :barang="barang"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import ProductCard from "../components/ProductCard.vue";
import axios from "axios";

const barangs = ref([]);

const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/barang");
        barangs.value = response.data;
    } catch (error) {
        console.error("Error fetching barang:", error);
    }
};

const topBarangs = computed(() => {
    return barangs.value
        .sort((a, b) => b.count_disewa - a.count_disewa)
        .slice(0, 3);
});

onMounted(fetchProducts);
</script>