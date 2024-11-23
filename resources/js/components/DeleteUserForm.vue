<template>
  <section class="p-6 space-y-6">
    <header class="space-y-2">
      <h2 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight">Nonaktifkan Akun</h2>
      <p class="text-xl font-light italic text-gray-600">
        Setelah akun Anda dinonaktifkan, Anda tidak dapat mengakses akun anda kembali.
        Namun transaksi yang sudah dilakukan tetap akan tersimpan.
      </p>
    </header>

    <button
      @click="showModal = true"
      class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
    >
      Nonaktifkan Akun
    </button>

    <!-- Modal -->
    <Teleport to="body">
      <Transition 
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="showModal" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center">
          <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div v-show="showModal" class="fixed inset-0 transform transition-all" @click="showModal = false">
              <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm" />
            </div>
          </Transition>

          <Transition
            enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="opacity-0 translate-y-8 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-8 scale-95"
          >
            <div v-show="showModal" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto sm:max-w-lg p-8">
              <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">
                Apakah Anda yakin ingin menonaktifkan akun Anda?
              </h2>

              <p class="text-base text-gray-600 mb-6">
                Setelah akun Anda dinonaktifkan, Anda tidak dapat mengakses akun anda kembali.
                Namun transaksi yang sudah dilakukan tetap akan tersimpan.
              </p>

              <div class="space-y-4">
                <div>
                  <input
                    v-model="password"
                    type="password"
                    class="w-full px-4 py-3 rounded-lg border-2 border-red-100 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                    placeholder="Password"
                  />
                  <span v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</span>
                </div>

                <div class="flex items-center justify-end space-x-4">
                  <button
                    @click="showModal = false"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full font-medium transform transition-all duration-300"
                  >
                    Batal
                  </button>
                  <button
                    @click="deleteAccount"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
                  >
                    Nonaktifkan Akun
                  </button>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'DeleteUserForm',
  data() {
    return {
      showModal: false,
      password: '',
      error: ''
    }
  },
  methods: {
    async deleteAccount() {
      if (!this.password) {
        this.error = 'Password diperlukan';
        return;
      }
      try {
        await axios.delete('/api/profile/delete', {
          data: { password: this.password }
        });
        window.location.href = '/login';
      } catch (error) {
        if (error.response?.status === 422) {
          this.error = error.response.data.errors.password?.[0] || 'Password salah';
        }
      }
    }
  }
}
</script>
