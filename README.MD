1. After Laravel installation add this repo in composer.json and run `composer update`
2. `npm update` 
3. Copy env.example from current folder to laravel root dir (if needed)
4. IN .env file last empty ZOHO_REFRESH_TOKEN
5. Set ZOHO_AUTH_CODE with provided by ZOHO code 
6. Set other env settings
7. `php artisan key:generate`
8. `php artisan migrate`
9. Setup server
10. Try to access `/deals` and `/accounts` in browser