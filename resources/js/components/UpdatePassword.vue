<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
      <p class="mt-1 text-sm text-gray-600">
        Ensure your account is using a long, random password to stay secure.
      </p>
    </header>

    <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
      <div>
        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
        <input
          id="current_password"
          v-model="form.current_password"
          type="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        />
        <span v-if="errors.current_password" class="text-red-600 text-sm">{{ errors.current_password }}</span>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        />
        <span v-if="errors.password" class="text-red-600 text-sm">{{ errors.password }}</span>
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        />
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
