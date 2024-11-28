<template>
    <div id="app">
        <!-- Toast Notification -->
        <Transition
            :enter-active-class="`transform transition duration-300 ease-out ${isMobile ? 'bottom-toast' : 'side-toast'}`"
            :enter-from-class="`opacity-0 ${isMobile ? 'translate-y-full' : 'translate-x-full'}`"
            :enter-to-class="`opacity-100 ${isMobile ? 'translate-y-0' : 'translate-x-0'}`"
            :leave-active-class="`transform transition duration-300 ease-in ${isMobile ? 'bottom-toast' : 'side-toast'}`"
            :leave-from-class="`opacity-100 ${isMobile ? 'translate-y-0' : 'translate-x-0'}`"
            :leave-to-class="`opacity-0 ${isMobile ? 'translate-y-full' : 'translate-x-full'}`"
        >
            <div v-if="showToast" 
                 :class="`fixed ${isMobile ? 'bottom-20 left-6 right-6' : 'top-20 right-6'} bg-gradient-to-r from-teal-600/80 via-green-600/80 to-blue-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-lg shadow-lg z-50`">
                {{ toastMessage }}
            </div>
        </Transition>

        <Header />
        <main class="">
            <router-view></router-view>
        </main>
        <div class="absolute inset-0 bg-gradient-to-b from-teal-100 to-white -z-10"></div>
        <Footer />
        <ModalRecomendation />
    </div>
</template>

<script setup>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import ModalRecomendation from './components/ModalRecomendation.vue';
import { useToast } from './composables/useToast';
import { ref, onMounted, onUnmounted } from 'vue';

const { showToast, toastMessage } = useToast();
const isMobile = ref(false);

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});
</script>