<template>
  <section class="p-6 space-y-6">
    <header class="space-y-2">
      <h2 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight">Delete Account</h2>
      <p class="text-xl font-light italic text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently deleted. 
        Before deleting your account, please download any data or information that you wish to retain.
      </p>
    </header>

    <button
      @click="showModal = true"
      class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
    >
      Delete Account
    </button>

    <!-- Modal -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="showModal" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-2xl max-w-md w-full m-4 shadow-xl transform transition-all">
          <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">
            Are you sure you want to delete your account?
          </h2>

          <p class="text-base text-gray-600 mb-6">
            Once your account is deleted, all of its resources and data will be permanently deleted. 
            Please enter your password to confirm you would like to permanently delete your account.
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
                Cancel
              </button>
              <button
                @click="deleteAccount"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg"
              >
                Delete Account
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
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
        this.error = 'Password is required';
        return;
      }
      try {
        await axios.delete('/api/profile/delete', {
          data: { password: this.password }
        });
        window.location.href = '/login';
      } catch (error) {
        if (error.response?.status === 422) {
          this.error = error.response.data.errors.password?.[0] || 'Invalid password';
        }
      }
    }
  }
}
</script>
