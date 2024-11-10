<template>
    <div>
        <label
            class="mx-auto relative bg-white min-w-sm max-w-2xl flex flex-col md:flex-row items-center justify-center border py-2 px-2 rounded-2xl gap-2 shadow-2xl focus-within:border-gray-300"
            for="search-bar">
            <input 
                id="search-bar" 
                placeholder="your keyword here"
                class="px-6 py-2 flex-1 rounded-md outline-none bg-white"
                type="text"
                v-model="searchQuery"
                @input="handleSearch">
            <button
                class="px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-xl transition-colors"
                @click="handleSearch">
                <span class="text-sm font-semibold">
                    Search
                </span>
            </button>
        </label>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')
const emit = defineEmits(['search'])

const handleSearch = () => {
    emit('search', searchQuery.value)
}
</script>