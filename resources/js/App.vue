<template>
    <div id="app">
        <!-- Toast Notification -->
        <Transition
            enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="translate-x-full opacity-0"
            enter-to-class="translate-x-0 opacity-100"
            leave-active-class="transform transition duration-300 ease-in"
            leave-from-class="translate-x-0 opacity-100"
            leave-to-class="translate-x-full opacity-0"
        >
            <div v-if="showToast" 
                 class="fixed top-20 right-6 bg-gradient-to-r from-teal-600/80 via-green-600/80 to-blue-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-lg shadow-lg z-50">
                {{ toastMessage }}
            </div>
        </Transition>

        <Header />
        <main class="py-8">
            <router-view></router-view>
        </main>
        <div class="absolute inset-0 bg-gradient-to-b from-teal-100 to-white -z-10"></div>
        <Footer />
    </div>
</template>

<script setup>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import { useToast } from './composables/useToast';

const { showToast, toastMessage } = useToast();
</script>