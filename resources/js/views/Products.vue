<template>
    <div class="container mx-auto px-6">
        <SearchBar @search="handleSearch" />
        <div v-if="searchQuery" class="grid grid-cols-1 md:grid-cols-3 gap-12 py-16">
            <ProductCard
                v-for="barang in filteredBarangs"
                :key="barang.id"
                :barang="barang"
            />
        </div>
        <ProductSection v-else />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import ProductSection from '../components/ProductSection.vue';
import SearchBar from '../components/SearchBar.vue';
import ProductCard from '../components/ProductCard.vue';
import axios from 'axios';

const searchQuery = ref('');
const barangs = ref([]);
const filteredBarangs = ref([]);

const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/barang");
        barangs.value = response.data;
    } catch (error) {
        console.error("Error fetching barang:", error);
    }
};

const handleSearch = (query) => {
    searchQuery.value = query;
    if (!query.trim()) {
        filteredBarangs.value = [];
        return;
    }

    const queue = [...barangs.value];
    const result = [];
    const lowercaseQuery = query.toLowerCase();

    while (queue.length > 0) {
        const item = queue.shift();
        if (
            item.nama.toLowerCase().includes(lowercaseQuery) ||
            item.merk.toLowerCase().includes(lowercaseQuery) ||
            item.deskripsi.toLowerCase().includes(lowercaseQuery) ||
            item.harga.toString().includes(lowercaseQuery)
        ) {
            result.push(item);
        }
    }

    filteredBarangs.value = result;
};

fetchProducts();
</script>