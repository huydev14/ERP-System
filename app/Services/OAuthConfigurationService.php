<?php

namespace App\Services;

use App\Models\OAuthConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class OAuthConfigurationService
{
    public static function load(string $providerName)
    {
        $config = Cache::remember("oauth_config_{$providerName}", now()->addDays(7), function () use ($providerName) {
            return OAuthConfig::where('provider', $providerName)
                ->where('is_active', 1)
                ->first();
        });

        if (!$config) {
            return false;
        }
        Config::set("services.{$providerName}", [
            'client_id' => $config->client_id,
            'client_secret' => $config->client_secret,
            'redirect' => $config->redirect_uri,
        ]);

        return true;
    }
}
