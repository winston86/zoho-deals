<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Add Zoho Deal</h2>
    
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Deal Name *</label>
        <input v-model="form.Deal_Name" type="text" required 
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Stage</label>
        <select v-model="form.Stage" class="w-full border p-2 rounded" required>
          <option value="">Select a Stage</option>
          <option v-for="stage in stageOptions" :key="stage" :value="stage">
            {{ stage }}
          </option>
        </select>
      </div>

      <button type="submit" :disabled="loading" 
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-gray-400">
        {{ loading ? 'Saving...' : 'Create Deal' }}
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

const stageOptions = [
  'Qualification',
  'Needs Analysis',
  'Value Proposition',
  'Identify Decision Makers',
  'Proposal/Price Quote',
  'Negotiation/Review',
  'Closed Won',
  'Closed Lost',
  'Closed Lost to Competition'
];

const form = reactive({
  Deal_Name: '',
  Stage: ''
});

const handleSubmit = async () => {

  loading.value = true;
  message.value = '';
  
  try {
    // Note: Point this to your Laravel route
    const response = await axios.post('/add-deal', form);
    
    message.value = 'Deal created successfully!';
    isError.value = false;
    
    // Clear form
    form.Deal_Name = '';
    form.Stage = ''

    // Dispatch a custom event to the whole window
    window.dispatchEvent(new CustomEvent('deal-created', { 
        detail: response.data.item.data[0]
    }));

    
  } catch (error) {
    isError.value = true;
    message.value = error.response?.data?.message || 'Failed to create deal.';
  } finally {
    loading.value = false;
  }
};
</script>