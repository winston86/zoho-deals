import { createApp } from 'vue';
import DealsList from '@zoho/components/DealsList.vue';
import DealForm from '@zoho/components/DealForm.vue';
import AccountsList from '@zoho/components/AccountsList.vue';
import AccountForm from '@zoho/components/AccountForm.vue';
import '../css/app.css';

const init = () => {
    const el = document.getElementById('zoho-app');
    if (el) {
        const app = createApp({});
		app.component('zoho-deals-widget', DealsList);
		app.component('zoho-deal-form-widget', DealForm);
		app.component('zoho-accounts-widget', AccountsList);
		app.component('zoho-account-form-widget', AccountForm);
        app.mount(el);
        console.log('Zoho App Mounted Successfully'); // Add this to verify in console
    } else {
        console.error('Element #zoho-app not found!');
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
