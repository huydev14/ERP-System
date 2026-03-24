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

            <div class="tw-flex tw-flex-col tw-gap-1">
                <label class="fluent-label">Phòng ban</label>
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

            <div class="tw-flex tw-flex-col tw-gap-1">
                <label class="fluent-label">Đội nhóm</label>
                <div
                    class="tw-relative tw-flex tw-items-center tw-bg-white tw-border tw-border-gray-300 tw-border-b-gray-400 tw-rounded-[4px] tw-overflow-hidden hover:tw-border-gray-400 focus-within:tw-border-gray-300 after:tw-content-[''] after:tw-absolute after:tw-bottom-0 after:tw-left-0 after:tw-right-0 after:tw-h-[2px] after:tw-bg-[#0063B1] after:tw-scale-x-0 focus-within:after:tw-scale-x-100 after:tw-transition-transform after:tw-duration-200 after:tw-origin-center">
                    <select name="team_id"
                        class="tw-w-full tw-py-1.5 tw-px-2.5 tw-text-sm tw-text-gray-900 tw-bg-transparent tw-border-none tw-outline-none focus:tw-ring-0 tw-appearance-none">
                        <option value="">Chọn đội nhóm</option>
                    </select>
                    <i
                        class="fas fa-chevron-down tw-absolute tw-right-3 tw-text-gray-500 tw-text-xs tw-pointer-events-none"></i>
                </div>
            </div>

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
            // ----- GET DATA FOR SELECT FIELDs ----------------------
            let departmentSelect = $('#form-create-user select[name="department_id"]');
            let teamSelect = $('#form-create-user select[name="team_id"]');

            $.getJSON('{{ route('users.filter_data') }}')
                .done(function(res) {
                    renderOptions(departmentSelect, res.department_data);
                })
                .fail(function(xhr) {
                    console.error('Load error:', xhr.status)
                    console.error('Load error:', xhr.responseText)
                })

            // Team data change on department id selected
            departmentSelect.on('change', function() {
                let departmentId = $(this).val();

                $.getJSON('{{ route('users.filter_data') }}', {
                        department_id: departmentId
                    })
                    .done(function(res) {
                        renderOptions(teamSelect, res.team_data)
                    });
            })

            // Render <option> helper
            function renderOptions(selector, data) {
                selector.find('option:not([value=""])').remove();
                selector.val('');

                let html = ""
                data.forEach(item => {
                    html += `<option value="${item.id}">${item.text}</option>`;
                });
                selector.append(html);
            }

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
                // Remove red border of error feedback
                form.find('.tw-border-red-500')
                    .removeClass('tw-border-red-500')
                    .addClass('tw-border-gray-300');

                // Send ajax request
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        submitBtn.prop('disabled', false).html(originalBtnText);

                        if (res.status === 'success') {
                            form[0].reset();

                            // Close slide-over
                            const container = document.getElementById('slideover-create-user');
                            if (container && typeof window.closeSlideover === 'function') {
                                window.closeSlideover(container);
                            }

                            // Reload user datatable
                            if (typeof window.table !== 'undefined') {
                                window.table.ajax.reload(null, false);
                            }

                            alert(res.message);
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

                                    wrapper.append(
                                        `<span class="field-error tw-block tw-text-red-500 tw-text-xs tw-mt-1 tw-font-medium">
                                            ${messages[0]}
                                        </span>`
                                    );
                                }
                            });
                        } else {
                            alert('Đã xảy ra lỗi hệ thống. Vui lòng thử lại!');
                        }
                    }
                })
            })
        })
    </script>
@endpush
