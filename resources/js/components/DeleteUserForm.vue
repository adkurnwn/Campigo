<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
      <p class="mt-1 text-sm text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently deleted. 
        Before deleting your account, please download any data or information that you wish to retain.
      </p>
    </header>

    <button
      @click="showModal = true"
      class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md"
    >
      Delete Account
    </button>

    <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg max-w-md">
        <h2 class="text-lg font-medium text-gray-900">
          Are you sure you want to delete your account?
        </h2>

        <p class="mt-1 text-sm text-gray-600">
          Once your account is deleted, all of its resources and data will be permanently deleted. 
          Please enter your password to confirm you would like to permanently delete your account.
        </p>

        <div class="mt-6">
          <input
            v-model="password"
            type="password"
            class="mt-1 block w-3/4 rounded-md border-gray-300 shadow-sm"
            placeholder="Password"
          />
          <span v-if="error" class="text-red-600 text-sm">{{ error }}</span>
        </div>

        <div class="mt-6 flex justify-end">
          <button
            @click="showModal = false"
            class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md mr-3"
          >
            Cancel
          </button>
          <button
            @click="deleteAccount"
            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md"
          >
            Delete Account
          </button>
        </div>
      </div>
    </div>
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
