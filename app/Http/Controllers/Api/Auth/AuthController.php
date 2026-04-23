<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api_customer');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = $this->guard()->attempt($credentials);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác'
            ], 401);
        }
        return $this->responseWithToken($token);
    }

    public function checkEmail(Request $request){
        $request->validate([
            'email' => 'required|email',
        ]);
        $exists = DB::table('customers')->where('email', $request->email)->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'exists' => $exists
            ]
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
        ]);

        $user = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo tài khoản thành công',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $this->guard()->user()
            ]
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        $cookie = cookie()->forget('refresh_token');

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công'
        ])->withCookie($cookie);
    }

    protected function responseWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Thành công',
            'data' => [
                'access_token' => $token,
                'user' => $this->guard()->user(),
            ]
        ])->withCookie('refresh_token', $token, config('jwt.refresh_ttl'));
    }

    public function refresh(Request $request)
    {
        try {
            if (!$request->cookie('refresh_token')) {
                throw new Exception('Không tìm thấy Refresh Token trong Cookie');
            }

            $newToken = $this->guard()->setToken($refreshToken)->refresh();

            return $this->responseWithToken($newToken);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã hết hạn'
            ], 401);
        }
    }
}
