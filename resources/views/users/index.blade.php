@extends('layouts.main')

@section('content')
    <div class="fluent-card ">
        <div class="card-header tw-bg-white tw-border-b-0">

            {{-- Toolbar --}}
            <x-toolbar target="slideover-create-user" btnId="btn-open-create" dataTableInstance="usersTable" />

            <div id="filter-panel" class="tw-hidden tw-pt-5 tw-pb-2">

                <div class="tw-flex tw-justify-between tw-items-center tw-mb-5">
                    <h4 class="tw-text-base tw-font-bold tw-text-gray-800">Filter</h4>
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <button id="btn-clear-filters" class="tw-text-sm tw-font-medium">
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

        <div class="card-body tw-pt-0">
            <table id="users-table" class="display table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Bộ phận</th>
                        <th>Trạng thái</th>
                        <th>Số điện thoại</th>
                        <th>Loại tài khoản</th>
                        <th>Ngày bắt đầu</th>
                        <th>
                            <div class="tw-text-center">Tác vụ</div>
                        </th>
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
@endsection

@push('scripts')
    <script>
        $(function() {
            // ---- RENDER TABLE --------------------------
            window.usersTable = new DataTable('#users-table', {
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
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'tw-text-center',
                    }
                ],
                createdRow: function(row, data) {
                    let url = '{{ route('users.show', ':id') }}'.replace(':id', data.id);

                    $(row).css('cursor', 'pointer').on('click', function(e) {
                        if ($(e.target).closest('button').length > 0) {
                            return;
                        }
                        window.location.href = url;
                    })
                },

                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'pageLength',
                    bottomEnd: 'paging',
                },
            });

            $('#custom-search-input').on('keyup', function() {
                usersTable.search(this.value).draw();
            });

            // ---- FILTER PANEL TOGGLE ---------------------------
            $('#toggle-filter-btn').on('click', function() {
                $('#filter-panel').slideToggle('fast');
                $(this).toggleClass('tw-text-[#0f6cbd] tw-bg-blue-50 tw-rounded');

                // Reset filter
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger(
                    'change.select2');
                usersTable.ajax.reload();
            });

            // ---- RENDER OPTIONS FOR SELECT FIELDs ----------------
            $.getJSON('{!! route('users.filter_data') !!}')
                .done(function(res) {
                    renderOptions('#f_department', res.department_data);
                    renderOptions('#f_status', res.status_data);
                    renderOptions('#f_employment_type', res.employment_type_data);
                    renderOptions('#f_role', res.role_data);
                })
                .fail(function(xhr) {
                    console.error('Load error:', xhr.status)
                    console.error('Load error:', xhr.responseText)
                });

            $(document).on('change', '#create-department, #edit-department', function() {
                let departmentId = $(this).val();
                let isCreateForm = $(this).attr('id') === 'create-department';
                let targetTeamSelector = isCreateForm ? '#create-team' : '#edit-team';

                $.getJSON('{{ route('users.teams_data') }}', {
                        department_id: departmentId
                    })
                    .done(function(res) {
                        renderOptions(targetTeamSelector, res.teams_data)
                    });
            })

            $(document).on('change', '#filter-panel select', function() {
                usersTable.ajax.reload();
            });

            $('#f_status, #f_department, #f_employment_type, #f_role').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 10,
                width: '100%',
            });

            // Clear filter
            $(document).on('click', '#btn-clear-filters', function() {
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger(
                    'change.select2');
                usersTable.ajax.reload();
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
                }
            })

            $(document).on('click', '.edit-user-btn, #edit-user-btn', function() {
                let editUrl = $(this).data('edit-url');
                openSlideover('slideover-edit-user');
                $('#content-edit').html(loadingHtml);

                $.get(editUrl, function(html) {
                    $('#content-edit').html(html);
                }).fail(function(xhr) {
                    $('#content-edit').html(loadingHtml);
                    console.error('Load edit form error:', xhr.status);
                    console.error('Load edit form error:', xhr.responseText);
                });
            });

            // --- Handle create/edit user ---------------------------
            ajaxFormRequest('#form-create-user, #form-edit-user', usersTable)

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
                        usersTable.ajax.reload(null, false);
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
                                            usersTable.ajax
                                                .reload(
                                                    null, false
                                                );

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
@endpush
