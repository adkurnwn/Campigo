<!-- ProdukSection.vue -->
<template>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <FilterSort 
                @filter-change="handleFilterChange"
                @sort-change="handleSortChange" 
            />
            
            <div v-if="loading" class="text-center p-8">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-teal-600 border-t-transparent mx-auto"></div>
                <div class="pt-2 text-teal-600">Memuat...</div>
            </div>
            

            <div v-else>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <ProductCard
                        v-for="barang in paginatedFilteredBarangs"
                        :key="barang.id"
                        :barang="barang"
                    />
                </div>
                
                <!-- Pagination Controls -->
                <div class="flex justify-center items-center space-x-4 mt-12">
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
        </div>
    </section>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import ProductCard from "../components/ProductCard.vue";
import axios from "axios";
import FilterSort from './FilterSort.vue';

const barangs = ref([]);
const currentPage = ref(1);
const itemsPerPage = 6;

const activeFilters = ref([]);
const currentSort = ref('');

const loading = ref(true);

const filteredBarangs = computed(() => {
    if (activeFilters.value.length === 0) return barangs.value;
    return barangs.value.filter(barang => 
        activeFilters.value.includes(barang.kategori)
    );
});

const sortedAndFilteredBarangs = computed(() => {
    let result = filteredBarangs.value;
    
    switch (currentSort.value) {
        case 'harga-asc':
            return [...result].sort((a, b) => a.harga - b.harga);
        case 'harga-desc':
            return [...result].sort((a, b) => b.harga - a.harga);
        case 'stok-asc':
            return [...result].sort((a, b) => a.stok - b.stok);
        case 'stok-desc':
            return [...result].sort((a, b) => b.stok - a.stok);
        default:
            return result;
    }
});

const paginatedFilteredBarangs = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return sortedAndFilteredBarangs.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredBarangs.value.length / itemsPerPage);
});

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
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

const handleFilterChange = (filters) => {
    activeFilters.value = filters;
    currentPage.value = 1; // Reset to first page when filters change
};

const handleSortChange = (sortValue) => {
    currentSort.value = sortValue;
    currentPage.value = 1; // Reset to first page when sort changes
};

const fetchProducts = async () => {
    try {
        loading.value = true;
        const response = await axios.get("/api/barang");
        barangs.value = response.data;
    } catch (error) {
        console.error("Error fetching barang:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchProducts();
});
</script>
