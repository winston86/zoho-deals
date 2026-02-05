1. After Laravel installation add this repo in composer.json and run `composer install`
`{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/winston86/zoho-deals"
        }
    ],
    "require": {
        "winston86/zoho-deals": "dev-main"
    }
}`
2. `npm install` 
3. Copy env.example and example.vite.config.js (or merge) from current folder to laravel root dir (if needed)
4. IN .env file leave empty ZOHO_REFRESH_TOKEN
5. Set ZOHO_AUTH_CODE with provided by ZOHO code 
6. Set other env settings
7. `php artisan key:generate`
8. `php artisan migrate`, `php artisan optimize:clear`
9. `npm install @vitejs/plugin-vue --save-dev && npm install ./vendor/winston86/zoho-deals`
10. Setup server `npm run dev` or `php artisan serve`
11. Try to access `/deals` and `/accounts` in browser