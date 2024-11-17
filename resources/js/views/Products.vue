<template>
    <div class="container mx-auto px-6">
        <SearchBar @search="handleSearch" />
        <div v-if="searchQuery">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 py-16">
                <ProductCard
                    v-for="barang in paginatedFilteredBarangs"
                    :key="barang.id"
                    :barang="barang"
                />
            </div>
            
            <!-- Pagination Controls for Search Results -->
            <div class="flex justify-center items-center space-x-4 mt-12" v-if="filteredBarangs.length > 0">
                <button 
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="w-24 px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 transition-opacity"
                >
                    Previous
                </button>
                <span class="text-gray-600">
                    Page {{ currentPage }} of {{ totalPages }}
                </span>
                <button 
                    @click="nextPage"
                    :disabled="currentPage >= totalPages"
                    class="w-24 px-4 py-2 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90 transition-opacity"
                >
                    Next
                </button>
            </div>
        </div>
        <ProductSection v-else />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import ProductSection from '../components/ProductSection.vue';
import SearchBar from '../components/SearchBar.vue';
import ProductCard from '../components/ProductCard.vue';
import axios from 'axios';

const searchQuery = ref('');
const barangs = ref([]);
const filteredBarangs = ref([]);
const currentPage = ref(1);
const itemsPerPage = 6;

const totalFilteredPages = computed(() => {
    return Math.ceil(filteredBarangs.value.length / itemsPerPage);
});

const paginatedFilteredBarangs = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredBarangs.value.slice(start, end);
});

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

const nextPage = () => {
    if (currentPage.value < totalFilteredPages.value) {
        currentPage.value++;
        scrollToTop();
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
        scrollToTop();
    }
};

const handleSearch = (query) => {
    currentPage.value = 1; // Reset to first page on new search
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

const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/barang");
        barangs.value = response.data;
    } catch (error) {
        console.error("Error fetching barang:", error);
    }
};

fetchProducts();
</script>