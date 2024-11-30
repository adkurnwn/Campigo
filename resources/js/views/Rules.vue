<template>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-playfair font-bold text-gray-900 text-center">
                    TATA TERTIB PERSEWAAN CAMPIGO
                </h1>
            </div>

            <!-- Rules Content -->
            <div class="p-6 space-y-6">
                <div v-for="(section, index) in rules" :key="index" class="space-y-4">
                    <h2 class="text-xl font-playfair font-semibold text-teal-600">
                        {{ index + 1 }}. {{ section.title }}
                    </h2>
                    <ul class="space-y-3 pl-6">
                        <li v-for="(rule, ruleIndex) in section.rules" :key="ruleIndex" 
                            class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1.5">
                                <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                            </div>
                            <p class="text-gray-700">{{ rule }}</p>
                        </li>
                        <div v-if="section.subRules" class="pl-6 space-y-2 mt-2">
                            <div v-for="(subRule, subIndex) in section.subRules" :key="subIndex"
                                 class="flex items-start space-x-3">
                                <span class="text-gray-600">-</span>
                                <p class="text-gray-700">{{ subRule }}</p>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>

            <!-- Agreement Footer -->
            <div class="p-6 bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10">
                <p class="text-gray-700 text-center font-medium">
                    Dengan menyewa di Campigo, penyewa dianggap telah membaca, memahami, dan menyetujui semua ketentuan yang berlaku.
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Rules',
    data() {
        return {
            rules: [
                {
                    title: 'Pengambilan dan Pengembalian Barang',
                    rules: [
                        'Penyewa wajib mengambil barang sesuai dengan tanggal pemesanan dan mengembalikan barang sesuai dengan tanggal pengembalian.',
                        'Keterlambatan pengembalian dianggap sebagai perpanjangan sewa dan dikenakan biaya tambahan sesuai tarif harian.'
                    ]
                },
                {
                    title: 'Perawatan dan Penggunaan Barang',
                    rules: [
                        'Semua peralatan/perlengkapan disewakan dalam kondisi baik dan utuh. Penyewa wajib merawat dan menjaga barang yang disewa dengan baik.',
                        'Jauhkan peralatan dari api, kecuali barang yang memang didesain untuk itu (misalnya kompor dan nisting).',
                        'Dilarang merokok atau memasak di dalam tenda.',
                        'Dilarang mendirikan tenda terlalu dekat dengan api unggun.',
                        'Dilarang mendirikan tenda di lokasi yang berbatu atau terdapat benda tajam lainnya (ranting patah, pecahan batu tajam, dll).',
                        'Pastikan membawa dan menggunakan barang dengan cara yang benar dan aman.'
                    ]
                },
                {
                    title: 'Operasional',
                    rules: [
                        'Jam kerja Campigo adalah pukul 05.00 – 22.00 WIB.',
                        'Campigo beralamat di Jl. Urip Sumoharjo No. 555, Jebres, Surakarta.',
                        'Jika pengembalian dilakukan di luar jam operasional, maka akan dikenakan denda atau dianggap memperpanjang sewa (tidak ada toleransi waktu).'
                    ]
                },
                {
                    title: 'Ketentuan Barang Hilang atau Rusak',
                    rules: [
                        'Barang yang hilang atau rusak akan dikenakan denda sebagai berikut:'
                    ],
                    subRules: [
                        'Kerusakan ringan: 25% dari nilai sewa harian.',
                        'Kerusakan berat: Senilai 100% dari nilai sewa harian.',
                        'Kehilangan barang: 5 kali nilai sewa harian.'
                    ]
                },
                {
                    title: 'Ketentuan Down Payment (DP)',
                    rules: [
                        'Penyewa wajib membayar uang muka (DP) sebesar 50% dari total biaya sewa pada saat melakukan pemesanan.',
                        'Sisa pembayaran dilakukan saat pengembalian barang, bersamaan dengan pembayaran denda jika terdapat kerusakan atau kehilangan.',
                        'Pembayaran yang saat ini dilayani adalah transfer bank dan pembayaran tunai.',
                        'Setelah pembayaran DP terkonfirmasi, penyewa harus segera mengambil barang yang disewa.',
                        'Jika penyewa tidak mengambil barang sampai melewati jadwal kembali, maka DP dianggap hangus dan penyewaan dianggap batal.'
                    ]
                },
                {
                    title: 'Jaminan Penyewaan',
                    rules: [
                        'Pengambilan barang sewa harus disertai dengan menyerahkan jaminan identitas asli berupa KTP (atau identitas lain yang sah).',
                        'Penyerahan jaminan disertai dengan mengunggah foto KTP sebagai penanda transaksi.',
                        'Jaminan identitas asli dapat diambil kembali setelah semua barang yang disewa dikembalikan, pembayaran sisa biaya sewa dilunasi, dan denda (jika ada) diselesaikan.',
                        'Campigo menjamin kerahasiaan data pribadi penyewa, termasuk foto KTP, dan tidak akan menyalahgunakannya. Data akan dihapus setelah penyewaan selesai dan seluruh kewajiban penyewa terpenuhi.'
                    ],
                    
                },
                {
                    title: 'Ketentuan Lain',
                    rules: [
                        'Pelanggan dilarang menitipkan barang bawaan apapun di lokasi persewaan.',
                        'Penyewa bertanggung jawab sepenuhnya atas barang yang disewa.',
                        'Ketentuan di atas dapat berubah sewaktu-waktu sesuai dengan situasi dan kondisi yang berlaku.'
                    ]
                },
            ]
        }
    }
}
</script>