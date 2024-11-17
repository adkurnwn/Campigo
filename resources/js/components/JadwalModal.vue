<script setup>
import { ref, computed } from 'vue';

const emit = defineEmits(['back', 'proceedToPayment']);
const startDate = ref('');
const endDate = ref('');
const errorMessage = ref('');

const isValidRange = computed(() => {
    if (!startDate.value || !endDate.value) return true;
    
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (end < start) {
        errorMessage.value = 'Tanggal selesai harus setelah tanggal mulai';
        return false;
    }
    
    if (diffDays > 6) {
        errorMessage.value = 'Maksimal penyewaan adalah 7 hari';
        return false;
    }
    
    errorMessage.value = '';
    return true;
});

const selectedDays = computed(() => {
    if (!startDate.value || !endDate.value) return 0;
    
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    const diffTime = Math.abs(end - start);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates
    
    return diffDays;
});

const validateDates = () => {
    return isValidRange.value;
};

const proceedToPayment = () => {
    if (validateDates()) {
        emit('proceedToPayment', { 
            startDate: startDate.value, 
            endDate: endDate.value,
            days: selectedDays.value 
        });
    }
};
</script>

<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Pilih Jadwal</h2>
            <button @click="$emit('back')" 
                class="text-teal-600 hover:text-teal-700 font-medium transform transition-all duration-300 hover:scale-105">
                Kembali ke Keranjang
            </button>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <h3 class="text-lg font-playfair font-bold text-gray-900">Tanggal Mulai</h3>
                    <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-0.5 rounded-lg">
                        <input type="date"
                            v-model="startDate"
                            :min="new Date().toISOString().split('T')[0]"
                            class="w-full bg-white rounded-lg p-3 border-2 border-transparent focus:border-teal-600 focus:outline-none transition-all duration-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="text-lg font-playfair font-bold text-gray-900">Tanggal Selesai</h3>
                    <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-0.5 rounded-lg">
                        <input type="date"
                            v-model="endDate"
                            :min="startDate || new Date().toISOString().split('T')[0]"
                            class="w-full bg-white rounded-lg p-3 border-2 border-transparent focus:border-teal-600 focus:outline-none transition-all duration-300">
                    </div>
                </div>
            </div>
            
            <div v-if="errorMessage" 
                class="bg-red-50 text-red-600 p-3 rounded-lg text-sm font-medium">
                {{ errorMessage }}
            </div>

            <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-4 py-3 w-full">
                <p class="text-2xl font-semibold text-teal-600">
                    Durasi Sewa: {{ selectedDays }} hari
                </p>
            </div>

            <button @click="proceedToPayment"
                :disabled="!isValidRange || !startDate || !endDate"
                class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:shadow-none">
                Lanjutkan ke Pembayaran DP
            </button>
        </div>
    </div>
</template>
