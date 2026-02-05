<?php

namespace Winston86\ZohoDeals\Services;

use Illuminate\Support\Facades\Http;
use Winston86\ZohoDeals\Interfaces\ZohoApiInterface;

class ZohoApiService implements ZohoApiInterface
{
    protected string $baseUrl;
    protected string $accessToken;
    protected string $orgId;

    public function __construct()
    {
        $this->baseUrl = config('zoho.base_url');
        $this->orgId = config('zoho.org_id');
        $this->accessToken = $this->authenticate();
    }

    public function authenticate(): string
    {

        // 1. Try to get from cache
        $token = cache('zoho_access_token');

        if (!empty($token)) {
            return $token;
        }

        // 2. If empty, refresh it (Logic we discussed earlier)
        $newToken = $this->refreshAccessToken();

        // 3. Store it for 59 minutes (3540 seconds)
        cache()->put('zoho_access_token', $newToken, 3540);

        return $newToken;
    }

    protected function OAuth(): bool
    {
        $response = Http::asForm()->post(config('zoho.auth_url'), [
                'code' => config('zoho.auth_code'),
                'client_id'     => config('zoho.client_id'),
                'client_secret' => config('zoho.client_secret'),
                'grant_type'    => 'authorization_code',
            ]);
        $refresh_token = $response->json()['refresh_token'];
        $this->setEnvValue('ZOHO_REFRESH_TOKEN', $refresh_token);
        return true;
    }

    protected function setEnvValue($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            // Check if the key already exists
            if (str_contains($content, "{$key}=")) {
                // Replace the existing value
                $content = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $content
                );
            } else {
                // Append the new key to the end of the file
                $content .= "\n{$key}={$value}";
            }

            file_put_contents($path, $content);
        }
    }

    protected function refreshAccessToken(): string
    {
        if (empty(config('zoho.refresh_token'))) {
            $this->OAuth();
        }
        $response = Http::asForm()->post(config('zoho.auth_url'), [
                 'refresh_token' => config('zoho.refresh_token'),
                 'client_id'     => config('zoho.client_id'),
                 'client_secret' => config('zoho.client_secret'),
                 'grant_type'    => 'refresh_token',
             ]);
        return $response->json()['access_token'] ?? '';
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $this->accessToken,
            'orgId' => $this->orgId,
            'Content-Type' => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    public function getRecord(string $module, string $id): array
    {
        $path = $id ? "{$module}/{$id}" : $module;
        return $this->client()->get($path)->json();
    }

    public function createRecord(string $module, array $data): array
    {
        return $this->client()->post("/{$module}", $data)->json();
    }

    public function storeRecord(string $module, string $id, array $data): array
    {
        return $this->client()->put("/{$module}/{$id}", $data)->json();
    }

    public function searchRecords(string $module, string $criteria): array
    {
        $result = $this->client()->get("/{$module}/search", ['word' => $criteria])->json();
        return $result ?? [];
    }
}
