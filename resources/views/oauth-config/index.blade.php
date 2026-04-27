@extends('layouts.main')

@section('content')
<div class="tw-p-8 tw-bg-[#f3f3f3] tw-min-h-screen">
    {{-- Header --}}
    <div class="tw-mb-8">
        <h1 class="tw-text-2xl tw-font-semibold tw-text-[#201f1e]">Hệ thống OAuth</h1>
        <p class="tw-text-[#605e5d] tw-text-sm">Cấu hình định danh và kết nối API cho các dịch vụ bên thứ ba.</p>
    </div>

    @if (session('success'))
        <div class="tw-flex tw-items-center tw-p-4 tw-mb-6 tw-text-[#107c10] tw-bg-[#dff6dd] tw-rounded-sm tw-shadow-sm">
            <i class="fas fa-check-circle tw-mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="tw-flex tw-items-center tw-p-4 tw-mb-6 tw-text-[#a4262c] tw-bg-[#fed9cc] tw-rounded-sm tw-shadow-sm">
            <i class="fas fa-exclamation-circle tw-mr-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6">
        {{-- Navigation Left --}}
        <div class="lg:tw-col-span-3">
            <div class="nav tw-flex tw-flex-col fluent-tab-list tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-2" id="v-pills-tab" role="tablist">
                @php
                    $providers = [
                        ['id' => 'google', 'title' => 'Google Cloud', 'subtitle' => 'Identity Platform', 'icon' => 'fab fa-google'],
                        ['id' => 'microsoft', 'title' => 'Microsoft Teams', 'subtitle' => 'Microsoft Entra ID', 'icon' => 'fab fa-microsoft'],
                        ['id' => 'facebook', 'title' => 'Facebook Login', 'subtitle' => 'Meta for Developers', 'icon' => 'fab fa-facebook-f'],
                        ['id' => 'github', 'title' => 'GitHub OAuth', 'subtitle' => 'Source control auth', 'icon' => 'fab fa-github'],
                    ];
                @endphp

                @foreach($providers as $p)
                    <a class="nav-link {{ $loop->first ? 'active' : '' }} fluent-tab-item tw-mb-1 tw-text-left tw-flex tw-items-center tw-gap-3 tw-border-none tw-w-full tw-bg-transparent"
                        id="v-pills-{{ $p['id'] }}-tab"
                        data-bs-toggle="pill"
                        href="#v-pills-{{ $p['id'] }}"
                        role="tab"
                        aria-controls="v-pills-{{ $p['id'] }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">

                        <i class="{{ $p['icon'] }} tw-text-lg"></i>
                        <div class="tw-flex tw-flex-col">
                            <span class="tw-font-medium tw-text-sm">{{ $p['title'] }}</span>
                            <span class="tw-text-[11px] tw-opacity-70">{{ $p['subtitle'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Content Right --}}
        <div class="lg:tw-col-span-9">
            <div class="tab-content" id="v-pills-tabContent">
                @foreach($providers as $provider)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="v-pills-{{ $provider['id'] }}"
                         role="tabpanel"
                         aria-labelledby="v-pills-{{ $provider['id'] }}-tab">

                        <div class="tw-bg-white tw-rounded-lg tw-shadow-sm tw-border tw-border-[#edebe9]">
                            <div class="tw-p-6 tw-border-b tw-border-[#edebe9] tw-flex tw-justify-between tw-items-center">
                                <h5 class="tw-text-lg tw-font-semibold tw-text-[#323130] tw-mb-0">{{ $provider['title'] }} Configuration</h5>
                                <span class="tw-px-2 tw-py-1 tw-text-[11px] tw-font-medium tw-bg-[#f3f2f1] tw-text-[#605e5d] tw-rounded tw-border">ID: {{ $provider['id'] }}</span>
                            </div>

                            <div class="tw-p-8">
                                @php
                                    $providerId = $provider['id'];
                                    $currentConfig = $configs[$providerId] ?? null;
                                @endphp

                                <form method="POST" action="{{ route('oauth-configs.update', $providerId) }}" class="tw-space-y-6">
                                    @csrf
                                    @method('PATCH')

                                    <div class="tw-flex tw-items-center tw-justify-between tw-p-4 tw-bg-[#faf9f8] tw-rounded-md tw-border">
                                        <div>
                                            <div class="tw-font-medium tw-text-sm tw-text-[#323130]">Trạng thái hoạt động</div>
                                            <div class="tw-text-xs tw-text-[#605e5d]">Cho phép hoặc chặn đăng nhập qua {{ $provider['title'] }}</div>
                                        </div>
                                        <x-switch name="is_active" value="1" :checked="old('is_active', $currentConfig->is_active ?? false)" />
                                    </div>

                                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-5">
                                        <div>
                                            <x-input id="{{ $providerId }}_client_id" label="Client ID" name="client_id" icon="fas fa-key"
                                                :value="old('client_id', $currentConfig->client_id ?? '')" placeholder="..." required="true" />
                                        </div>
                                        <div>
                                            <x-input id="{{ $providerId }}_client_secret" label="Client Secret" name="client_secret" type="password" icon="fas fa-lock"
                                                :value="old('client_secret', $currentConfig->client_secret ?? '')" placeholder="••••••••••••" required="true" />
                                        </div>
                                        <div class="tw-col-span-1 md:tw-col-span-2">
                                            <x-input id="{{ $providerId }}_redirect_uri" label="Authorized Redirect URI" name="redirect_uri" type="url" icon="fas fa-link"
                                                :value="old('redirect_uri', $currentConfig->redirect_uri ?? '')" placeholder="https://..." required="true"
                                                helper="Copy URL này dán vào {{ $provider['title'] }} Console." />
                                        </div>
                                    </div>

                                    <div class="tw-pt-6 tw-border-t tw-border-[#edebe9] tw-flex tw-justify-end tw-gap-3">
                                        <button type="button" class="tw-px-4 tw-py-2 tw-text-sm tw-font-bold tw-bg-white tw-border tw-border-[#8a8886] tw-rounded hover:tw-bg-[#f3f2f1] tw-transition-colors">Hủy bỏ</button>
                                        <button type="submit" class="tw-px-4 tw-py-2 tw-text-sm tw-font-bold tw-text-white tw-bg-[#0078d4] tw-rounded tw-shadow-sm hover:tw-bg-[#106ebe] tw-transition-colors">Lưu cấu hình</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .fluent-tab-item {
        border: none !important;
        border-radius: 4px !important;
        color: #323130 !important;
        transition: background 0.15s ease;
    }
    .fluent-tab-item:hover { background-color: #f3f2f1 !important; }
    .fluent-tab-item.active { background-color: #edebe9 !important; color: #0078d4 !important; position: relative; font-weight: 600; }
    .fluent-tab-item.active::before { content: ""; position: absolute; left: 0; top: 20%; height: 60%; width: 3px; background-color: #0078d4; border-radius: 2px; }
</style>
@endsection
