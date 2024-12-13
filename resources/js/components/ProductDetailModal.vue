<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '4xl', // Updated default
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        // Remove scrollbar manipulation
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-md', // 1.5x of sm
        md: 'sm:max-w-lg', // 1.5x of md
        lg: 'sm:max-w-xl', // 1.5x of lg
        xl: 'sm:max-w-2xl', // 1.5x of xl
        '2xl': 'sm:max-w-3xl', // 1.5x of 2xl
        '3xl': 'sm:max-w-4xl', // New larger size
        '4xl': 'sm:max-w-5xl', // New larger size
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 sm:px-0 z-[9999] flex items-center justify-center min-h-screen" scroll-region>
                <!-- Background overlay -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-500/50 backdrop-blur-sm transition-opacity duration-300" />
                    </div>
                </Transition>

                <!-- Modal -->
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="relative bg-gradient-to-b from-teal-100 to-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <!-- Close Button -->
                        <button 
                            v-if="closeable"
                            @click="close"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-500 transition-colors duration-150 ease-in-out focus:outline-none"
                        >
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        
                        <slot v-if="show" />
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
