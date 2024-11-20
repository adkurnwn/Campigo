<template>
    <ProductDetailModal :show="show" @close="$emit('close')">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-playfair font-bold text-gray-900 tracking-tight">Upload Bukti Pembayaran DP</h2>
                
            </div>

            <div class="space-y-6">
                <!-- Payment Information -->
                <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg p-6">
                    <h3 class="text-lg font-playfair font-bold text-gray-900 mb-2">Total Pembayaran DP (50%)</h3>
                    <p class="text-2xl font-semibold text-teal-600">
                        Rp {{ formatPrice(transaction?.total_harga*0.5) }}
                    </p>
                </div>

                <!-- Bank Transfer Information -->
                <div class="bg-white border border-teal-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-3">Informasi Pembayaran Transfer Bank</h4>
                    <div class="space-y-2">
                        <p class="text-gray-600">Bank: <span class="font-medium text-gray-900">BCA</span></p>
                        <p class="text-gray-600">No. Rekening: <span class="font-medium text-gray-900">1234567890</span></p>
                        <p class="text-gray-600">Atas Nama: <span class="font-medium text-gray-900">PT Campigo Indonesia</span></p>
                    </div>
                </div>

                <!-- Upload Form -->
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

                    <div v-if="error" class="text-red-600 text-sm">
                        {{ error }}
                    </div>

                    <button 
                        @click="submitPaymentProof"
                        :disabled="!selectedFile || isUploading"
                        class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ isUploading ? 'Mengunggah...' : 'Upload Bukti Pembayaran' }}
                    </button>
                </div>
            </div>
        </div>
    </ProductDetailModal>
</template>

<script setup>
import { ref, onUnmounted } from 'vue';
import axios from 'axios';
import ProductDetailModal from './ProductDetailModal.vue';

const props = defineProps({
    show: Boolean,
    transaction: Object
});

const emit = defineEmits(['close', 'success']);

const selectedFile = ref(null);
const imagePreview = ref(null);
const error = ref('');
const isUploading = ref(false);
const fileInput = ref(null);

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

// When component is unmounted, clean up the object URL
onUnmounted(() => {
    if (imagePreview.value) {
        URL.revokeObjectURL(imagePreview.value);
    }
});

const formatPrice = (price) => {
    return price ? price.toLocaleString('id-ID') : '0';
};

const submitPaymentProof = async () => {
    if (!selectedFile.value) return;

    isUploading.value = true;
    error.value = '';

    const formData = new FormData();
    formData.append('payment_proof', selectedFile.value);
    formData.append('transaction_id', props.transaction.id);

    try {
        await axios.post('/api/upload-payment-proof', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        emit('success');
        emit('close');
    } catch (e) {
        error.value = e.response?.data?.message || 'Gagal mengunggah bukti pembayaran';
    } finally {
        isUploading.value = false;
    }
};
</script>