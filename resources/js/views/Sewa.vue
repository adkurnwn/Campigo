<template>
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight mb-8">Sewaan Saya</h1>
        
        <div v-if="loading" class="text-center p-8">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-teal-600 border-t-transparent mx-auto"></div>
        </div>
        
        <div v-else-if="transactions.length === 0" class="text-center p-8 bg-gray-50 rounded-lg">
            <p class="text-gray-600">Belum ada transaksi sewa.</p>
        </div>
        
        <div v-else class="grid grid-cols-1 gap-6">
            <div v-for="transaction in transactions" :key="transaction.id" 
                 class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900">Transaksi #{{ transaction.id }}</h3>
                        <span :class="[
                            'px-3 py-1 rounded-full text-sm font-medium',
                            getStatusClass(transaction.status)
                        ]">
                            {{ transaction.status || 'Pending' }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6 space-y-6">
                    <!-- Dates and Payment Info -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Tanggal Pinjam:</span>
                                <span class="px-3 py-1 text-sm font-medium text-teal-600 bg-teal-100 rounded-full">
                                    {{ formatDate(transaction.tgl_pinjam) }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">Tanggal Kembali:</span>
                                <span class="px-3 py-1 text-sm font-medium text-teal-600 bg-teal-100 rounded-full">
                                    {{ formatDate(transaction.tgl_kembali) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="inline-block bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 rounded-lg px-4 py-2">
                                <p class="text-xl font-semibold text-teal-600">
                                    Rp {{ formatPrice(transaction.total_harga) }}
                                </p>
                                <p v-if="transaction.dp_amount" class="text-sm text-gray-600">
                                    DP: Rp {{ formatPrice(transaction.dp_amount) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Items List -->
                    <div class="border-t border-gray-200 pt-4">
                        <button 
                            @click="toggleItems(transaction.id)"
                            class="w-full flex justify-between items-center py-2 text-gray-900 hover:text-teal-600 transition-colors duration-200"
                        >
                            <h4 class="font-medium">Items yang Disewa</h4>
                            <svg 
                                class="w-5 h-5 transform transition-transform duration-200"
                                :class="{ 'rotate-180': openTransactions.includes(transaction.id) }"
                                xmlns="http://www.w3.org/2000/svg" 
                                viewBox="0 0 20 20" 
                                fill="currentColor"
                            >
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div 
                            v-show="openTransactions.includes(transaction.id)"
                            class="space-y-3 mt-4 transition-all duration-200"
                        >
                            <div v-for="(item, index) in transaction.items" 
                                 :key="index"
                                 class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer"
                                 @click="showProductDetail(item.barang_id)"
                            >
                                <!-- Item Image -->
                                <div class="w-16 h-16 flex-shrink-0 overflow-hidden rounded-lg">
                                    <img 
                                        :src="`/storage/${item.image}`" 
                                        :alt="item.name"
                                        class="w-full h-full object-cover"
                                    />
                                </div>

                                <!-- Item Details -->
                                <div class="flex-grow">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900">{{ item.name }}</span>
                                        <span class="text-sm text-gray-500 italic">{{ item.merk }}</span>
                                    </div>
                                    <div class="flex items-center mt-1 text-sm text-gray-600">
                                        <span class="bg-teal-100 text-teal-600 px-2 py-0.5 rounded-full">
                                            {{ item.quantity }}x
                                        </span>
                                        <span class="mx-2">·</span>
                                        <span>Rp {{ formatPrice(item.price_per_day) }}/hari</span>
                                    </div>
                                </div>

                                <!-- Item Total -->
                                <div class="flex-shrink-0 text-right">
                                    <span class="text-teal-600 font-medium">
                                        Rp {{ formatPrice(item.subtotal) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div v-if="transaction.payment_method" class="text-sm text-gray-600 border-t border-gray-200 pt-4">
                        <span class="font-medium text-gray-900">Metode Pembayaran:</span>
                        <span class="ml-2">{{ transaction.payment_method }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Detail Modal -->
        <ProductDetailModal :show="!!selectedProduct" @close="closeModal">
            <ProductDetail 
                v-if="selectedProduct"
                :barang="selectedProduct"
                @close="closeModal"
            />
        </ProductDetailModal>
    </div>
</template>

<script>
import axios from 'axios';
import ProductDetail from '../components/ProductDetail.vue';
import ProductDetailModal from '../components/ProductDetailModal.vue';

export default {
    name: 'Sewa',
    components: {
        ProductDetail,
        ProductDetailModal
    },
    data() {
        return {
            transactions: [],
            loading: true,
            openTransactions: [], // Add this to track open dropdowns
            selectedProduct: null
        }
    },
    created() {
        this.fetchTransactions();
    },
    methods: {
        async fetchTransactions() {
            try {
                const response = await axios.get('/api/user-transactions');
                this.transactions = response.data.data;
            } catch (error) {
                console.error('Error fetching transactions:', error);
            } finally {
                this.loading = false;
            }
        },
        formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },
        formatPrice(price) {
            return price ? price.toLocaleString('id-ID') : '0';
        },
        getStatusClass(status) {
            const classes = {
                pending: 'bg-yellow-50 text-yellow-600',
                paid: 'bg-green-50 text-green-600',
                completed: 'bg-blue-50 text-blue-600',
                cancelled: 'bg-red-50 text-red-600'
            };
            return classes[status || 'pending'] || 'bg-gray-50 text-gray-600';
        },
        toggleItems(transactionId) {
            const index = this.openTransactions.indexOf(transactionId);
            if (index === -1) {
                this.openTransactions.push(transactionId);
            } else {
                this.openTransactions.splice(index, 1);
            }
        },
        async showProductDetail(barangId) {
            try {
                const response = await axios.get(`/api/barang/${barangId}`);
                this.selectedProduct = response.data.data;
            } catch (error) {
                console.error('Error fetching product details:', error);
            }
        },
        closeModal() {
            this.selectedProduct = null;
        }
    }
}
</script>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}
</style>