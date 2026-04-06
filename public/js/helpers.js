const loadingHtml = `
    <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-h-full tw-min-h-[300px] tw-text-gray-500">
        <i class="fas fa-spinner fa-spin tw-text-4xl tw-text-[#0063B1] tw-mb-4"></i>
        <p class="tw-text-sm">Đang tải dữ liệu...</p>
    </div>
`;

function renderOptions(selector, items) {
    let $selector = $(selector);
    if (!items) items = [];

    // Reset select
    $selector.find('option:not([value=""])').remove();
    $selector.val('');

    let html = '';
    items.forEach((item) => {
        html += `<option value="${item.id}">${item.text}</option>`;
    });
    $selector.append(html);
}

window.ajaxFormRequest = function (formSelector, dataTableInstance = null, reloadPage = false) {
    $(document).on('submit', formSelector, function (e) {
        e.preventDefault();

        let form = $(this);
        let url = form.attr('action');
        let formData = new FormData(this);
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin tw-mr-2"></i> Đang xử lý...');

        form.find('.field-error').remove();
        form.find('.tw-border-red-500').removeClass('tw-border-red-500').addClass('tw-border-gray-300');

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function (res,textStatus, xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (res.success) {
                    let container = form.closest('.slideover-container');
                    if (container.length && typeof window.closeSlideover === 'function') {
                        window.closeSlideover(container[0]);
                    }

                    if (dataTableInstance) {
                        dataTableInstance.ajax.reload(null, false);
                        form[0].reset();
                    }

                    if (reloadPage) {
                        setTimeout(() => window.location.reload(), 1000);
                    }

                    fluentToast({
                        type: 'success',
                        title: 'Thành công',
                        description: res.msg || 'Dữ liệu đã được lưu vào hệ thống',
                        subtitle: 'Code: ' + xhr.status,
                        actionType: 'close',
                    });
                }
            },
            error: function (xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                // Validate failed
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        let input = form.find(`[name="${field}"]`);
                        if (input.length) {
                            let wrapper = input.closest('.tw-flex-col');
                            input.closest('.tw-relative').removeClass('tw-border-gray-300').addClass('tw-border-red-500');
                            wrapper.append(
                                `<span class="field-error tw-block tw-text-red-500 tw-text-xs tw-mt-1 tw-font-medium">${messages[0]}</span>`,
                            );
                        }
                    });

                    fluentToast({
                        type: 'error',
                        title: 'Xử lý thất bại',
                        description: 'Hãy kiểm tra lại các trường thông tin.',
                        subtitle: 'Mã lỗi: ' + xhr.status,
                        actionType: 'close',
                    });
                } else {
                    fluentToast({
                        type: 'error',
                        title: 'Lỗi hệ thống',
                        description: xhr.responseJSON?.msg || 'Đã có lỗi xảy ra, vui lòng thử lại!',
                        subtitle: 'Mã lỗi: ' + xhr.status,
                        actionType: 'close',
                    });
                }
            },
        });
    });
};
