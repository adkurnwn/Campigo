<!-- ProdukSection.vue -->
<template>
    <section >
        <div class="container mx-auto mt-10">
            <!-- Decorative Elements -->
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <ProductCard
                    v-for="barang in barangs"
                    :key="barang.id"
                    :barang="barang"
                />
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from "vue";
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

onMounted(() => {
    fetchProducts();
});
</script>
