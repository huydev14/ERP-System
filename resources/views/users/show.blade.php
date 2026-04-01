@extends('layouts.main')

@section('content')
    <div class="tw-max-w-7xl tw-mx-auto tw-pb-10">
        <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
            <div class="tw-flex tw-items-center tw-gap-3">
                <a href="{{ route('users.index') }}"
                    class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-rounded-full hover:tw-bg-gray-200 tw-text-gray-600 tw-transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">Hồ sơ nhân sự</h1>
            </div>

            <div class="tw-flex tw-gap-3">
                <button type="button" class="fluent-btn-cancel edit-user-btn" data-id="{{ $user->id }}">
                    <i class="fas fa-pen tw-mr-2"></i> Chỉnh sửa
                </button>
            </div>
        </div>

        <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6">

            {{-- Left panel --}}
            <div class="lg:tw-col-span-4">
                <div class="fluent-card tw-sticky tw-top-6">
                    <div class="card-body tw-flex tw-flex-col tw-items-center tw-text-center tw-p-8">
                        {{-- Avatar  --}}
                        @php
                            $nameParts = explode(' ', trim($user->name));
                            $initials =
                                count($nameParts) > 1
                                    ? strtoupper(substr(end($nameParts), 0, 1))
                                    : strtoupper(substr($user->name, 0, 1));
                            $colors = [
                                'tw-bg-[#0078D4]',
                                'tw-bg-[#D13438]',
                                'tw-bg-[#038387]',
                                'tw-bg-[#8764B8]',
                                'tw-bg-[#498205]',
                            ];
                            $bgColor = $colors[$user->id % count($colors)];
                        @endphp
                        <div
                            class="tw-w-28 tw-h-28 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-text-white tw-text-4xl tw-font-semibold tw-shadow-md tw-mb-5 {{ $bgColor }}">
                            {{ $initials }}
                        </div>

                        <h2 class="tw-text-xl tw-font-bold tw-text-gray-900">{{ $user->name }}</h2>
                        <p class="tw-text-sm tw-text-gray-500 tw-mt-1">{{ $user->role->name ?? 'Nhân viên' }}</p>

                        <div class="tw-mt-4">
                            @if ($user->status == 1)
                                <span
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800">
                                    Đang hoạt động
                                </span>
                            @else
                                <span
                                    class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                                    Đã vô hiệu hóa
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="tw-border-t tw-border-gray-100 tw-p-6">
                        <h3 class="tw-text-xs tw-font-semibold tw-text-gray-400 tw-uppercase tw-tracking-wider tw-mb-4">Liên
                            hệ</h3>

                        <div class="tw-space-y-4">
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <i class="far fa-envelope tw-text-gray-400 tw-mt-1 tw-w-5"></i>
                                <div>
                                    <div class="tw-text-sm tw-font-medium tw-text-gray-900 break-all">{{ $user->email }}
                                    </div>
                                    <div class="tw-text-xs tw-text-gray-500">Email công việc</div>
                                </div>
                            </div>
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <i class="fas fa-phone-alt tw-text-gray-400 tw-mt-1 tw-w-5"></i>
                                <div>
                                    <div class="tw-text-sm tw-font-medium tw-text-gray-900">
                                        {{ $user->phone ?? 'Chưa cập nhật' }}</div>
                                    <div class="tw-text-xs tw-text-gray-500">Số điện thoại</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right panel --}}
            <div class="lg:tw-col-span-8">
                <div class="fluent-card tw-h-full">

                    {{-- Tab Navigation --}}
                    <div class="tw-border-b tw-border-gray-200 tw-px-6">
                        <nav class="tw--mb-px tw-flex tw-space-x-8" aria-label="Tabs">
                            <button
                                class="fluent-tab tw-border-[#0063B1] tw-text-[#0063B1] tw-whitespace-nowrap tw-py-4 tw-px-1 tw-border-b-2 tw-font-medium tw-text-sm tw-transition-colors"
                                data-target="#tab-overview">Tổng quan
                            </button>
                            <button
                                class="fluent-tab tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300 tw-whitespace-nowrap tw-py-4 tw-px-1 tw-border-b-2 tw-font-medium tw-text-sm tw-transition-colors"
                                data-target="#tab-work">
                                Công tác
                            </button>

                            <button
                                class="fluent-tab tw-border-transparent tw-text-gray-500 hover:tw-text-gray-700 hover:tw-border-gray-300 tw-whitespace-nowrap tw-py-4 tw-px-1 tw-border-b-2 tw-font-medium tw-text-sm tw-transition-colors"
                                data-target="#tab-activity">
                                Activity Logs
                            </button>
                        </nav>
                    </div>

                    {{-- Tab Contents --}}
                    <div class="tw-p-6">
                        {{-- TAB Overview --}}
                        <div id="tab-overview" class="tab-pane">
                            <h3 class="tw-text-sm tw-font-semibold tw-text-[#0063B1] tw-mb-4 tw-uppercase tw-tracking-wide">
                                Thông tin cá nhân</h3>
                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6 tw-mb-8">
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Họ và tên</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">{{ $user->name }}</div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Giới tính</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                                        {{ $user->gender == 0 ? 'Nam' : ($user->gender == 1 ? 'Nữ' : '—') }}
                                    </div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Ngày sinh</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                                        {{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : '—' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Địa chỉ thường trú</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">{{ $user->address ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB Work --}}
                        <div id="tab-work" class="tab-pane tw-hidden">
                            <h3 class="tw-text-sm tw-font-semibold tw-text-[#0063B1] tw-mb-4 tw-uppercase tw-tracking-wide">
                                Chi tiết tổ chức</h3>
                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Phòng ban</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                                        {{ $user->department->name ?? '—' }}</div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Đội / Nhóm</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">{{ $user->team->name ?? '—' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Hình thức làm việc</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                                        {{ $user->employment_type == 0 ? 'Toàn thời gian (Full-time)' : 'Bán thời gian (Part-time)' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Ngày gia nhập</label>
                                    <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                                        {{ $user->start_date ? \Carbon\Carbon::parse($user->start_date)->format('d/m/Y') : '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB Activity --}}
                        <div id="tab-activity" class="tab-pane tw-hidden">
                            {{-- TODO: Add user audit logs --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.fluent-tab').on('click', function() {
                $('.fluent-tab')
                    .removeClass('tw-border-[#0063B1] tw-text-[#0063B1]')
                    .addClass('tw-border-transparent tw-text-gray-500');
                $(this)
                    .addClass('tw-border-[#0063B1] tw-text-[#0063B1]')
                    .removeClass('tw-border-transparent tw-text-gray-500');

                $('.tab-pane').addClass('tw-hidden');
                $($(this).data('target')).removeClass('tw-hidden');
            });
        });
    </script>
@endpush
