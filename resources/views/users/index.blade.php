@extends('layouts.main')

@section('content')
    <div class="fluent-card">
        <div class="card-header tw-bg-white tw-border-b-0">

            <x-toolbar />

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
            {{-- <div class="table-responsive"> --}}
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
                        <th><div class="tw-text-center">Tác vụ</div></th>
                    </tr>
                </thead>
            </table>
            {{-- </div> --}}
        </div>
    </div>

    <script>
        $(function() {
            window.table = new DataTable('#users-table', {
                processing: true,
                serverSide: true,
                autoWidth: false,
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
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        sortable: false,
                        searchable: false
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

            $('#custom-search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter panel toggle
            $('#toggle-filter-btn').on('click', function() {
                $('#filter-panel').slideToggle('fast');
                $(this).toggleClass('tw-text-[#0f6cbd] tw-bg-blue-50 tw-rounded');

                // Reset filter
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger('change.select2');
                table.ajax.reload();
            });

            // Get data for select2
            $.getJSON('{!! route('users.filter_data') !!}')
                .done(function(res) {
                    fill('#f_department', res.department_data);
                    fill('#f_status', res.status_data);
                    fill('#f_employment_type', res.employment_type_data);
                    fill('#f_role', res.role_data);

                    function fill(selector, items) {
                        let element = $(selector);
                        if (!items) items = [];
                        items.forEach(item => {
                            element.append(new Option(item.text, item.id));
                        })
                    }

                    $('#f_status, #f_department, #f_employment_type, #f_role').select2({
                        theme: 'bootstrap4',
                        minimumResultsForSearch: 10,
                        width: '100%'
                    });
                });

            $(document).on('change', '#filter-panel select', function() {
                table.ajax.reload();
            });

            // Clear filter
            $(document).on('click', '#btn-clear-filters', function() {
                $('#f_status, #f_department, #f_employment_type, #f_role').val('').trigger('change.select2');
                table.ajax.reload();
            });
        });
    </script>
@endsection
