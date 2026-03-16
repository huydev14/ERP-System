<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="noti-box tw-flex tw-items-center tw-justify-between tw-bg-p tw-p-2 tw-rounded-md tw-mb-4 tw-cursor-pointer tw-opacity-100 hover:tw-opacity-90"
            role="alert">
            <p class="tw-font-medium tw-text-white tw-mr-2">{{ session('status') }}</p>
            <button type="button" class="tw-text-white tw-font-bold tw-leading-none" aria-label="Close">
                &times;
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="tw-block tw-mt-1 tw-w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="tw-mt-2" />
        </div>

        <!-- Password -->
        <div class="tw-mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="tw-block tw-mt-1 tw-w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="tw-mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="tw-block tw-mt-4">
            <label for="remember_me" class="tw-inline-flex tw-items-center">
                <input id="remember_me" type="checkbox"
                    class="tw-rounded tw-border-gray-300 tw-text-indigo-600 tw-shadow-sm focus:tw-ring-indigo-500"
                    name="remember">
                <span class="tw-ms-2 tw-text-sm tw-text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="tw-flex tw-items-center tw-justify-between tw-mt-4">
            @if (Route::has('password.request'))
                <a class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <div
                class="tw-flex tw-p-2 tw-items-center tw-gap-2 tw-text-sm tw-transition tw-duration-100 hover:!tw-bg-gray-200 tw-rounded-lg">
                @if (App::getLocale() == 'vi')
                    <a href="{{ route('lang.switch', 'en') }}">
                        <span class="fi fi-gb"></span> English
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'vi') }}">
                        <span class="fi fi-vn"></span> Tiếng Việt
                    </a>
                @endif
            </div>

            {{-- Login button --}}
            <div><x-primary-button class="tw-ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
