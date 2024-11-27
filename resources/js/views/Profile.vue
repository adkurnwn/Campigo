<template>
  <div class="bg-gradient-to-b from-teal-100 to-white">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 shadow sm:rounded-lg">
          <div class="max-w-xl">
            <UpdateProfileInformation />
          </div>
        </div>

        <div class="p-4 sm:p-8 bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 shadow sm:rounded-lg">
          <div class="max-w-xl">
            <UpdatePassword />
          </div>
        </div>

        <div class="p-4 sm:p-8 bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 shadow sm:rounded-lg">
          <div class="max-w-xl">
            <DeleteUserForm />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import UpdateProfileInformation from '../components/UpdateProfileInformation.vue'
import UpdatePassword from '../components/UpdatePassword.vue'
import DeleteUserForm from '../components/DeleteUserForm.vue'

export default {
  name: 'Profile',
  components: {
    UpdateProfileInformation,
    UpdatePassword,
    DeleteUserForm
  }
}
</script>