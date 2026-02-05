@vite([
    'resources/js/app.js', 
    'vendor/winston86/zoho-deals/src/resources/js/app.js',
    'resources/css/app.css'
])
<div id="zoho-app">
    <zoho-account-form-widget></zoho-account-form-widget>
    <zoho-accounts-widget 
        :accounts="{{ json_encode($accounts) }}" 
        org-id="{{ config('zoho.org_id') }}">
    </zoho-accounts-widget>
</div>