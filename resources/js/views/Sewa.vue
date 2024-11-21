<template>
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight mb-8">Sewaan Saya</h1>
        
        <!-- Status Filters -->
        <div class="flex flex-wrap gap-3 mb-8 border-b pb-6">
            <button
                v-for="status in availableStatuses"
                :key="status"
                @click="toggleStatusFilter(status)"
                :class="{
                    'text-white bg-gradient-to-r from-teal-600 via-green-600 to-blue-600': activeStatus === status,
                    'text-teal-600 bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10': activeStatus !== status
                }"
                class="px-3 py-1 text-sm font-medium rounded-full transition-all duration-200 hover:scale-105"
            >
                {{ status }}
            </button>
        </div>

        <div v-if="loading" class="text-center p-8 flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-teal-600 border-t-transparent mx-auto"></div>
            <div class="pt-2 text-teal-600">Memuat...</div>
        </div>
        
        <div v-else-if="filteredTransactions.length === 0" class="text-center p-8 bg-gray-50 rounded-lg">
            <p class="text-gray-600">Tidak ada transaksi yang sesuai filter.</p>
        </div>
        
        <div v-else class="grid grid-cols-1 gap-6">
            <div v-for="transaction in filteredTransactions" :key="transaction.id" 
                 class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900">Transaksi #{{ transaction.id }}</h3>
                        <span 
                            class="px-3 py-1 text-sm font-medium rounded-full"
                            :class="{
                                'bg-gray-100 text-gray-800': transaction.status === 'belum bayar',
                                'bg-yellow-100 text-yellow-800': transaction.status === 'pending',
                                'bg-green-100 text-green-800': transaction.status === 'pembayaran terkonfirmasi',
                                'bg-teal-100 text-teal-800': transaction.status === 'berlangsung',
                                'bg-blue-100 text-blue-800': transaction.status === 'selesai',
                                'bg-red-100 text-red-800': transaction.status === 'dibatalkan',
                                
                                
                            }"
                        >
                            {{ transaction.status }}
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

                    <!-- Upload Payment Button -->
                    <div v-if="transaction.status === 'belum bayar'" class="border-t border-gray-200 pt-4">
                        <button 
                            @click="openUploadModal(transaction)"
                            class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                        >
                            Upload Bukti Pembayaran
                        </button>
                    </div>

                    <!-- Pickup Information and Button -->
                    <div v-if="transaction.status === 'pembayaran terkonfirmasi'" class="border-t border-gray-200 pt-4">
                        <div class="bg-gradient-to-r from-teal-600/10 via-green-600/10 to-blue-600/10 p-4 rounded-lg mb-4">
                            <h4 class="text-teal-800 font-medium mb-2">Informasi Pengambilan:</h4>
                            <p class="text-teal-600">Silakan ambil barang di toko kami dengan menunjukkan ID transaksi: #{{ transaction.id }}</p>
                            <p class="text-teal-600 mt-1">Alamat: Jl. Telekomunikasi No. 1, Terusan Buah Batu, Bandung</p>
                        </div>
                        <button 
                            @click="openWebcam(transaction)"
                            class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                        >
                            Ambil Barang
                        </button>
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

        <!-- Payment Proof Upload Modal -->
        <PaymentProofModal
            :show="showUploadModal"
            :transaction="selectedTransaction"
            @close="closeUploadModal"
            @success="fetchTransactions"
        />

        <!-- Add WebcamModal -->
        <WebcamModal
            :show="showWebcamModal"
            @close="closeWebcamModal"
            @captured="handleCapturedImage"
        />
    </div>
</template>

<script>
import axios from 'axios';
import ProductDetail from '../components/ProductDetail.vue';
import ProductDetailModal from '../components/ProductDetailModal.vue';
import PaymentProofModal from '../components/PaymentProofModal.vue';
import WebcamModal from '../components/WebcamModal.vue';

export default {
    name: 'Sewa',
    components: {
        ProductDetail,
        ProductDetailModal,
        PaymentProofModal,
        WebcamModal
    },
    data() {
        return {
            transactions: [],
            filteredTransactions: [],
            activeStatus: '', // Changed from array to string
            availableStatuses: [
                'belum bayar',
                'pending',
                'pembayaran terkonfirmasi',
                'berlangsung',
                'selesai',
                'dibatalkan',
                
            ],
            loading: true,
            openTransactions: [], // Add this to track open dropdowns
            selectedProduct: null,
            showUploadModal: false,
            selectedTransaction: null,
            showWebcamModal: false,
            currentTransaction: null
        }
    },
    watch: {
        activeStatus: {
            handler() {
                this.filterTransactions();
            }
        },
        transactions: {
            handler() {
                this.filterTransactions();
            },
            immediate: true
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
        },
        openUploadModal(transaction) {
            this.selectedTransaction = transaction;
            this.showUploadModal = true;
        },
        closeUploadModal() {
            this.showUploadModal = false;
            this.selectedTransaction = null;
        },
        openWebcam(transaction) {
            this.currentTransaction = transaction;
            this.showWebcamModal = true;
        },
        closeWebcamModal() {
            this.showWebcamModal = false;
            this.currentTransaction = null;
        },
        async updateStatus(transactionId, newStatus) {
            try {
                await this.closeWebcamModal();
                await axios.put(`/api/transaction/${transactionId}/update-status`, {
                    status: newStatus
                });
                await this.fetchTransactions();
            } catch (error) {
                console.error('Error updating transaction status:', error);
            }
        },
        async handleCapturedImage(imageData) {
            try {
                // First upload the KTP image
                await axios.post('/api/upload-ktp', {
                    ktp_image: imageData,
                    transaction_id: this.currentTransaction.id
                });

                // Then update the status
                await this.updateStatus(this.currentTransaction.id, 'berlangsung');
                
                // Finally close the modal
                this.closeWebcamModal();
            } catch (error) {
                console.error('Error processing captured image:', error);
                // You might want to show an error message to the user here
            }
        },
        toggleStatusFilter(status) {
            this.activeStatus = this.activeStatus === status ? '' : status;
        },
        filterTransactions() {
            if (!this.activeStatus) {
                this.filteredTransactions = this.transactions;
            } else {
                this.filteredTransactions = this.transactions.filter(transaction => 
                    transaction.status === this.activeStatus
                );
            }
        }
    }
}
</script>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}
</style>