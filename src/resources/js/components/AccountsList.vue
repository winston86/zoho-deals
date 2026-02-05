<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Phone</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approval State</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr v-for="account in accounts" :key="account.id" class="hover:bg-gray-50 cursor-pointer">
          <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">
            #{{ account.id }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-900 font-medium">
            {{ account.Account_Name }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-900 font-medium">
            {{ account.Phone }}
          </td>
          <td class="px-6 py-4">
            <span :class="statusClass(account.$approval_state)" class="px-2 py-1 rounded-full text-xs font-semibold">
              {{ account.$approval_state }}
            </span>
          </td>
          <td class="px-6 py-4 text-sm text-gray-500">
            {{ formatDate(account.Created_Time) }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps(['accounts']);
const accounts = ref([...props.accounts]);

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString();
};

const statusClass = (status) => {
  return status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
};

onMounted(() => {
    window.addEventListener('account-created', (e) => {
        // Push the new account from the event detail into your local list
        accounts.value.unshift(e.detail);
    });
});


</script>