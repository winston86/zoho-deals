<?php

return [
    'auth_url'      => env('ZOHO_AUTH_URL', 'https://accounts.zoho.eu/oauth/v2/token'),
    'base_url'      => env('ZOHO_BASE_URL', 'https://www.zohoapis.eu/crm/v2/'),
    'org_id'        => env('ZOHO_ORG_ID'),
    'client_id'     => env('ZOHO_CLIENT_ID'),
    'client_secret' => env('ZOHO_CLIENT_SECRET'),
    'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
    'auth_code'     => env('ZOHO_AUTH_CODE'),
];