<template>
    <ModalComponent :show="show" @close="$emit('close')">
        <div class="w-auto">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-playfair font-bold text-gray-900 tracking-tight">{{ title }}</h2>
                    <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <p class="text-gray-600 mb-6">{{ message }}</p>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button @click="$emit('close')" 
                        class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200">
                        Batal
                    </button>
                    <button @click="handleConfirm" 
                        class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors duration-200">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </ModalComponent>
</template>

<script>
import ModalComponent from './ModalComponent.vue';

export default {
    components: {
        ModalComponent
    },
    props: {
        show: Boolean,
        title: {
            type: String,
            default: 'Konfirmasi'
        },
        message: {
            type: String,
            required: true
        }
    },
    emits: ['close', 'confirm'],
    methods: {
        handleConfirm() {
            this.$emit('confirm');
            this.$emit('close');
        }
    }
}
</script>