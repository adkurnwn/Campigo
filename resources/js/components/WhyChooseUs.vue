<template>
    <section class="py-10 mx-8">
        <div class="h-auto">
            <div class="text-3xl font-playfair font-bold p-6 text-center bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-transparent bg-clip-text">
            Mengapa Memilih Kami
            </div>
        </div>
        <div class="container mx-auto ">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div v-for="(feature, index) in features" :key="index"
                    class="flex flex-col items-center p-6 bg-white bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg">
                    <div class="mb-4">
                        <span v-html="feature.icon" class="w-12 h-12 text-teal-600"></span>
                    </div>
                    <h4 class="text-xl font-playfair font-bold text-gray-900 mb-2">{{ feature.title }}</h4>
                    <p class="text-gray-600 text-center">{{ feature.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
const features = [
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>`,
        title: 'Ahli Di bidangnya',
        description: 'Kami telah berpengalaman di bidang alat camping dan outdoor sejak tahun 2009.'
    },
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>`,
        title: 'Layanan berkualitas',
        description: 'Pelayanan terbaik senantiasa kami berikan kepada setiap pelanggan'
    },
    {
        icon: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>`,
        title: 'Harga terjangkau',
        description: 'Tak perlu berpikir dua kali untuk masalah harga, karena kami senantiasa memberikan pilihan harga terbaik.'
    }
];
</script>