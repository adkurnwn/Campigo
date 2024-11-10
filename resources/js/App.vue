<template>
    <div id="app">
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
</script>