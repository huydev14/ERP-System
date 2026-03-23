<form id="form-create-user" method="POST" class="tw-flex tw-flex-col tw-flex-1 tw-min-h-0">
    @csrf

    <div class="tw-flex-1 tw-min-h-0 tw-overflow-y-auto tw-p-6 tw-flex tw-flex-col tw-gap-5">

        <div class="tw-space-y-4">
            <h3 class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">
                Thông tin cá nhân
            </h3>

            <x-input id="name" name="name" label="Họ và tên *" icon="far fa-user" placeholder="Nhập họ và tên"
                required />
            <x-input type="email" id="email" name="email" label="Email *" icon="far fa-envelope"
                placeholder="email@congty.com" required />
            <x-input type="password" id="password" name="password" label="Mật khẩu *" icon="fas fa-lock"
                placeholder="Tạo mật khẩu đăng nhập" required />

            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                <x-input type="tel" id="phone" name="phone" label="Số điện thoại" icon="fas fa-phone-alt"
                    placeholder="09xx..." />
                <x-input type="date" id="birthday" name="birthday" label="Ngày sinh" icon="far fa-calendar-alt" />
            </div>

            <x-input id="address" name="address" label="Địa chỉ" icon="fas fa-map-marker-alt"
                placeholder="Nhập địa chỉ hiện tại" />

            <div class="tw-flex tw-flex-col tw-gap-1">
                <label class="tw-text-sm tw-font-normal tw-text-gray-700">Giới tính</label>
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
            <h3 class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">Thông tin công việc
            </h3>

            <div class="tw-flex tw-flex-col tw-gap-1">
                <label class="tw-text-sm tw-font-normal tw-text-gray-700">Phòng ban</label>
                <div
                    class="tw-relative tw-flex tw-items-center tw-bg-white tw-border tw-border-gray-300 tw-border-b-gray-400 tw-rounded-[4px] tw-overflow-hidden hover:tw-border-gray-400 focus-within:tw-border-gray-300 after:tw-content-[''] after:tw-absolute after:tw-bottom-0 after:tw-left-0 after:tw-right-0 after:tw-h-[2px] after:tw-bg-[#0063B1] after:tw-scale-x-0 focus-within:after:tw-scale-x-100 after:tw-transition-transform after:tw-duration-200 after:tw-origin-center">
                    <select name="department_id"
                        class="tw-w-full tw-py-1.5 tw-px-2.5 tw-text-sm tw-text-gray-900 tw-bg-transparent tw-border-none tw-outline-none focus:tw-ring-0 tw-appearance-none">
                        <option value="">Chọn phòng ban</option>
                    </select>
                    <i
                        class="fas fa-chevron-down tw-absolute tw-right-3 tw-text-gray-500 tw-text-xs tw-pointer-events-none"></i>
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-2 tw-gap-4"></div>

            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                <x-input type="date" id="start_date" name="start_date" label="Ngày bắt đầu"
                    icon="far fa-calendar-check" />

                <div class="tw-flex tw-flex-col tw-gap-1">
                    <label class="tw-text-sm tw-font-normal tw-text-gray-700">Hình thức</label>
                    <div class="tw-flex tw-gap-4 tw-pt-1">
                        <label class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer">
                            <input type="radio" name="employment_type" value="0" class="tw-peer tw-sr-only"
                                checked>
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

        <button type="button"
            class="close-slideover tw-px-4 tw-py-1.5 tw-rounded-[4px] tw-text-sm tw-font-semibold tw-text-gray-700 tw-border tw-border-gray-300 tw-bg-white hover:tw-bg-gray-50 active:tw-bg-gray-100 tw-transition-colors">
            Hủy bỏ
        </button>

        <button type="submit"
            class="tw-inline-flex tw-items-center tw-justify-center tw-px-4 tw-py-1.5 tw-rounded-[4px] tw-text-sm tw-font-semibold tw-text-white tw-bg-[#0063B1] tw-transition-all tw-duration-200 hover:tw-bg-[#115ea3] active:tw-bg-[#0f548c] active:tw-scale-[0.97] focus-visible:tw-outline focus-visible:tw-outline-2 focus-visible:tw-outline-offset-2 focus-visible:tw-outline-[#0063B1]">
            Lưu nhân viên
        </button>
    </div>
</form>

@push('scripts')
    <script>
        $(document).ready(function() {
            
        })
    </script>
@endpush
