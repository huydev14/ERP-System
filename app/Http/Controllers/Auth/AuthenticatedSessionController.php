<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private JwtService $jwtService) {}

    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->authenticate();

        $accessToken  = $this->jwtService->generateAccessToken($user);
        $refreshToken = $this->jwtService->generateRefreshToken($user);

        $user->update(['refresh_token' => $refreshToken]);

        // Save refresh token to DB
        $user->refresh_token = $refreshToken;
        $user->save();

        return redirect()->intended(route('dashboard', absolute: false))
            ->withCookies([
                cookie('access_token', $accessToken, config('jwt.ttl')),
                cookie('refresh_token', $refreshToken, config('jwt.refresh_ttl'))
            ]);
    }

    /**
     * Logout user
     * Blacklist token and remove cookies
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $user = Auth::user();
            $accessToken = $request->cookie('access_token');

            if ($accessToken) {
                JWTAuth::setToken($accessToken)->invalidate();
            }
            if ($user) {
                $user->update(['refresh_token' => null]);
            }
        } catch (\Exception $e) {
            Log::error("Logout error: " . $e->getMessage());
        }

        return redirect()->route('login')
            ->withCookies([
                cookie()->forget('access_token'),
                cookie()->forget('refresh_token')
            ])
            ->with(['status' => 'Đăng xuất thành công']);
    }
}
