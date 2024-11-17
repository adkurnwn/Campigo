<template>
  <section class="p-6">
    <!-- Toast Notification -->
    <Transition
      enter-active-class="transform transition duration-300 ease-out"
      enter-from-class="translate-x-full opacity-0"
      enter-to-class="translate-x-0 opacity-100"
      leave-active-class="transform transition duration-300 ease-in"
      leave-from-class="translate-x-0 opacity-100"
      leave-to-class="translate-x-full opacity-0"
    >
      <div v-if="status" 
           class="fixed top-20 right-6 bg-gradient-to-r from-teal-600/80 via-green-600/80 to-blue-600/80 backdrop-blur-sm text-white px-6 py-3 rounded-lg shadow-lg z-50">
        Password updated successfully
      </div>
    </Transition>

    <header class="space-y-2">
      <h2 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight">Update Password</h2>
      <p class="text-xl font-light italic text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <div class="space-y-4">
        <div>
          <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password</label>
          <input
            id="current_password"
            v-model="form.current_password"
            type="password"
            class="w-full px-4 py-3 rounded-lg border-2 border-teal-100 focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
          />
          <span v-if="errors.current_password" class="mt-1 text-sm text-red-600">{{ errors.current_password }}</span>
        </div>

        <div>
          <label for="password" class="block text-gray-700 font-medium mb-2">New Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="w-full px-4 py-3 rounded-lg border-2 border-teal-100 focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
          />
          <span v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</span>
        </div>

        <div>
          <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirm Password</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="w-full px-4 py-3 rounded-lg border-2 border-teal-100 focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
          />
        </div>
      </div>

      <div class="flex items-center justify-start pt-4">
        <button 
          type="submit" 
          class="px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
        >
          Save Changes
        </button>
      </div>
    </form>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UpdatePassword',
  data() {
    return {
      form: {
        current_password: '',
        password: '',
        password_confirmation: ''
      },
      errors: {},
      status: false
    }
  },
  methods: {
    async updatePassword() {
      try {
        await axios.put('/api/profile/password', this.form);
        this.status = true;
        this.errors = {};
        this.form = {
          current_password: '',
          password: '',
          password_confirmation: ''
        };
        setTimeout(() => {
          this.status = false;
        }, 2000);
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        }
      }
    }
  }
}
</script>
