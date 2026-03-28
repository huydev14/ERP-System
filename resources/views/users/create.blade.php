<form id="form-create-user" method="POST" action="{{ route('users.store') }}" novalidate
    class="tw-flex tw-flex-col tw-flex-1 tw-min-h-0">
    @csrf

    <div class="tw-flex-1 tw-min-h-0 tw-overflow-y-auto tw-p-6 tw-flex tw-flex-col tw-gap-5">

        <div class="tw-space-y-4">
            <h3 class="tw-text-xs tw-font-semibold tw-text-gray-500 tw-uppercase tw-tracking-wider">
                Thông tin cá nhân
            </h3>

            <x-input id="name" name="name" label="Họ và tên" icon="far fa-user" placeholder="Nhập họ và tên"
                required />
            <x-input type="email" id="email" name="email" label="Email" icon="far fa-envelope"
                placeholder="email@congty.com" required />
            <x-input type="password" id="password" name="password" label="Mật khẩu" icon="fas fa-lock"
                placeholder="Tạo mật khẩu đăng nhập" required />

            <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                <x-input type="tel" id="phone" name="phone" label="Số điện thoại" icon="fas fa-phone-alt"
                    placeholder="09xx..." />

                <x-input type="date" id="birthday" name="birthday" label="Ngày sinh" />
            </div>

            <x-input id="address" name="address" label="Địa chỉ" icon="fas fa-map-marker-alt"
                placeholder="Nhập địa chỉ hiện tại" />

            <div class="tw-flex tw-flex-col tw-gap-1">
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
                <x-input type="date" id="start_date" name="start_date" label="Ngày bắt đầu" />

                <div class="tw-flex tw-flex-col tw-gap-1">
                    <label class="fluent-label">Hình thức</label>
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

        <button type="button" class="close-slideover fluent-btn-cancel">Hủy bỏ</button>
        <button type="submit" class="fluent-btn-submit">Lưu nhân viên</button>
    </div>
</form>

@push('scripts')
    <script>
        $(function() {
            // ----- CREATE NEW USER --------------------------
            $('#form-create-user').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let formData = new FormData(this);
                let submitBtn = form.find('button[type="submit"]');
                let originalBtnText = submitBtn.html();

                submitBtn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin tw-mr-2"></i> Đang xử lý...');

                // Remove old error feedback
                form.find('.field-error').remove();
                form.find('.tw-border-red-500')
                    .removeClass('tw-border-red-500')
                    .addClass('tw-border-gray-300');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        submitBtn.prop('disabled', false).html(originalBtnText);

                        if (res.success) {
                            form[0].reset();

                            // Close slide-over
                            const container = document.getElementById('slideover-create-user');
                            if (container && typeof window.closeSlideover === 'function') {
                                window.closeSlideover(container);
                            }

                            // Reload user datatable
                            if (typeof window.table !== 'undefined') {
                                window.table.ajax.reload(null, false);
                            };

                            fluentToast({
                                type: 'success',
                                title: 'Thêm nhân viên thành công',
                                description: 'Tài khoản đã được cấp quyền truy cập vào hệ thống.',
                                subtitle: res.status,
                                actionType: 'close',
                                bottomActions: [{
                                    text: 'Xem hồ sơ',
                                    onClick: function() {
                                        // TODO: redirect to user profile
                                    }
                                }]
                            });
                        }
                    },

                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(originalBtnText);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                let input = form.find(`[name="${field}"]`);

                                if (input.length) {
                                    let wrapper = input.closest('.tw-flex-col');

                                    input.closest('.tw-relative')
                                        .removeClass('tw-border-gray-300')
                                        .addClass('tw-border-red-500');

                                    wrapper.append(`
                                        <span class="field-error tw-block tw-text-red-500 tw-text-xs tw-mt-1 tw-font-medium">
                                                ${messages[0]}
                                        </span>`);
                                }
                            });

                            fluentToast({
                                type: 'error',
                                title: 'Thêm nhân viên thất bại',
                                description: 'Hãy kiểm tra lại các trường thông tin',
                                subtitle: 'Mã lỗi: ' + xhr.status,
                                actionType: 'link',
                                actionText: 'Undo',
                                onAction: function() {
                                    console.log('Test');
                                },
                            });
                        } else {
                            fluentToast({
                                type: 'error',
                                title: 'Lỗi hệ thống',
                                description: 'Đã có lỗi hệ thống, vui lòng thử lại sau!',
                                subtitle: 'Mã lỗi: ' + xhr.status,
                                actionType: 'close',
                            });
                        }
                    }
                })
            })
        })
    </script>
@endpush
