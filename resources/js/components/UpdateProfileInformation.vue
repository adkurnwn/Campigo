<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
      <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
      </p>
    </header>

    <div v-if="loading" class="text-center py-4">
      Loading...
    </div>
    <form v-else @submit.prevent="updateProfile" class="mt-6 space-y-6">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          required
          autofocus
        />
        <span v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</span>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
          required
        />
        <span v-if="errors.email" class="text-red-600 text-sm">{{ errors.email }}</span>
      </div>

      <div class="flex items-center gap-4">
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md">
          Save
        </button>
        <p v-if="status" class="text-sm text-gray-600">Saved.</p>
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
      status: false,
      loading: true
    }
  },
  async created() {
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
  methods: {
    async updateProfile() {
      try {
        await axios.patch('/api/profile/update', this.form);
        this.status = true;
        this.errors = {};
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
