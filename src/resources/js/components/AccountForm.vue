<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Add Zoho Account</h2>
    
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Account Name *</label>
        <input v-model="form.Account_Name" type="text" required 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Website *</label>
        <input v-model="form.Website" type="url"  required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Phone *</label>
        <input v-model="form.Phone" type="phone"  required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <button type="submit" :disabled="loading" 
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-gray-400">
        {{ loading ? 'Saving...' : 'Create Account' }}
      </button>
    </form>

    <p v-if="message" :class="isError ? 'text-red-600' : 'text-green-600'" class="mt-4 text-sm font-medium">
      {{ message }}
    </p>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';

const loading = ref(false);
const message = ref('');
const isError = ref(false);

const form = reactive({
  Account_Name: '',
  Website: '',
  Phone: ''
});

const handleSubmit = async () => {

  const phoneRegex = /^\+?(\d[\d-. ]+)?(\([\d-. ]+\))?[\d-. ]+\d$/;

  if (form.Phone && !phoneRegex.test(form.Phone)) {
    message.value = "Please enter a valid phone number.";
    isError.value = true;
    return;
  }


  loading.value = true;
  message.value = '';
  
  try {
    // Note: Point this to your Laravel route
    const response = await axios.post('/add-account', form);
    
    message.value = 'Account created successfully!';
    isError.value = false;
    
    // Clear form
    form.Account_Name = '';
    form.Website = '';
    form.Phone = '';

    // Dispatch a custom event to the whole window
    window.dispatchEvent(new CustomEvent('account-created', { 
        detail: response.data.item.data[0]
    }));

    
  } catch (error) {
    isError.value = true;
    message.value = error.response?.data?.message || 'Failed to create account.';
  } finally {
    loading.value = false;
  }
};
</script>