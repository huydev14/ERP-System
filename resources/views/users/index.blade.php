@extends('layouts.main')

@section('page-header')
    <x-page-header title="User Management" description="Manage user" />
@endsection

@section('content')
    <div class="fluent-card tw-mt-4">
        <div class="card-header tw-bg-white tw-border-b-0">
            <div class="fluent-toolbar tw-flex tw-items-center tw-w-full tw-gap-4 tw-border-b tw-border-gray-200">
                <div class="tw-relative tw-flex-1">
                    <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-3 tw-pointer-events-none">
                        <i class="fas fa-search tw-text-gray-400"></i>
                    </div>
                    <input type="text" id="custom-search-input" placeholder="Search table..."
                        class="tw-w-full tw-pl-10 tw-pr-3 tw-py-2 tw-text-sm tw-text-gray-900 tw-bg-transparent tw-border-none">
                </div>

                <button id="toggle-filter-btn" class="tw-text-gray-500 hover:tw-text-[#0f6cbd] tw-px-1 tw-transition-colors"
                    title="Filter">
                    <x-icon-filter />
                </button>

                <div class="tw-h-6 tw-w-px tw-bg-gray-300"></div>

                <div class="tw-flex tw-items-center tw-gap-5 tw-px-2">
                    <button class="tw-text-gray-500 hover:tw-text-gray-900 tw-transition-colors">
                        <x-icon-download/>
                    </button>
                    <button class="tw-text-gray-500 hover:tw-text-gray-900 tw-transition-colors"
                        onclick="table.ajax.reload()">
                        <x-icon-sync/>
                    </button>
                    <button class="tw-text-gray-500 hover:tw-text-gray-900 tw-transition-colors">
                        <x-icon-setting/>
                    </button>
                </div>

                <x-create-button />
            </div>

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
                    <x-filter-select id="f_status" label="Trạng thái" />
                    <x-filter-select id="f_department" label="Bộ phận" />
                    <x-filter-select id="f_team" label="Đội nhóm" />
                    <x-filter-select id="f_account_type" label="Loại tài khoản" />
                </div>
            </div>
        </div>

        {{-- Users datatable --}}
        <div class="card-body tw-pt-0">
            <div class="table-responsive">
                <table id="users-table" class="display table table-hover text-nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Bộ phận</th>
                            <th>Vị trí</th>
                            <th>Đội nhóm</th>
                            <th>Loại công việc</th>
                            <th>Trạng thái</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Giới tính</th>
                            <th>Ngày sinh</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Loại tài khoản</th>
                            <th>Tác vụ</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                        d.team_id = $('#f_team').val() || '';
                        d.account_type_id = $('#f_account_type').val() || '';
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
                        defaultContent: '-'
                    },
                    {
                        data: 'position.name',
                        name: 'position.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'team.name',
                        name: 'team.name',
                        defaultContent: '-'
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
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'birthday',
                        name: 'birthday'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'account_type.name',
                        name: 'accountType.name',
                        defaultContent: '-'
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
                $('#f_status, #f_department, #f_team, #f_account_type').val('').trigger('change.select2');
                table.ajax.reload();
            });

            // Get data for select2
            $.getJSON('{!! route('users.filter_data') !!}')
                .done(function(res) {
                    fill('#f_status', res.status_data);
                    fill('#f_department', res.department_data);
                    fill('#f_team', res.team_data);
                    fill('#f_account_type', res.account_type_data);

                    function fill(selector, items) {
                        let element = $(selector);
                        if (!items) items = [];
                        items.forEach(item => {
                            element.append(new Option(item.text, item.id));
                        })
                    }

                    $('#f_status, #f_department, #f_team, #f_account_type').select2({
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
                $('#f_status, #f_department, #f_team, #f_account_type').val('').trigger('change.select2');
                table.ajax.reload();
            });
        });
    </script>
@endsection
