@vite([
    'resources/js/app.js', 
    'vendor/winston86/zoho-deals/src/resources/js/app.js',
    'resources/css/app.css'
])
<div id="zoho-app">
    <zoho-deal-form-widget></zoho-deal-form-widget>
    <zoho-deals-widget 
        :deals="{{ json_encode($deals) }}" 
        org-id="{{ config('zoho.org_id') }}">
    </zoho-deals-widget>
</div>