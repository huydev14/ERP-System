$(function () {
    // ---- RENDER TABLE --------------------------
    globalThis.brandTable = new DataTable('#brandTable', {
        processing: true,
        serverSide: true,
        autoWidth: false,
        order: [[3, 'desc']],
        ajax: {
            url: '/brands/data',
            data: function (d) {
                d.status = $('#f_brandName').val() || '';
                d.department_id = $('#f_isActive').val() || '';
            },
        },
        columns: [
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'slug',
                name: 'slug',
            },
            {
                data: 'logo',
                name: 'logo',
            },
            {
                data: 'website',
                name: 'website',
            },
            {
                data: 'is_active',
                name: 'is_active',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
            {
                data: 'updated_at',
                name: 'updated_at',
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'tw-text-center',
            },
        ],
        createdRow: function (row, data) {
            let url = '/brands/show/' + data.id;

            $(row)
                .css('cursor', 'pointer')
                .on('click', function (e) {
                    if ($(e.target).closest('button').length > 0) {
                        return;
                    }
                    globalThis.location.href = url;
                });
        },

        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: 'pageLength',
            bottomEnd: 'paging',
        },
    });
    $('#custom-search-input').on('keyup', function () {
        brandTable.search(this.value).draw();
    });

    // ---- FILTER PANEL TOGGLE ---------------------------
    $('#toggle-filter-btn, #close-filter-btn').on('click', function () {
        $('#filter-panel').slideToggle('fast');

        // Reset filter
        $('#f_brandName, #f_isActive').val('').trigger('change.select2');
        brandTable.ajax.reload();
    });

    $(document).on('change', '#filter-panel select', function () {
        brandTable.ajax.reload();
    });

    // ---- RENDER OPTIONS FOR SELECT FIELDs ----------------
    $.getJSON('/brands/filter-data')
        .done(function (res) {
            renderOptions('#f_brandName', res.brandName);
            renderOptions('#f_isActive', res.isActive);
        })
        .fail(function (xhr) {
            console.error('Load error:', xhr.status);
            console.error('Load error:', xhr.responseText);
        });

    // ---- Delete brand ------------------------
    $(document).on('click', '#delete-brand-btn', function () {
        let $btn = $(this);
        let deleteUrl = $btn.data('delete-url');
        let restoreUrl = $btn.data('restore-url');

        if (!confirm('Confirm delete?')) {
            return;
        }

        $btn.prop('disabled', true);

        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            success: function (res) {
                brandTable.ajax.reload(null, false);
                fluentToast({
                    type: 'info',
                    title: 'Đã xóa thương hiệu',
                    description: 'Thương hiệu đã chuyển vào thùng rác.',
                    subtitle: res.status,
                    actionType: 'close',
                    bottomActions: [
                        {
                            text: 'Hoàn tác',

                            // Restore soft-deleted brand
                            onClick: function () {
                                $.ajax({
                                    type: 'POST',
                                    url: '/brands/restore' + data.id,
                                    success: function (res) {
                                        brandTable.ajax.reload(null, false);

                                        fluentToast({
                                            type: 'success',
                                            title: 'Hoàn tác thành công',
                                            description: 'Tài khoản nhân viên đã được khôi phục hoạt động.',
                                            actionType: 'close',
                                        });
                                    },
                                    error: function (xhr) {
                                        fluentToast({
                                            type: 'error',
                                            title: 'Lỗi khôi phục',
                                            description: 'Không thể hoàn tác thao tác này.',
                                            subtitle: 'Mã lỗi: ' + xhr.status,
                                        });
                                        console.error('Load error:', xhr.status);
                                        console.error('Load error:', xhr.responseText);
                                    },
                                });
                            },
                        },
                    ],
                });
            },
            error: function (xhr) {
                fluentToast({
                    type: 'error',
                    title: 'Đã xảy ra lỗi!',
                    description: 'Hãy thử lại sau',
                    subtitle: 'Mã lỗi: ' + xhr.status,
                    actionType: 'close',
                });
                console.error('Load error:', xhr.status);
                console.error('Load error:', xhr.responseText);
            },
            complete: function () {
                $btn.prop('disabled', false);
            },
        });
    });

    $(document).on('click', '#create-brand', function () {
        ModalHelper.open('modal');
        $('#create-brand-content').html(loadingHtml);

        $.get('/brands/create', function (html) {
            $('#create-brand-content').html(html);
        }).fail(function (xhr) {
            $('#create-brand-content').html(loadingHtml);
            console.error('Load create modal error:', xhr.status);
            console.error('Load create modal error:', xhr.responseText);
        });
    });
});
