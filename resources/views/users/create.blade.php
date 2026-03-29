<form id="form-create-user" method="POST" action="{{ route('users.store') }}"
    class="tw-flex tw-flex-col tw-flex-1 tw-min-h-0" novalidate>
    @csrf
    @include('users._form')
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
