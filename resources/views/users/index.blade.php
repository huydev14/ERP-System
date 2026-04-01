@extends('layouts.main')

@section('content')
    <div class="fluent-card">
        <div class="card-header tw-bg-white tw-border-b-0">

            {{-- Toolbar --}}
            <x-toolbar target="slideover-create-user" btnId="btn-open-create" />

            <div id="filter-panel" class="tw-hidden tw-pt-5 tw-pb-2">

                <div class="tw-flex tw-justify-between tw-items-center tw-mb-5">
                    <h4 class="tw-text-base tw-font-bold tw-text-gray-800">Filter</h4>
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <button id="btn-clear-filters"
                            class="tw-text-sm tw-text-blue-600 hover:tw-text-blue-800 tw-font-medium">
                            Clear all
                        </button>
                        <button id="close-filter-btn" class="tw-text-gray-400 hover:tw-text-gray-700 tw-transition-colors">
                            <i class="fas fa-times tw-text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-x-8 tw-gap-y-6">
                    <x-filter-select id="f_department" label="Bộ phận" />
                    <x-filter-select id="f_employment_type" label="Hình thức" />
                    <x-filter-select id="f_status" label="Trạng thái" />
                    <x-filter-select id="f_role" label="Loại tài khoản" />
                </div>
            </div>
        </div>

        {{-- Users datatable --}}
        <div class="card-body tw-pt-0">
            <table id="users-table" class="display table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Bộ phận</th>
                        <th>Hình thức</th>
                        <th>Trạng thái</th>
                        <th>Số điện thoại</th>
                        <th>Loại tài khoản</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- Panel: Create user --}}
    <x-slide-over id="slideover-create-user" title="Thêm nhân viên mới">
        <div id="content-create" class="tw-flex tw-flex-col tw-flex-1 tw-min-h-0"></div>
    </x-slide-over>

    {{-- Panel: Edit user --}}
    <x-slide-over id="slideover-edit-user" title="Cập nhật thông tin nhân viên">
        <div id="content-edit" class="tw-flex tw-flex-col tw-flex-1 tw-min-h-0"></div>
    </x-slide-over>

    <script>
        $(function() {
            // ---- RENDER TABLE --------------------------
            window.table = new DataTable('#users-table', {
                processing: true,
                serverSide: true,
                autoWidth: false,
                order: [
                    [0, 'desc']
                ],
                ajax: {
                    url: '{!! route('users.data') !!}',
                    data: function(d) {
                        d.status = $('#f_status').val() || '';
                        d.department_id = $('#f_department').val() || '';
                        d.employment_type_id = $('#f_employment_type').val() || '';
                        d.role = $('#f_role').val() || '';
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'department.name',
                        name: 'department.name',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                let positionName = (row.position && row.position.name) ?
                                    row.position.name :
                                    'Chưa cập nhật';
                                let departmentName = (row.department && row.department.name) ?
                                    row.department.name :
                                    'Chưa phân bổ';

                                return `
                                    <div style="line-height: 1.4;">
                                        <div class="tw-font-medium tw-text-gray-900">${departmentName}</div>
                                        <div class="tw-text-xs tw-text-gray-500">Chức vụ: ${positionName}</div>
                                    </div>
                                `;
                            }
                            return data || '-';
                        }
                    },
                    {
                        data: 'employment_type',
                        name: 'employment_type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        defaultContent: '-',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                if (!data) {
                                    return null;
                                }
                                let badgeClass = 'tw-bg-gray-100 tw-text-gray-700';
                                if (data === 'Super Admin') badgeClass =
                                    'tw-bg-purple-100 tw-text-purple-700';
                                else if (data === 'Admin' || data === 'Manager') badgeClass =
                                    'tw-bg-blue-100 tw-text-[#0f6cbd]';

                                return `<span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-md tw-text-xs tw-font-medium ${badgeClass}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'pageLength',
                    bottomEnd: 'paging',
                },
            });

            const loadingHtml = `
                <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-h-full tw-min-h-[300px] tw-text-gray-500">
                    <i class="fas fa-spinner fa-spin tw-text-4xl tw-text-[#0063B1] tw-mb-4"></i>
                    <p class="tw-text-sm">Đang tải dữ liệu...</p>
                </div>
            `;

            $('#custom-search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // ---- FILTER PANEL TOGGLE ---------------------------
            $('#toggle-filter-btn').on('click', function() {
                $('#filter-panel').slideToggle('fast');
                $(this).toggleClass('tw-text-[#0f6cbd] tw-bg-blue-50 tw-rounded');

                // Reset filter
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger(
                    'change.select2');
                table.ajax.reload();
            });

            // ---- RENDER OPTIONS FOR SELECT FIELDs ----------------
            $.getJSON('{!! route('users.filter_data') !!}')
                .done(function(res) {
                    renderOptions('#f_department', res.department_data);
                    renderOptions('#f_status', res.status_data);
                    renderOptions('#f_employment_type', res.employment_type_data);
                    renderOptions('#f_role', res.role_data);
                    renderOptions('#create-department', res.department_data);
                    renderOptions('#create-team', res.team_data);
                    renderOptions('#create-user-role', res.role_data);

                    $('#create-department').on('change', function() {
                        let departmentId = $(this).val();

                        $.getJSON('{{ route('users.filter_data') }}', {
                                department_id: departmentId
                            })
                            .done(function(res) {
                                renderOptions('#create-team', res.team_data)
                            });
                    })

                    function renderOptions(selector, items) {
                        let $selector = $(selector);
                        if (!items) items = [];

                        // Reset select
                        $selector.find('option:not([value=""])').remove();
                        $selector.val('');

                        let html = '';
                        items.forEach(item => {
                            html += `<option value="${item.id}">${item.text}</option>`
                        })
                        $selector.append(html);
                    }

                    $('#f_status, #f_department, #f_employment_type, #f_role').select2({
                        theme: 'bootstrap4',
                        minimumResultsForSearch: 10,
                        width: '100%'
                    });
                })
                .fail(function(xhr) {
                    console.error('Load error:', xhr.status)
                    console.error('Load error:', xhr.responseText)
                });

            $(document).on('change', '#filter-panel select', function() {
                table.ajax.reload();
            });

            // Clear filter
            $(document).on('click', '#btn-clear-filters', function() {
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger(
                    'change.select2');
                table.ajax.reload();
            });

            // --- Open create user slide-over -------------------------
            let preloadedCreateHtml = null;
            setTimeout(() => {
                $.get('{{ route('users.create') }}', function(html) {
                    $('#content-create').html(html);
                    preloadedCreateHtml = html;
                });
            }, 800);

            $('#btn-open-create').on('click', function() {
                openSlideover('slideover-create-user')
                if (preloadedCreateHtml) {
                    $('#content-create').html(preloadedCreateHtml);
                } else {
                    $('#content-create').html(loadingHtml);
                    preloadedCreateHtml = html;
                    $('#content-create').html(html);
                }
            })

            $(document).on('click', '.edit-user-btn, #edit-user-btn', function() {
                let editUrl = $(this).data('edit-url');
                openSlideover('slideover-edit-user');
                $('#content-edit').html(loadingHtml);

                $.get(editUrl, function(html) {
                    $('#content-edit').html(html);

                }).fail(function(xhr) {
                    $('#content-edit').html(
                        '<div class="tw-text-red-500 tw-text-center tw-mt-10">Lỗi tải dữ liệu. Vui lòng thử lại.</div>'
                        );
                    console.error('Load edit form error:', xhr.status);
                    console.error('Load edit form error:', xhr.responseText);
                });
            });

            // Create/edit forms
            $(document).on('submit', '#form-create-user, #form-edit-user', function(e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let formData = new FormData(this);
                let submitBtn = form.find('button[type="submit"]');
                let originalBtnText = submitBtn.html();
                let isCreate = form.attr('id') === 'form-create-user';

                submitBtn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin tw-mr-2"></i> Đang xử lý...');

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
                            if (isCreate) {
                                form[0].reset();
                            }

                            let container = form.closest('.slideover-container');
                            if (container.length) {
                                window.closeSlideover(container[0]);
                            }

                            if (typeof window.table !== 'undefined') {
                                window.table.ajax.reload(null, false);
                            }

                            fluentToast({
                                type: 'success',
                                title: isCreate ? 'Thêm nhân viên thành công' :
                                    'Cập nhật nhân viên thành công',
                                description: isCreate ?
                                    'Tài khoản đã được cấp quyền truy cập vào hệ thống.' :
                                    'Thông tin nhân viên đã được cập nhật.',
                                subtitle: res.msg || '',
                                actionType: 'close',
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(originalBtnText);

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(field, messages) {
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
                                title: isCreate ? 'Thêm nhân viên thất bại' :
                                    'Cập nhật nhân viên thất bại',
                                description: 'Hãy kiểm tra lại các trường thông tin',
                                subtitle: 'Mã lỗi: ' + xhr.status,
                                actionType: 'close',
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
                });
            });

            // ---- Delete user ------------------------
            $(document).on('click', '#delete-user-btn', function() {
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
                    success: function(res) {
                        table.ajax.reload(null, false);
                        fluentToast({
                            type: 'info',
                            title: 'Đã xóa nhân viên',
                            description: 'Tài khoản nhân viên đã được chuyển vào thùng rác.',
                            subtitle: res.status,
                            actionType: 'close',
                            bottomActions: [{
                                text: 'Hoàn tác',

                                // Restore soft-deleted user
                                onClick: function() {
                                    $.ajax({
                                        type: 'POST',
                                        url: restoreUrl,
                                        success: function(res) {
                                            table.ajax.reload(
                                                null, false);

                                            fluentToast({
                                                type: 'success',
                                                title: 'Hoàn tác thành công',
                                                description: 'Tài khoản nhân viên đã được khôi phục hoạt động.',
                                                actionType: 'close',
                                            });
                                        },
                                        error: function(xhr) {
                                            fluentToast({
                                                type: 'error',
                                                title: 'Lỗi khôi phục',
                                                description: 'Không thể hoàn tác thao tác này.',
                                                subtitle: 'Mã lỗi: ' +
                                                    xhr
                                                    .status,
                                            });
                                            console.error(
                                                'Load error:',
                                                xhr.status)
                                            console.error(
                                                'Load error:',
                                                xhr
                                                .responseText
                                            )
                                        }
                                    })
                                }
                            }]
                        });
                    },
                    error: function(xhr) {
                        fluentToast({
                            type: 'error',
                            title: 'Đã xảy ra lỗi!',
                            description: 'Hãy thử lại sau',
                            subtitle: 'Mã lỗi: ' + xhr.status,
                            actionType: 'close'
                        });
                        console.error('Load error:', xhr.status)
                        console.error('Load error:', xhr.responseText)
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            })
        });
    </script>
@endsection
