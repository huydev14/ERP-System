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
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Địa chỉ thường
                trú</label>
            <div class="tw-text-sm tw-text-gray-900 tw-font-medium">{{ $user->address ?? '—' }}
            </div>
        </div>
    </div>

    <h3 class="tw-text-sm tw-font-semibold tw-text-[#0063B1] tw-mb-4 tw-uppercase tw-tracking-wide">
        Thông tin công việc</h3>
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6">
        <div>
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Phòng ban</label>
            <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                {{ $user->department->name ?? '—' }}</div>
        </div>
        <div>
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Đội / Nhóm</label>
            <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                {{ $user->team->name ?? '—' }}
            </div>
        </div>
        <div>
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Chức vụ</label>
            <div class="tw-text-sm tw-text-gray-900 tw-font-medium">
                {{ $user->position->name ?? '—' }}
            </div>
        </div>
        <div>
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Hình thức làm
                việc</label>
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

        <div>
            <label class="tw-block tw-text-xs tw-text-gray-500 tw-mb-1">Ngày nghỉ việc</label>
            @if ($user->status === 1)
                <div class="tw-text-sm tw-text-red-600 tw-font-medium">
                    {{ $user->start_date ? \Carbon\Carbon::parse($user->start_date)->format('d/m/Y') : '—' }}
                </div>
            @endif
        </div>
    </div>


</div>
