<div class="tw-flex-1 tw-min-h-0 tw-overflow-y-auto tw-p-6 tw-flex tw-flex-col tw-gap-5">

    <div class="tw-space-y-4">
        <h3 class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">
            Thông tin cá nhân
        </h3>
        {{-- Name --}}
        <x-input :value="$user->name" id="name" name="name" label="Họ và tên" icon="far fa-user"
            placeholder="Nhập họ và tên" required />
        {{-- Email --}}
        <x-input :value="$user->email" type="email" id="email" name="email" label="Email"
            icon="far fa-envelope" placeholder="email@congty.com" required />
        {{-- Password --}}
        <x-input type="password" id="password" name="password" label="Mật khẩu" icon="fas fa-lock" :placeholder="$user->id ? 'Bỏ trống nếu không đổi' : 'Tạo mật khẩu đăng nhập'"
            :required="!$user->id" />

        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
            {{-- Phone number --}}
            <x-input :value="$user->phone" type="tel" id="phone" name="phone" label="Số điện thoại"
                icon="fas fa-phone-alt" placeholder="09xx..." />
            {{-- Birthday --}}
            <x-input :value="$user->birthday" type="date" id="birthday" name="birthday" label="Ngày sinh" />
        </div>

        <x-input :value="$user->address" id="address" name="address" label="Địa chỉ" icon="fas fa-map-marker-alt"
            placeholder="Nhập địa chỉ hiện tại" />

        <div class="tw-flex tw-flex-col tw-gap-1">
            {{-- Gender --}}
            <label class="fluent-label">Giới tính</label>
            <div class="tw-flex tw-gap-4 tw-pt-1">
                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer group">
                    <input type="radio" name="gender" value="0" class="tw-peer tw-sr-only" checked>
                    <div
                        class="tw-w-4 tw-h-4 tw-rounded-full tw-border tw-border-gray-500 peer-checked:tw-border-[#0063B1] peer-checked:tw-border-[5px] tw-transition-all">
                    </div>
                    <span class="tw-text-sm tw-text-gray-800">Nam</span>
                </label>
                <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer group">
                    <input type="radio" name="gender" value="1" class="tw-peer tw-sr-only">
                    <div
                        class="tw-w-4 tw-h-4 tw-rounded-full tw-border tw-border-gray-500 peer-checked:tw-border-[#0063B1] peer-checked:tw-border-[5px] tw-transition-all">
                    </div>
                    <span class="tw-text-sm tw-text-gray-800">Nữ</span>
                </label>
            </div>
        </div>
    </div>

    <hr class="tw-border-gray-200">

    <div class="tw-space-y-4">
        <h3 class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">
            Thông tin công việc
        </h3>
        <x-select title="Phòng ban" id="create-department" name="department_id" required />
        <x-select title="Đội nhóm" id="create-team" name="team_id" />
        <x-select title="Loại tài khoản" id="create-user-role" name="role_id" required />

        <div class="tw-grid tw-grid-cols-2 tw-gap-4"></div>

        <div class="tw-grid tw-grid-cols-2 tw-gap-4">
            <x-input :value="$user->start_date" type="date" id="start_date" name="start_date"
                label="Ngày bắt đầu" />

            <div class="tw-flex tw-flex-col tw-gap-1">
                <label class="fluent-label">Hình thức</label>
                <div class="tw-flex tw-gap-4 tw-pt-1">
                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                        <input type="radio" name="employment_type" value="0" class="tw-peer tw-sr-only" checked>
                        <div
                            class="tw-w-4 tw-h-4 tw-rounded-full tw-border tw-border-gray-500 peer-checked:tw-border-[#0063B1] peer-checked:tw-border-[5px] tw-transition-all">
                        </div>
                        <span class="tw-text-sm tw-text-gray-800">Full-time</span>
                    </label>
                    <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                        <input type="radio" name="employment_type" value="1" class="tw-peer tw-sr-only">
                        <div
                            class="tw-w-4 tw-h-4 tw-rounded-full tw-border tw-border-gray-500 peer-checked:tw-border-[#0063B1] peer-checked:tw-border-[5px] tw-transition-all">
                        </div>
                        <span class="tw-text-sm tw-text-gray-800">Part-time</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="tw-flex tw-items-center tw-justify-end tw-gap-3 tw-px-6 tw-py-4 tw-border-t tw-border-gray-200 tw-bg-gray-50">

    <button type="button" class="close-slideover fluent-btn-cancel">Hủy bỏ</button>
    <button type="submit" class="fluent-btn-submit">{{ $user->id ? 'Lưu thay đổi' : 'Lưu nhân viên' }}</button>
</div>
