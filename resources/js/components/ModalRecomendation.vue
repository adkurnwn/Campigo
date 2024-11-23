<template>
    <div>
        <button
            @click="isOpen = true"
            class="fixed bottom-6 right-6 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition-all duration-300 ease-in-out transform hover:scale-105 z-50 group"
            title="Generate Rekomendasi">
            <div class="flex items-center h-12">
                <div class="p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M21,14v6c0,1.1-0.9,2-2,2H4c-1.1,0-2-0.9-2-2V5c0-1.1,0.9-2,2-2h7" 
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
                        <path d="M17.1,13c-0.4-2.7-1.5-5.1-5.5-5.5c4-0.4,5.1-2.8,5.5-5.5c0.4,2.7,1.5,5.1,5.5,5.5C18.5,7.9,17.4,10.3,17.1,13z" 
                            stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
                        <path d="M10,17c-0.1-1-0.5-1.9-2-2c1.5-0.1,1.9-1,2-2c0.1,1,0.5,1.9,2,2C10.5,15.1,10.1,16,10,17z" 
                            stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"/>
                    </svg>
                </div>
                <div class="w-0 group-hover:w-48 overflow-hidden transition-[width] duration-300 ease-in-out">
                    <span class="whitespace-nowrap px-1">Generate Rekomendasi</span>
                </div>
            </div>
        </button>

        <ModalComponent :show="isOpen" @close="isOpen = false">
            <!-- Form View -->
            <div v-if="currentView === 'form'" class="p-6 max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Generate Rekomendasi</h2>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Jumlah Orang -->
                    <div class="flex items-center space-x-4">
                        <label for="jumlahOrang" class="w-1/3 block text-lg font-playfair font-bold text-gray-900">
                            Jumlah Orang
                        </label>
                        <div class="w-2/3 flex items-center">
                            <div class="inline-flex items-center border-2 border-teal-200 rounded-lg">
                                <button type="button" 
                                    @click="decrementJumlahOrang"
                                    class="w-10 h-9 flex items-center justify-center text-teal-600 hover:bg-teal-50 rounded-l-lg disabled:opacity-50 disabled:cursor-not-allowed text-lg font-medium"
                                    :disabled="formData.jumlahOrang <= 1">
                                    -
                                </button>
                                <input
                                    type="number"
                                    id="jumlahOrang"
                                    v-model="formData.jumlahOrang"
                                    min="1"
                                    class="w-14 text-center font-medium text-gray-700 border-x-2 border-teal-200 h-9 focus:outline-none"
                                    required
                                >
                                <button type="button"
                                    @click="incrementJumlahOrang"
                                    class="w-10 h-9 flex items-center justify-center text-teal-600 hover:bg-teal-50 rounded-r-lg text-lg font-medium">
                                    +
                                </button>
                            </div>
                            <span class="ml-2 text-gray-600">Orang</span>
                        </div>
                    </div>

                    <!-- Jenis Camping -->
                    <div class="flex items-center space-x-4">
                        <label for="jenisCamping" class="w-1/3 block text-lg font-playfair font-bold text-gray-900">
                            Jenis Camping
                        </label>
                        <select
                            id="jenisCamping"
                            v-model="formData.jenisCamping"
                            class="w-2/3 px-4 py-2 border-2 border-teal-200 rounded-lg text-gray-700 focus:outline-none focus:border-teal-600"
                            required
                        >
                            <option value="" disabled>Pilih jenis camping</option>
                            <option v-for="jenis in jenisOptions" :key="jenis" :value="jenis">
                                {{ jenis.charAt(0).toUpperCase() + jenis.slice(1) }}
                            </option>
                        </select>
                    </div>

                    <!-- Durasi Camping -->
                    <div class="flex items-center space-x-4">
                        <label for="durasi" class="w-1/3 block text-lg font-playfair font-bold text-gray-900">
                            Durasi Camping
                        </label>
                        <div class="w-2/3 flex items-center">
                            <div class="inline-flex items-center border-2 border-teal-200 rounded-lg">
                                <button type="button"
                                    @click="decrementDurasi"
                                    class="w-10 h-9 flex items-center justify-center text-teal-600 hover:bg-teal-50 rounded-l-lg disabled:opacity-50 disabled:cursor-not-allowed text-lg font-medium"
                                    :disabled="formData.durasi <= 1">
                                    -
                                </button>
                                <input
                                    type="number"
                                    id="durasi"
                                    v-model="formData.durasi"
                                    min="1"
                                    class="w-14 text-center font-medium text-gray-700 border-x-2 border-teal-200 h-9 focus:outline-none"
                                    required
                                >
                                <button type="button"
                                    @click="incrementDurasi"
                                    class="w-10 h-9 flex items-center justify-center text-teal-600 hover:bg-teal-50 rounded-r-lg text-lg font-medium">
                                    +
                                </button>
                            </div>
                            <span class="ml-2 text-gray-600">Hari</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg mt-6"
                    >
                        Generate Rekomendasi
                    </button>
                </form>
            </div>

            <!-- Recommendations View -->
            <div v-else-if="currentView === 'recommendations'" class="flex flex-col h-[85vh]">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Rekomendasi Peralatan</h2>
                            <p class="text-gray-600 mt-1">Berdasarkan {{ formData.jumlahOrang }} orang, {{ formData.durasi }} hari, {{ formData.jenisCamping }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <button @click="backToForm" class="text-teal-600 hover:text-teal-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <div class="space-y-4">
                        <div v-for="item in recommendations" :key="item.id"
                            class="flex items-center justify-between border-b border-gray-200 py-4">
                            <div class="flex items-center space-x-4">
                                <img :src="`/storage/${item.image}`" :alt="item.nama" class="w-20 h-20 object-cover rounded-lg">
                                <div>
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-xl font-playfair font-bold text-gray-900">{{ item.nama }}</h3>
                                        <span class="text-gray-300">|</span>
                                        <p class="text-lg font-light italic text-gray-600">{{ item.merk }}</p>
                                    </div>
                                    <div class="flex items-center mt-2">
                                        <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-3 py-1">
                                            <p class="text-lg font-semibold text-teal-600">
                                                {{ formatCurrency(item.harga) }}
                                                <span class="text-sm text-gray-500 font-normal">/hari</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border-2 border-teal-200 rounded-lg">
                                    <button 
                                        @click="updateItemQuantity(item.id, item.quantity - 1)"
                                        class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-l-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="item.quantity <= 1">
                                        -
                                    </button>
                                    <span class="w-12 text-center font-medium text-gray-700 border-x-2 border-teal-200 py-1">
                                        {{ item.quantity }}
                                    </span>
                                    <button 
                                        @click="updateItemQuantity(item.id, item.quantity + 1)"
                                        class="px-3 py-1 text-teal-600 hover:bg-teal-50 rounded-r-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="item.quantity >= item.stok">
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
                </div>

                <div class="p-6 border-t border-gray-200">
                    <div class="space-y-4">
                        <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-4 py-2 w-full">
                            <p class="text-2xl font-semibold text-teal-600">
                                Total: {{ formatCurrency(totalHarga) }}
                                <span class="text-sm text-gray-500 font-normal">/hari</span>
                            </p>
                        </div>
                        <button 
                            @click="addAllToCart"
                            class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                        >
                            Tambah Semua ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </ModalComponent>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import ModalComponent from './ModalComponent.vue';
import axios from 'axios';
import { useToast } from '../composables/useToast';

const { showSuccessToast, showErrorToast } = useToast();

const isOpen = ref(false);
const jenisOptions = ['gunung', 'pantai', 'hutan', 'perkemahan'];
const formData = ref({
    jumlahOrang: 1,
    jenisCamping: '',
    durasi: 1
});

const incrementJumlahOrang = () => formData.value.jumlahOrang++;
const decrementJumlahOrang = () => {
    if (formData.value.jumlahOrang > 1) formData.value.jumlahOrang--;
};

const incrementDurasi = () => formData.value.durasi++;
const decrementDurasi = () => {
    if (formData.value.durasi > 1) formData.value.durasi--;
};

const handleSubmit = async () => {
    try {
        const response = await axios.post('/api/recommendations', {
            jumlahOrang: formData.value.jumlahOrang,
            jenisCamping: formData.value.jenisCamping,
            durasi: formData.value.durasi
        });
        recommendations.value = response.data.recommendations;
        currentView.value = 'recommendations';
    } catch (error) {
        console.error('Error:', error);
    }
};

const backToForm = () => {
    currentView.value = 'form';
};

const currentView = ref('form');
const recommendations = ref([]);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value);
};

const totalHarga = computed(() => {
    return recommendations.value.reduce((sum, item) => sum + (item.harga * item.quantity), 0);
});

const updateItemQuantity = (itemId, newQuantity) => {
    const item = recommendations.value.find(item => item.id === itemId);
    if (item) {
        if (newQuantity > 0 && newQuantity <= item.stok) {
            item.quantity = newQuantity;
        }
    }
};

const removeItem = (itemId) => {
    recommendations.value = recommendations.value.filter(item => item.id !== itemId);
};

const addAllToCart = async () => {
    try {
        const items = recommendations.value.map(item => ({
            id: item.id,
            quantity: item.quantity
        }));

        await axios.post('/api/cart/add-multiple', { items });
        
        showSuccessToast('Semua item berhasil ditambahkan ke keranjang!');
        isOpen.value = false;
    } catch (error) {
        console.error('Error adding items to cart:', error);
        showErrorToast('Gagal menambahkan item ke keranjang');
    }
};
</script>