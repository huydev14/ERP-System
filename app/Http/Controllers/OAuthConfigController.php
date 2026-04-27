<?php

namespace App\Http\Controllers;

use App\Models\OAuthConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OAuthConfigController extends Controller
{
    public function index()
    {
        $configs = OAuthConfig::all()->keyBy('provider');

        return view('oauth-config.index', compact('configs'));
    }

    public function update(Request $request, $provider)
    {
        $validated = $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'redirect_uri' => 'required|url',
        ]);

        try {
            OAuthConfig::updateOrCreate(
                ['provider' => $provider],
                [
                    'client_id' => $validated['client_id'],
                    'client_secret' => $validated['client_secret'],
                    'redirect_uri' => $validated['redirect_uri'],
                    'is_active' => $request->boolean('is_active'),
                ]
            );

            Cache::forget("oauth_config_{$provider}");

            return redirect()->back()->with('success', "Cập nhật cấu hình " . ucfirst($provider) . " thành công.");
        } catch (\Throwable $e) {
            Log::error("Update {$provider} OAuth failed", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật cấu hình ' . ucfirst($provider) . '. Vui lòng thử lại.');
        }
    }
}
