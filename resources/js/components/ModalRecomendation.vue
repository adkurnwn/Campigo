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
            <div class="p-6">
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
        </ModalComponent>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ModalComponent from './ModalComponent.vue';
import axios from 'axios';

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
        console.log('Recommendations:', response.data);
        // Handle the recommendations display here
    } catch (error) {
        console.error('Error:', error);
    }
};
</script>