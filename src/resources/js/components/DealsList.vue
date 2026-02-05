<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deal Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stage</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approval State</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr v-for="deal in deals" :key="deal.id" class="hover:bg-gray-50 cursor-pointer">
          <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">
            #{{ deal.id }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-900 font-medium">
            {{ deal.Deal_Name }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-900 font-medium">
            {{ deal.Stage }}
          </td>
          <td class="px-6 py-4">
            <span :class="statusClass(deal.$approval_state)" class="px-2 py-1 rounded-full text-xs font-semibold">
              {{ deal.$approval_state }}
            </span>
          </td>
          <td>
            <div class="relative">
              <input 
                :deal-id="deal.id"
                :key="deal.id"
                v-model="deal.tempSearch" 
                @input="handleSearch(deal)" 
                @blur="deal.searchResults = []"
                placeholder="Search Account..."
                class="border p-1 w-full"
              />
              <ul v-if="deal.accs && deal.accs.length" class="absolute z-10 bg-white border w-full shadow-lg">
                <li 
                  v-for="account in deal.accs" 
                  :key="account.id"
                  @click="selectAccount(account, deal)"
                  class="p-2 hover:bg-gray-100 cursor-pointer"
                >
                  {{ account.Account_Name }}
                </li>
              </ul>
            </div>
          </td>
          <td class="px-6 py-4 text-sm text-gray-500">
            {{ formatDate(deal.Created_Time) }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import debounce from 'lodash.debounce';


const handleSearch = debounce(async (deal) => {
  if (deal.tempSearch.length < 3) return; // Don't search for tiny strings

  const response = await axios.get(`search-accounts?q=${deal.tempSearch}`);
  deal.accs = deal.accs ?? ref([]);
  deal.accs = response.data; // Should return list of {id, Account_Name}
}, 300);

const selectAccount = async (account, deal) => {
  // Store the ID to send to Zoho later
  // dealForm.Account_Name = { id: account.id };
  const response = await axios.post(`store-deal`, {'deal_id': deal.id, 'account_id': account.id}); 
  deal.tempSearch = account.Account_Name;
  deal.accs = []; // Hide dropdown
};

const props = defineProps(['deals']);
const deals = ref([...props.deals]);
deals.value = deals.value.map(deal => ({
    ...deal,
    // If the deal already has an account, show its name in the search box
    tempSearch: deal.Account_Name ? deal.Account_Name.name : '',
    accs: []
}));

const formatDate = (dateStr) => {
  return new Date(dateStr).toLocaleDateString();
};

const statusClass = (status) => {
  return status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
};

onMounted(() => {
    window.addEventListener('deal-created', (e) => {
        // Push the new deal from the event detail into your local list
        deals.value.unshift(e.detail);
    });
});

</script>