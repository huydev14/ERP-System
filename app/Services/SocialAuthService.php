<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        parse_str(request()->input('state'), $stateParams);
        $type = $stateParams['type'] ?? 'user';

        return DB::transaction(function () use ($provider, $socialUser, $type) {
            $socialAccount = SocialAccount::where('provider_name', $provider)
                ->where('provider_id', $socialUser->getId())
                ->first();

            if ($socialAccount) {
                $user = ($type === 'customer') ? $socialAccount->customer : $socialAccount->user;
                return [$user, $type];
            }

            if ($type === 'customer') {
                $user = Customer::firstOrCreate(
                    ['email' => $socialUser->getEmail()],
                    [
                        'fullname' => $socialUser->getName(),
                        'avatar' => $socialUser->getAvatar(),
                        'email_verified_at' => now(),
                    ]
                );
            } else {
                $user = User::firstOrCreate(
                    ['email' => $socialUser->getEmail()],
                    [
                        'name' => $socialUser->getName(),
                        'email_verified_at' => now(),
                    ]
                );
            }
            //Create social account link
            SocialAccount::updateOrCreate(
                [
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                ],
                [
                    'user_id' => ($type === 'customer') ? null : $user->id,
                    'customer_id' => ($type === 'customer') ? $user->id : null,
                ]
            );

            return [$user, $type];
        });

    }
}
