<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    total: {
        type: Number,
        required: true
    },
    days: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['back']);

const dpAmount = computed(() => props.total * props.days * 0.5);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(value);
};

const selectedPaymentMethod = ref('');
const isPaymentValid = computed(() => selectedPaymentMethod.value !== '');
</script>

<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Pembayaran DP</h2>
            <button @click="$emit('back')" 
                class="text-teal-600 hover:text-teal-700 font-medium transform transition-all duration-300 hover:scale-105">
                Kembali ke Jadwal
            </button>
        </div>

        <div class="space-y-6">
            <!-- Total DP Section -->
            <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg p-6">
                <h3 class="text-lg font-playfair font-bold text-gray-900 mb-2">Total DP yang harus dibayar (50%)</h3>
                <p class="text-2xl font-semibold text-teal-600">
                    {{ formatCurrency(dpAmount) }}
                </p>
            </div>

            <!-- Payment Method Section -->
            <div class="space-y-3">
                <h3 class="text-lg font-playfair font-bold text-gray-900">Pilih Metode Pembayaran</h3>
                <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-0.5 rounded-lg">
                    <select v-model="selectedPaymentMethod"
                        class="w-full bg-white rounded-lg p-3 border-2 border-transparent focus:border-teal-600 focus:outline-none transition-all duration-300">
                        <option value="" disabled>Pilih metode pembayaran</option>
                        <option value="tunai">Tunai</option>
                        <option value="transferbank" disabled>Transfer Bank (belum tersedia)</option>
                        <option value="ewallet" disabled>E-Wallet (belum tersedia)</option>
                        <option value="qris" disabled>QRIS (belum tersedia)</option>
                    </select>
                </div>
            </div>

            <button :disabled="!isPaymentValid"
                class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:shadow-none">
                Bayar Sekarang
            </button>
        </div>
    </div>
</template>
