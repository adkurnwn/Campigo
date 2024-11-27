<template>
    <div class="p-6">
        <!-- Toast Notification -->
        <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="translate-x-full opacity-0"
            enter-to-class="translate-x-0 opacity-100"
            leave-active-class="transform transition duration-300 ease-in"
            leave-from-class="translate-x-0 opacity-100"
            leave-to-class="translate-x-full opacity-0"
        >
            <div v-if="showToast" 
                 class="fixed top-20 right-6 bg-gradient-to-r from-teal-600/80 via-green-600/80 to-blue-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ successMessage }}
            </div>
        </Transition>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Image Section -->
            <div class="relative overflow-hidden rounded-lg">
                <img :src="`/storage/${barang.image}`" :alt="barang.nama"
                    class="w-full h-auto object-cover" />
            </div>

            <!-- Product Info Section -->
            <div class="space-y-6">
                <!-- Product Name and Brand -->
                <div class="space-y-2">
                    <h2 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight">
                        {{ barang.nama }}
                    </h2>
                    <h3 class="text-xl font-light italic text-gray-600">
                        {{ barang.merk }}
                    </h3>
                </div>

                <!-- Price -->
                <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-4 py-2">
                    <p class="text-2xl font-semibold text-teal-600">
                        {{ formatCurrency(barang.harga) }}
                        <span class="text-sm text-gray-500 font-normal">/hari</span>
                    </p>
                </div>

                <!-- Description -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="text-base leading-relaxed text-gray-600 text-justify" v-html="barang.deskripsi"></div>
                </div>

                <!-- Category and Stock -->
                <div class="flex items-center space-x-1 border-t border-gray-200 pt-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Kategori:</span>
                        <span class="px-3 py-1 text-sm font-medium text-teal-600 bg-teal-100 rounded-full">
                            {{ barang.kategori }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Berat:</span>
                        <span class="px-3 py-1 text-sm font-medium text-teal-600 bg-teal-100 rounded-full">
                            {{ barang.berat ?? 'null' }} {{ barang.berat ? 'g' : '' }}
                        </span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Stok:</span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full"
                            :class="{
                                'bg-green-50 text-green-600': barang.stok > 5,
                                'bg-yellow-50 text-yellow-600': barang.stok <= 5 && barang.stok > 0,
                                'bg-red-50 text-red-600': barang.stok === 0
                            }">
                            {{ barang.stok }} unit
                        </span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">Disewa:</span>
                        <span class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full">
                            {{ barang.count_disewa ?? 0 }} kali
                        </span>
                    </div>
                </div>

                <!-- Add to Cart Form -->
                <form @submit.prevent="submitCart" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <label for="quantity" class="text-gray-700 font-medium">Qty:</label>
                        <div class="flex items-center border-2 border-teal-100 rounded-lg">
                            <button 
                                type="button" 
                                @click="quantity > 1 ? quantity-- : null"
                                class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-l-lg"
                                :disabled="quantity <= 1"
                            >
                                -
                            </button>
                            <span class="px-4 py-1 border-x-2 border-teal-200 text-gray-700">{{ quantity }}</span>
                            <button 
                                type="button" 
                                @click="quantity < barang.stok ? quantity++ : null"
                                class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-r-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="quantity >= barang.stok"
                            >
                                +
                            </button>
                        </div>
                        
                    </div>
                    <button type="submit"
                        class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
                        :disabled="isLoading">
                        <span v-if="isLoading" class="spinner-border animate-spin mr-2"></span>
                        {{ isLoading ? "Menambahkan..." : "Tambahkan ke Keranjang" }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    barang: {
        type: Object,
        required: true
    }
});

const quantity = ref(1);
const isLoading = ref(false);
const successMessage = ref('');
const showToast = ref(false);

const submitCart = async () => {
    try {
        isLoading.value = true;
        successMessage.value = '';

        const response = await axios.post(`/api/cart/add/${props.barang.id}`, {
            quantity: quantity.value
        });

        successMessage.value = response.data.message;
        showToast.value = true;
        quantity.value = 1;

        // Hide toast after 5 seconds
        setTimeout(() => {
            showToast.value = false;
        }, 5000);
    } catch (error) {
        console.error('Error adding product to cart:', error);
        successMessage.value = error.response?.data?.error || 'Terjadi kesalahan saat menambahkan ke keranjang';
        showToast.value = true;
        
        // Hide toast after 5 seconds for error messages too
        setTimeout(() => {
            showToast.value = false;
        }, 5000);
    } finally {
        isLoading.value = false;
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value);
};
</script>
