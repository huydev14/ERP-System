<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\CustomerOTPMail;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class AuthController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api_customer');
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $exists = Customer::where('email', $request->email)->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'exists' => $exists
            ]
        ]);
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

        $customer = $this->guard()->user();
        if (!$customer->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản chưa được xác minh.',
                'require_verify' => true,
            ], 403);
        }
        return $this->responseWithToken($token);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Địa chỉ email này đã được sử dụng.',
        ]);

        $customer = Customer::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        //Create OTP and send mail
        $otp = rand(100000, 999999);

        Cache::put('otp_' . $customer->email, $otp, now()->addMinutes(10));

        Mail::to($customer->email)->send(new CustomerOTPMail($otp, $customer->fullname));

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để lấy mã OTP.',
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $email = $request->email;
        $userOtp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $email);

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP đã hết hạn. Vui lòng gửi lại mã mới.'
            ], 400);
        }

        if ($userOtp != $cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Mã OTP không chính xác.'
            ], 400);
        }

        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin khách hàng.'
            ], 404);
        }

        //Update customer verified status
        $customer->update([
            'email_verified_at' => now()
        ]);

        Cache::forget('otp_' . $email);

        return response()->json([
            'success' => true,
            'message' => 'Xác thực email thành công! Bây giờ bạn có thể đăng nhập.'
        ]);
    }

    public function resendOTP(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Email không tồn tại.'], 404);
        }
        $otp = rand(100000, 999999);
        Cache::put('otp_' . $customer->email, $otp, now()->addMinutes(10));
        Mail::to($customer->email)->send(new CustomerOTPMail($otp, $customer->fullname));
        return response()->json([
            'success' => true,
            'message' => 'Mã OTP mới đã được gửi vào email của bạn.'
        ]);
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
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $this->guard()->user(),
            ]
        ])->withCookie(cookie('refresh_token', $token, config('jwt.refresh_ttl')));
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');
        try {
            if (!$refreshToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phiên làm việc'
                ], 401);
            }

            $newToken = $this->guard()->setToken($refreshToken)->refresh();

            return $this->responseWithToken($newToken);

        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã bị thu hồi'
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập không hợp lệ hoặc đã hết hạn'
            ], 401);
        }
    }
}
