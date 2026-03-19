<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Log;
use App\Services\JwtService;

class JwtGuest
{
    public function __construct(private JwtService $jwtService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->cookie('access_token');

        if($accessToken) {
            try {
                $this->jwtService->validate($accessToken);
                return redirect()->back();

            } catch (\Exception $e){
                Log::error('Token error'. $e->getMessage());
            }
        }

        return $next($request);
    }
}
