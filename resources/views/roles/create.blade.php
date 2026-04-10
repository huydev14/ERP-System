@extends('layouts.main')

@section('content')
    <div class="tw-min-h-screen tw-bg-gray-50/50 tw-py-8">
        <div class="tw-max-w-5xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">

            <div class="tw-mb-6 tw-flex tw-items-center tw-justify-between">
                <div>
                    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-tracking-tight">
                        Tạo vai trò mới
                    </h2>
                    <p class="tw-text-sm tw-text-gray-500 tw-mt-1">
                        Thiết lập thông tin và cấp quyền chi tiết cho vai trò này.
                    </p>
                </div>
            </div>

            <form action="{{ route('roles.store') }}" method="POST" id="role-form">
                @csrf

                <div class="tw-space-y-6">
                    <div class="tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm tw-overflow-hidden">
                        <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 tw-bg-gray-50/50">
                            <h3 class="tw-text-base tw-font-semibold tw-text-gray-900 tw-flex tw-items-center tw-gap-2">
                                Thông tin vai trò
                            </h3>
                        </div>
                        <div class="tw-p-6">
                            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                                <div>
                                    <label for="name"
                                        class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">Tên vai trò
                                        <span class="tw-text-red-500">*</span></label>
                                    <input type="text" name="name" id="name"
                                        placeholder="VD: Quản trị viên, Nhân sự..." required
                                        class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-blue-500 focus:tw-ring-blue-500 tw-text-sm tw-py-2 tw-px-3 tw-transition-colors">
                                </div>

                                <div>
                                    <label for="guard_name"
                                        class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">Description</label>
                                    <input type="text" name="description" id="description"
                                        placeholder="VD: Nhập mô tả cho vai trò..."
                                        class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm tw-text-sm tw-py-2 tw-px-3 tw-cursor-not-allowed focus:tw-outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tw-bg-white tw-rounded-b-xl tw-border tw-border-gray-200 tw-shadow-sm tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-200 tw-bg-gray-50/50 tw-flex tw-justify-between tw-items-center">
                            <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">
                                Danh sách Quyền hạn (Permissions)
                            </h3>
                            <button type="button" id="btn-check-all-global"
                                class="tw-text-sm tw-font-medium tw-text-[#0078D4] hover:tw-text-[#106ebe] tw-transition-colors">
                                Chọn tất cả toàn hệ thống
                            </button>
                        </div>

                        <div class="tw-p-0 tw-flex tw-flex-col">

                            {{-- ----- USERS --------- --}}
                            <div class="permission-group tw-bg-white">
                                <div
                                    class="accordion-header tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-cursor-pointer tw-transition-colors">
                                    <div class="tw-flex tw-items-center tw-gap-4">
                                        <div class="tw-w-5 tw-flex tw-justify-center tw-shrink-0">
                                            <i
                                                class="fas fa-chevron-right tw-text-gray-500 tw-text-sm tw-transition-transform tw-duration-200 accordion-icon"></i>
                                        </div>
                                        <div>
                                            <span class="tw-font-bold tw-text-gray-900 tw-text-sm tw-block">
                                                Quản lý tài khoản
                                            </span>
                                            <span class="tw-text-xs tw-text-gray-500 tw-font-normal tw-mt-0.5 tw-block">
                                                Xem, thêm, sửa, xóa và khóa tài khoản
                                            </span>
                                        </div>
                                    </div>

                                    <div onclick="event.stopPropagation()">
                                        <x-checkbox name="check_all" label="Chọn tất cả"
                                            class="tw-px-3 tw-py-1.5 tw-bg-white tw-border tw-border-gray-200 tw-rounded tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors" />
                                    </div>
                                </div>

                                <div class="accordion-body tw-hidden tw-flex tw-flex-col">
                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xem người dùng" name="permissions[]" value="users.view" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Tạo mới người dùng" name="permissions[]" value="users.create" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Chỉnh sửa người dùng" name="permissions[]" value="users.edit" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xóa người dùng" name="permissions[]" value="users.remove" />
                                    </div>
                                </div>
                            </div>

                            {{-- ----- Roles & Permissions --------- --}}
                            <div class="permission-group tw-bg-white">
                                <div
                                    class="accordion-header tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-cursor-pointer tw-transition-colors">
                                    <div class="tw-flex tw-items-center tw-gap-4">
                                        <div class="tw-w-5 tw-flex tw-justify-center tw-shrink-0">
                                            <i
                                                class="fas fa-chevron-right tw-text-gray-500 tw-text-sm tw-transition-transform tw-duration-200 accordion-icon"></i>
                                        </div>
                                        <div>
                                            <span class="tw-font-bold tw-text-gray-900 tw-text-sm tw-block">
                                                Vai trò & Phân quyền (Roles)
                                            </span>
                                            <span class="tw-text-xs tw-text-gray-500 tw-font-normal tw-mt-0.5 tw-block">
                                                Quản lý các nhóm quyền hạn trong hệ thống
                                            </span>
                                        </div>
                                    </div>

                                    <div onclick="event.stopPropagation()">
                                        <x-checkbox name="check_all" label="Chọn tất cả"
                                            class="tw-px-3 tw-py-1.5 tw-bg-white tw-border tw-border-gray-200 tw-rounded tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors" />
                                    </div>
                                </div>

                                <div class="accordion-body tw-hidden tw-flex tw-flex-col">
                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xem vai trò và phân quyền" name="permissions[]"
                                            value="roles.view" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Tạo mới vai trò và phân quyền" name="permissions[]"
                                            value="roles.create" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Chỉnh sửa vai trò và phân quyền" name="permissions[]"
                                            value="roles.edit" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xóa vai trò và phân quyền" name="permissions[]"
                                            value="roles.remove" />
                                    </div>
                                </div>
                            </div>

                            {{-- ----- AUDIT LOGS --------- --}}
                            <div class="permission-group tw-bg-white">
                                <div
                                    class="accordion-header tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-cursor-pointer tw-transition-colors">
                                    <div class="tw-flex tw-items-center tw-gap-4">
                                        <div class="tw-w-5 tw-flex tw-justify-center tw-shrink-0">
                                            <i
                                                class="fas fa-chevron-right tw-text-gray-500 tw-text-sm tw-transition-transform tw-duration-200 accordion-icon"></i>
                                        </div>
                                        <div>
                                            <span class="tw-font-bold tw-text-gray-900 tw-text-sm tw-block">
                                                Lịch sử Hoạt động (Audit Logs)
                                            </span>
                                            <span class="tw-text-xs tw-text-gray-500 tw-font-normal tw-mt-0.5 tw-block">
                                                Tra cứu lịch sử truy cập và thay đổi dữ liệu
                                            </span>
                                        </div>
                                    </div>

                                    <div onclick="event.stopPropagation()">
                                        <x-checkbox name="check_all" label="Chọn tất cả"
                                            class="tw-px-3 tw-py-1.5 tw-bg-white tw-border tw-border-gray-200 tw-rounded tw-shadow-sm hover:tw-bg-gray-50 tw-transition-colors" />
                                    </div>
                                </div>

                                <div class="accordion-body tw-hidden tw-flex tw-flex-col">

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xem danh sách logs" name="permissions[]" value="log.view" />
                                    </div>

                                    <div
                                        class="tw-flex tw-items-center tw-cursor-pointer tw-w-full tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 hover:tw-bg-gray-50 tw-transition-colors">
                                        <x-checkbox label="Xem chi tiết" name="permissions[]" value="log.detail" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div
                    class="tw-sticky tw-bottom-0 tw-z-40 tw-mt-8 tw-bg-white/80 tw-backdrop-blur-md tw-border-t tw-border-gray-200 tw-p-4 tw-rounded-t-xl tw-shadow-[0_-8px_20px_-10px_rgba(0,0,0,0.1)] tw-flex tw-justify-end tw-items-center tw-gap-2">

                    <button type="submit"
                        class="tw-min-w-[96px] tw-px-4 tw-py-1.5 tw-text-[14px] tw-font-medium tw-text-white tw-bg-[#0078D4] tw-border tw-border-transparent tw-rounded-[4px] hover:tw-bg-[#106ebe] tw-shadow-sm tw-transition-colors tw-flex tw-items-center tw-justify-center">
                        Save
                    </button>

                    <a href="{{ route('roles.index') }}"
                        class="tw-min-w-[96px] tw-px-4 tw-py-1.5 tw-text-[14px] tw-font-medium tw-text-gray-700 tw-bg-white tw-border tw-border-gray-300 tw-rounded-[4px] hover:tw-bg-gray-50 hover:tw-text-gray-900 tw-shadow-sm tw-transition-colors tw-flex tw-items-center tw-justify-center">
                        Cancel
                    </a>

                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('.accordion-header').on('click', function() {
                    let $header = $(this);
                    let $body = $header.next('.accordion-body');
                    let $icon = $header.find('.accordion-icon');

                    $body.slideToggle(250);

                    if ($icon.hasClass('tw-rotate-90')) {
                        $icon.removeClass('tw-rotate-90');
                    } else {
                        $icon.addClass('tw-rotate-90');
                    }
                });

                $('.permission-group').on('change', 'input[name="check_all"]', function() {
                    let isChecked = $(this).prop('checked');

                    $(this)
                        .closest('.permission-group')
                        .find('input[name="permissions[]"]')
                        .prop('checked', isChecked);

                    updateGlobalCheckButton();
                });

                $('.permission-group').on('change', 'input[name="permissions[]"]', function() {
                    let $group = $(this).closest('.permission-group');
                    let total = $group.find('input[name="permissions[]"]').length;
                    let checked = $group.find('input[name="permissions[]"]:checked').length;

                    $group.find('input[name="check_all"]').prop('checked', total === checked);

                    updateGlobalCheckButton();
                });

                let globalToggle = false;
                $('#btn-check-all-global').on('click', function() {
                    globalToggle = !globalToggle;

                    $('input[name="permissions[]"], input[name="check_all"]').prop('checked', globalToggle);

                    $(this).text(globalToggle ? 'Bỏ chọn tất cả' : 'Chọn tất cả toàn hệ thống');
                });

                function updateGlobalCheckButton() {
                    let totalPerms = $('input[name="permissions[]"]').length;
                    let checkedPerms = $('input[name="permissions[]"]:checked').length;

                    globalToggle = (totalPerms === checkedPerms && totalPerms > 0);
                    $('#btn-check-all-global').text(globalToggle ? 'Bỏ chọn tất cả' : 'Chọn tất cả toàn hệ thống');
                }
            })
        </script>
    @endpush
@endsection
