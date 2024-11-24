<script setup>
import { computed, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    transaction: Object,
});

const emit = defineEmits(['close', 'success']);
const isLoading = ref(false);
const error = ref('');
const selectedPaymentMethod = ref('');
const currentStep = ref(1);
const selectedFile = ref(null);
const imagePreview = ref(null);
const fileInput = ref(null);

const remainingAmount = computed(() => {
    if (!props.transaction) return 0;
    return props.transaction.total_harga - props.transaction.dp_amount;
});

const formatPrice = (price) => {
    return price ? price.toLocaleString('id-ID') : '0';
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
        error.value = '';
    }
};

const removeImage = () => {
    selectedFile.value = null;
    imagePreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const goToNextStep = () => {
    if (!selectedPaymentMethod.value) return;
    currentStep.value = 2;
};

const goBackStep = () => {
    currentStep.value = 1;
    removeImage();
};

const submitPelunasan = async () => {
    if (!selectedPaymentMethod.value || !selectedFile.value) {
        error.value = 'Please select a payment method and upload an image';
        return;
    }
    
    try {
        isLoading.value = true;
        const formData = new FormData();
        formData.append('payment_proof', selectedFile.value);
        formData.append('payment_method', selectedPaymentMethod.value);
        formData.append('transaction_id', props.transaction.id);
        
        await axios.post(`/api/transaction/${props.transaction.id}/pelunasan`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        emit('success');
        emit('close');
    } catch (err) {
        console.error('Error processing pelunasan:', err);
        error.value = err.response?.data?.message || 'Gagal memproses pelunasan';
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Step 1: Payment Method Selection -->
                <div v-if="currentStep === 1" class="p-6 space-y-6">
                    <h3 class="text-2xl font-playfair font-bold text-gray-900">Lunasi Pembayaran</h3>
                    
                    <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Sisa yang harus dibayar:</h4>
                        <p class="text-2xl font-semibold text-teal-600">
                            Rp {{ formatPrice(remainingAmount) }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        <h4 class="font-medium text-gray-900">Pilih Metode Pembayaran:</h4>
                        <select v-model="selectedPaymentMethod"
                            class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-teal-600 focus:outline-none">
                            <option value="" disabled>Pilih metode pembayaran</option>
                            <option value="tunai">Tunai</option>
                            <option value="transferbank">Transfer Bank</option>
                        </select>
                    </div>

                    <!-- Bank Transfer Information -->
                    <div v-if="selectedPaymentMethod === 'transferbank'" class="space-y-3 mt-4">
                        <div class="bg-white border border-teal-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-3">Informasi Pembayaran Transfer Bank</h4>
                            <div class="space-y-2">
                                <p class="text-gray-600">Bank: <span class="font-medium text-gray-900">BCA</span></p>
                                <p class="text-gray-600">No. Rekening: <span class="font-medium text-gray-900">1234567890</span></p>
                                <p class="text-gray-600">Atas Nama: <span class="font-medium text-gray-900">PT Campigo Indonesia</span></p>
                            </div>
                        </div>
                    </div>

                    <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>

                    <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                        <button @click="emit('close')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button @click="goToNextStep"
                            :disabled="!selectedPaymentMethod"
                            class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50">
                            Lanjut
                        </button>
                    </div>
                </div>

                <!-- Step 2: Upload Payment Proof -->
                <div v-else class="p-6 space-y-6">
                    <div class="flex items-center mb-4">
                        <button @click="goBackStep" class="mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <h3 class="text-2xl font-playfair font-bold text-gray-900">Upload Bukti Pembayaran</h3>
                    </div>

                    <div class="space-y-4">
                        <!-- Image Preview -->
                        <div v-if="imagePreview" class="relative">
                            <img 
                                :src="imagePreview" 
                                alt="Preview" 
                                class="w-full h-64 object-contain rounded-lg border-2 border-teal-200"
                            />
                            <button 
                                @click="removeImage"
                                class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div class="border-2 border-dashed border-teal-200 rounded-lg p-4">
                            <input 
                                type="file" 
                                @change="handleFileUpload" 
                                accept="image/*"
                                class="w-full"
                                ref="fileInput"
                            >
                        </div>

                        <div v-if="error" class="text-red-600 text-sm">{{ error }}</div>

                        <div class="border-t border-gray-200 pt-6 flex justify-end space-x-3">
                            <button @click="emit('close')"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>
                            <button @click="submitPelunasan"
                                :disabled="!selectedFile || isLoading"
                                class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50">
                                {{ isLoading ? 'Memproses...' : 'Konfirmasi' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>