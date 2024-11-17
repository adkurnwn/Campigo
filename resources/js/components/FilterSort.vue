<template>
    <div class="flex justify-between items-start mb-8 border-b pb-6">
        <!-- Filter Section -->
        <div class="flex flex-wrap gap-3">
            <!-- Active Filters -->
            <template v-for="category in categories" :key="category">
                <button
                    v-if="activeFilters.includes(category)"
                    @click="toggleFilter(category)"
                    class="order-first px-3 py-1 text-sm font-medium rounded-full transition-all duration-200 hover:scale-105
                        text-white bg-gradient-to-r from-teal-600 via-green-600 to-blue-600"
                >
                    {{ category }}
                </button>
            </template>
            
            <!-- Inactive Filters -->
            <template v-for="category in categories" :key="`inactive-${category}`">
                <button
                    v-if="!activeFilters.includes(category)"
                    @click="toggleFilter(category)"
                    class="px-3 py-1 text-sm font-medium rounded-full transition-all duration-200 hover:scale-105
                        text-teal-600 bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 
                        hover:from-teal-600/20 hover:via-green-600/20 hover:to-blue-600/20"
                >
                    {{ category }}
                </button>
            </template>
        </div>

        <!-- Sort Section -->
        <div class="flex items-center space-x-4">
            <label for="sort" class="text-sm text-gray-500">Urutkan:</label>
            <select id="sort" 
                v-model="selectedSort"
                @change="handleSort"
                class="px-3 py-1 text-sm font-medium text-teal-600 bg-gradient-to-r from-teal-600/20 via-green-600/20 to-blue-600/20
                    border-none focus:ring-2 focus:ring-teal-200 focus:ring-opacity-50 rounded-full
                    cursor-pointer hover:from-teal-600/30 hover:via-green-600/30 hover:to-blue-600/30 transition-colors duration-200">
                <option value="latest">Terbaru</option>
                <option value="harga-asc">Harga: Rendah ke Tinggi</option>
                <option value="harga-desc">Harga: Tinggi ke Rendah</option>
                <option value="stok-asc">Stok: Sedikit ke Banyak</option>
                <option value="stok-desc">Stok: Banyak ke Sedikit</option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const emit = defineEmits(['filter-change', 'sort-change']);
const activeFilters = ref([]);
const selectedSort = ref('latest'); // Set default sort to 'latest'

const categories = [
    'Tenda',
    'Bag',
    'Perlengkapan Tidur',
    'Lampu',
    'Alat Masak dan Makan',
    'Wears',
    'Lainnya'
];

const toggleFilter = (category) => {
    if (activeFilters.value.includes(category)) {
        activeFilters.value = activeFilters.value.filter(f => f !== category);
    } else {
        activeFilters.value.push(category);
    }
};

const handleSort = () => {
    emit('sort-change', selectedSort.value);
};

watch(activeFilters, (newFilters) => {
    emit('filter-change', newFilters);
}, { deep: true });
</script>
