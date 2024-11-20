<!-- CartModal.vue -->
<script setup>
import { ref, onMounted, computed } from 'vue';
import ModalComponent from '../components/ModalComponent.vue';
import axios from 'axios';
import JadwalModal from './JadwalModal.vue';
import BayarDPModal from './BayarDPModal.vue';
import { useToast } from '../composables/useToast';

// Add CSRF token to axios headers
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const isOpen = ref(false);
const cartItems = ref([]);
const isLoading = ref(false);
const currentView = ref('cart'); // Add this near other refs
const selectedDays = ref(0);
const selectedStartDate = ref('');
const selectedEndDate = ref('');

const cartTotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + item.harga * item.quantity, 0);
});

const openModal = async () => {
    isOpen.value = true;
    await fetchCart();
};
const closeModal = () => {
    isOpen.value = false;
};

const fetchCart = async () => {
    try {
        const response = await axios.get('/api/cart');
        cartItems.value = Object.entries(response.data.data).map(([id, item]) => ({
            id,
            ...item
        }));
    } catch (error) {
        console.error('Error fetching cart:', error);
    }
};

const updateQuantity = async (id, quantity) => {
    try {
        await axios.put(`/api/cart/update/${id}`, { quantity });
        await fetchCart();
    } catch (error) {
        console.error('Error updating quantity:', error);
    }
};

const removeItem = async (id) => {
    try {
        await axios.delete(`/api/cart/remove/${id}`);
        await fetchCart();
    } catch (error) {
        console.error('Error removing item:', error);
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value);
};

const switchToJadwal = () => {
    currentView.value = 'jadwal';
};

const backToCart = () => {
    currentView.value = 'cart';
};

const switchToPayment = (data) => {
    selectedDays.value = data.days;
    selectedStartDate.value = data.startDate;
    selectedEndDate.value = data.endDate;
    currentView.value = 'payment';
};

const backToJadwal = () => {
    currentView.value = 'jadwal';
};

const { showSuccessToast } = useToast();

const handleCheckoutSuccess = ({ data, message }) => {
    // Show toast using the composable
    showSuccessToast(message);
    
    // Reset cart view
    currentView.value = 'cart';
    
    // Clear cart items
    cartItems.value = [];
    
    // Close modal after toast shows
    setTimeout(() => {
        closeModal();
    }, 0);
};

defineExpose({ openModal });
</script>

<template>
    <ModalComponent :show="isOpen" @close="closeModal">
        <!-- Cart View -->
        <div v-if="currentView === 'cart'" class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Keranjang</h2>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <div v-if="cartItems.length === 0" class="text-center py-4 text-gray-500">
                    Keranjang belanja kosong
                </div>

                <div v-for="item in cartItems" :key="item.id"
                    class="flex items-center justify-between border-b border-gray-200 py-4">
                    <div class="flex items-center space-x-4">
                        <img :src="`/storage/${item.image}`" :alt="item.nama" class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <div class="flex items-center space-x-3">
                                <h3 class="text-xl font-playfair font-bold text-gray-900">{{ item.nama }}</h3>
                                <span class="text-gray-300">|</span>
                                <p class="text-lg font-light italic text-gray-600">{{ item.merk }}</p>
                            </div>
                            <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-3 py-1 mt-2">
                                <p class="text-lg font-semibold text-teal-600">
                                    {{ formatCurrency(item.harga) }}
                                    <span class="text-sm text-gray-500 font-normal">/hari</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border-2 border-teal-200 rounded-lg">
                            <button @click="updateQuantity(item.id, item.quantity - 1)"
                                class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-l-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="item.quantity <= 1">
                                -
                            </button>
                            <span class="w-12 text-center font-medium text-gray-700 border-x-2 border-teal-200 py-1">{{ item.quantity }}</span>
                            <button @click="updateQuantity(item.id, item.quantity + 1)"
                                class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-r-lg">
                                +
                            </button>
                        </div>

                        <button @click="removeItem(item.id)" 
                            class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="cartItems.length > 0" class="mt-6 space-y-4">
                <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-4 py-2 w-full">
                    <p class="text-2xl font-semibold text-teal-600">
                        Total: {{ formatCurrency(cartItems.reduce((sum, item) => sum + item.harga * item.quantity, 0)) }}
                        <span class="text-sm text-gray-500 font-normal">/hari</span>
                    </p>
                </div>
                <button @click="switchToJadwal"
                    class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    Lanjutkan ke Penjadwalan
                </button>
            </div>
        </div>

        <!-- Jadwal View -->
        <JadwalModal v-else-if="currentView === 'jadwal'" @back="backToCart" @proceed-to-payment="switchToPayment" />

        <!-- Payment View -->
        <BayarDPModal v-else-if="currentView === 'payment'" 
            @back="backToJadwal" 
            @checkout-success="handleCheckoutSuccess"
            :total="cartTotal"
            :days="selectedDays"
            :start-date="selectedStartDate"
            :end-date="selectedEndDate"
            :cart-items="cartItems" />
    </ModalComponent>
</template>