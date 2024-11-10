<template>
    <div class="bg-gradient-to-t from-teal-50 to-slate-100 rounded-2xl overflow-hidden shadow-lg transform transition-all duration-300 hover:scale-105">
        <!-- Image Section -->
        <div class="relative overflow-hidden group p-8">
            <img :src="`/storage/${barang.image}`" :alt="barang.nama"
                class="w-full h-64 object-cover transform transition-transform duration-700 group-hover:scale-110"
                style="width: 400px; height: 230px;" />
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="p-6">
            <h3 class="text-2xl font-playfair font-bold text-gray-900 mb-2">
                {{ barang.nama }}
            </h3>
            <h3 class="text-base font-normal text-gray-500 mb-2">
                {{ barang.merk }}
            </h3>
            <p class="text-lg font-medium text-teal-500 mb-4">
                {{ formatCurrency(barang.harga) }}
            </p>
            <!-- Add to Cart Form -->
            <form @submit.prevent="submitCart" class="space-y-4">
                <div class="flex items-center space-x-4">
                    <label for="quantity" class="text-gray-700 font-medium">Qty:</label>
                    <input type="number" v-model.number="quantity" min="1"
                        class="w-20 text-center border-2 border-teal-200 rounded-lg focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                        required />
                </div>
                <button type="submit"
                    class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
                    :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border animate-spin mr-2"></span>
                    {{
                        isLoading ? "Menambahkan..." : "Tambahkan ke Keranjang"
                    }}
                </button>
            </form>

            <!-- Success Message -->
            <p v-if="successMessage" class="mt-4 text-teal-600 font-medium text-center">
                {{ successMessage }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

// Props for product data
const props = defineProps({
    barang: Object,
});

// Local state
const quantity = ref(1);
const isLoading = ref(false);
const successMessage = ref("");

// Submit to cart function
const submitCart = async () => {
    try {
        isLoading.value = true;
        successMessage.value = "";

        await axios.post(`/api/cart/add/${props.barang.id}`, {
            quantity: quantity.value,
        });

        successMessage.value = "Produk berhasil ditambahkan ke keranjang!";
        quantity.value = 1; // Reset quantity after success
    } catch (error) {
        console.error("Error adding product to cart:", error);
    } finally {
        isLoading.value = false;
    }
};

// Utility function to format price
const formatCurrency = (value) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(value);
};
</script>

<style scoped>
.spinner-border {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
