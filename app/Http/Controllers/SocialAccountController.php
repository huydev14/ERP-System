<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialAccountController extends Controller
{
    public function redirect(Request $request, $provider)
    {
        $type = $request->query('type', 'user');

        return Socialite::driver($provider)
            ->with(['state' => 'type=' . $type])
            ->stateless()
            ->redirect();
    }

    public function callback(Request $request, $provider)
    {
        try {
            $socialAuthService = new \App\Services\SocialAuthService();
            [$user, $type] = $socialAuthService->handleProviderCallback($provider);

            //Login user and generate token
            $guard = ($type === 'customer') ? 'api_customer' : 'api';
            $token = auth($guard)->login($user);

            //Set refresh token in cookie
            $cookie = cookie('refresh_token', $token, config('jwt.refresh_ttl'));

            return response()
                ->view('auth.callback', compact('token', 'user'))
                ->withCookie($cookie);

        } catch (\Exception $e) {
            \Log::error('Social login error: ', ['provider' => $provider, 'error' => $e->getMessage()]);
            return view('auth.callback', [
                'error' => 'Đăng nhập thất bại: ' . $e->getMessage()
            ]);
        }
    }
}
