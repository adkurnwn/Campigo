<template>
    <div v-if="show" class="fixed inset-0 z-[9999] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="$emit('close')">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ capturedImage ? 'Foto Jaminan KTP' : 'Ambil Foto KTP Sebagai Bukti Jaminan' }}
                            </h3>
                            <div class="mt-2">
                                <div class="relative bg-gray-100 rounded-lg overflow-hidden">
                                    <!-- Error message -->
                                    <div v-if="error" class="absolute inset-0 flex items-center justify-center bg-gray-900/80">
                                        <div class="text-center p-4">
                                            <p class="text-white mb-4">{{ error }}</p>
                                            <button 
                                                @click="retryCamera"
                                                class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700"
                                            >
                                                Coba Lagi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Loading state -->
                                    <div v-else-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-gray-900/80">
                                        <div class="text-white">
                                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-teal-600 border-t-transparent"></div>
                                            <p class="mt-2">Memuat kamera...</p>
                                        </div>
                                    </div>

                                    <!-- Camera view -->
                                    <video 
                                        v-show="!capturedImage && !error"
                                        ref="video" 
                                        class="w-full h-[400px] object-cover rounded-lg"
                                        autoplay 
                                        playsinline
                                    ></video>
                                    <img 
                                        v-if="capturedImage"
                                        :src="capturedImage"
                                        class="w-full h-[400px] object-cover rounded-lg"
                                        alt="Captured ID"
                                    />
                                    <div v-if="!capturedImage" class="absolute inset-0 border-2 border-dashed border-white/50 m-4 rounded-lg">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <p class="text-white text-sm bg-black/50 px-4 py-2 rounded-full">
                                                Posisikan KTP di dalam area ini
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <template v-if="!capturedImage">
                        <button 
                            @click="capture"
                            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            Ambil Foto
                        </button>
                    </template>
                    <template v-else>
                        <button 
                            @click="$emit('captured', capturedImage)"
                            class="w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Selesai
                        </button>
                        <button 
                            @click="retake"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            Ambil Ulang
                        </button>
                    </template>
                    <button 
                        @click="handleClose"
                        class="mt-3 w-full inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'WebcamModal',
    props: {
        show: Boolean
    },
    data() {
        return {
            stream: null,
            capturedImage: null,
            error: null,
            isLoading: false,
            hasRequestedAccess: false
        }
    },
    mounted() {
        // Check if browser supports getUserMedia
        if (!navigator.mediaDevices?.getUserMedia) {
            this.error = 'Browser Anda tidak mendukung akses kamera';
        }
    },
    watch: {
        show(newVal) {
            if (newVal) {
                this.requestCameraAccess();
            } else {
                this.stopCamera();
            }
        }
    },
    methods: {
        async requestCameraAccess() {
            if (this.hasRequestedAccess) return;
            
            try {
                this.isLoading = true;
                this.error = null;
                
                // First check if we have permissions
                const permissions = await navigator.permissions.query({ name: 'camera' });
                
                if (permissions.state === 'denied') {
                    throw new Error('camera_blocked');
                }

                // Request camera access
                await navigator.mediaDevices.getUserMedia({ video: true });
                this.hasRequestedAccess = true;
                await this.startCamera();
                
            } catch (error) {
                console.error('Camera permission error:', error);
                if (error.message === 'camera_blocked') {
                    this.error = 'Akses kamera diblokir. Mohon izinkan akses kamera di pengaturan browser Anda.';
                } else {
                    this.error = this.getCameraErrorMessage(error);
                }
            } finally {
                this.isLoading = false;
            }
        },

        async startCamera() {
            if (!this.hasRequestedAccess) {
                await this.requestCameraAccess();
                return;
            }

            this.error = null;
            this.isLoading = true;

            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                if (videoDevices.length === 0) {
                    throw new Error('Tidak ada kamera yang terdeteksi');
                }

                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user', // Use front camera on mobile devices
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    }
                });

                if (!this.$refs.video) return;
                
                this.$refs.video.srcObject = this.stream;
                await this.$refs.video.play();

            } catch (error) {
                console.error('Camera error:', error);
                this.error = this.getCameraErrorMessage(error);
            } finally {
                this.isLoading = false;
            }
        },

        getCameraErrorMessage(error) {
            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                return 'Akses kamera ditolak. Mohon ikuti langkah-langkah berikut:\n\n' +
                       '1. Klik ikon kunci/kamera di address bar browser\n' +
                       '2. Pilih "Izinkan" untuk akses kamera\n' +
                       '3. Refresh halaman ini';
            } else if (error.name === 'NotFoundError') {
                return 'Kamera tidak ditemukan. Pastikan:\n\n' +
                       '1. Perangkat Anda memiliki kamera\n' +
                       '2. Kamera tidak sedang digunakan aplikasi lain\n' +
                       '3. Perangkat terhubung dengan benar';
            } else if (error.name === 'NotReadableError') {
                return 'Kamera sedang digunakan aplikasi lain.\n\n' +
                       'Tutup aplikasi lain yang mungkin menggunakan kamera\n' +
                       '(seperti Zoom, Teams, atau tab browser lain)';
            }
            return 'Terjadi kesalahan saat mengakses kamera. Silakan:\n\n' +
                   '1. Refresh halaman ini\n' +
                   '2. Coba browser lain (Chrome/Firefox)\n' +
                   '3. Periksa izin kamera di pengaturan browser';
        },

        retryCamera() {
            this.hasRequestedAccess = false;
            this.requestCameraAccess();
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
            this.capturedImage = null;
            this.error = null;
            this.isLoading = false;
            this.hasRequestedAccess = false; // Reset the access flag when stopping camera
        },

        handleClose() {
            this.stopCamera(); // This will also reset hasRequestedAccess
            this.$emit('close');
        },

        capture() {
            const canvas = document.createElement('canvas');
            canvas.width = this.$refs.video.videoWidth;
            canvas.height = this.$refs.video.videoHeight;
            const ctx = canvas.getContext('2d');
            
            // Apply mirror effect if needed
            if (this.$refs.video.style.transform.includes('scaleX(-1)')) {
                ctx.scale(-1, 1);
                ctx.translate(-canvas.width, 0);
            }
            
            ctx.drawImage(this.$refs.video, 0, 0);
            this.capturedImage = canvas.toDataURL('image/png');
        },

        retake() {
            this.capturedImage = null;
        }
    },
    beforeUnmount() {
        this.stopCamera();
    }
}
</script>

<style scoped>
video {
    transform: scaleX(-1); /* Mirror the camera view */
}
.error-message {
    white-space: pre-line;
    text-align: left;
}
</style>