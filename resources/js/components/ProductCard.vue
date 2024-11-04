<template>
    <div class="bg-white rounded-2xl overflow-hidden shadow-lg transform transition-all duration-150 hover:scale-105">
        <!-- Image Section -->
        <div class="relative overflow-hidden group">
            <img :src="`/storage/${product.image}`" :alt="product.name"
                class="w-full h-64 object-cover transform transition-transform duration-700 group-hover:scale-110" />
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
        </div>

        <!-- Product Info Section -->
        <div class="p-6">
            <h3 class="text-2xl font-playfair font-bold text-gray-900 mb-2">
                {{ product.name }}
            </h3>
            <p class="text-lg font-medium text-teal-500 mb-4">
                {{ formatCurrency(product.price) }}
            </p>
            <!-- Add to Cart Form -->
            <form @submit.prevent="submitCart" class="space-y-4">
                <div class="flex items-center space-x-4">

                </div>
                <button type="submit"
                    class="w-full px-6 py-3 bg-teal-500 hover:bg-teal-600 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
                    :disabled="isLoading">
                    <span v-if="isLoading" class="spinner-border animate-spin mr-2"></span>
                    {{
                        isLoading ? "Adding..." : "Add to Cart"
                    }}
                </button>
            </form>

            <!-- Success Message -->
            <p v-if="successMessage" class="mt-4 text-green-600 font-medium text-center">
                {{ successMessage }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// Props for product data
const props = defineProps({
    product: Object,
});

// Local state
const quantity = ref(1);
const isLoading = ref(false);
const successMessage = ref("");

// Submit to cart function
const submitCart = () => {
    isLoading.value = true;
    successMessage.value = "";

    setTimeout(() => {
        successMessage.value = "Added to cart!";
        quantity.value = 1; // Reset quantity after success
        isLoading.value = false;
    }, 1000);
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
