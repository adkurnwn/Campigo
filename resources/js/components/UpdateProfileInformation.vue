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
        {{ status }}
      </div>
    </Transition>

    <header class="space-y-2">
      <h2 class="text-3xl font-playfair font-bold text-gray-900 tracking-tight">Profile Information</h2>
      <p class="text-xl font-light italic text-gray-600">
        Update your account's profile information and email address.
      </p>
    </header>

    <div v-if="loading" class="flex items-center justify-center py-8">
      <div class="spinner-border animate-spin"></div>
    </div>

    <form v-else @submit.prevent="updateProfile" class="mt-6 space-y-6">
      <div class="space-y-4">
        <div>
          <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="w-full px-4 py-3 rounded-lg border-2 border-teal-100 focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
            required
            autofocus
          />
          <span v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</span>
        </div>

        <div>
          <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="w-full px-4 py-3 rounded-lg border-2 border-teal-100 focus:border-teal-300 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
            required
          />
          <span v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</span>
        </div>
      </div>

      <div class="flex items-center justify-start pt-4">
        <button 
          type="submit" 
          class="px-6 py-3 bg-teal-600 hover:bg-teal-900 text-white rounded-full font-medium transform transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center justify-center"
          :disabled="loading"
        >
          <span v-if="loading" class="spinner-border animate-spin mr-2"></span>
          {{ loading ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </form>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'UpdateProfileInformation',
  data() {
    return {
      form: {
        name: '',
        email: ''
      },
      errors: {},
      status: '',
      loading: true
    }
  },
  methods: {
    async fetchUserData() {
      try {
        const response = await axios.get('/api/user');
        this.form.name = response.data.name;
        this.form.email = response.data.email;
      } catch (error) {
        console.error('Error fetching user data:', error);
      } finally {
        this.loading = false;
      }
    },
    async updateProfile() {
      this.loading = true;
      this.errors = {};
      this.status = '';

      try {
        const response = await axios.patch('/api/profile/update', this.form);
        this.status = 'Profile updated successfully';
        // Update the form with the returned data
        this.form.name = response.data.user.name;
        this.form.email = response.data.user.email;
        
        setTimeout(() => {
          this.status = '';
        }, 2000);
      } catch (error) {
        if (error.response?.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          this.status = 'An error occurred while updating the profile';
        }
      } finally {
        this.loading = false;
      }
    }
  },
  created() {
    this.fetchUserData();
  }
}
</script>
