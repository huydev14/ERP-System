@extends('layouts.main')

@section('page-header')
    <x-page-header title="User Management" description="Manage user" />
@endsection

@section('content')

        <table id="users-table" class="display table table-bordered text-nowrap">
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



    <script>
        $(function() {
            let table = new DataTable('#users-table', {
                processing: true,
                serverSide: true,
                scrollX: true,
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
                language: {
                    "processing": "{{ __('datatables.processing') }}",
                    "search": "{{ __('datatables.search') }}",
                    "lengthMenu": "{{ __('datatables.lengthMenu') }}",
                    "info": "{{ __('datatables.info') }}",
                    "infoEmpty": "{{ __('datatables.infoEmpty') }}",
                    "infoFiltered": "{{ __('datatables.infoFiltered') }}",
                    "zeroRecords": "{{ __('datatables.zeroRecords') }}",
                    "loadingRecords": "{{ __('datatables.loadingRecords') }}",
                },
                layout: {
                    topStart: 'pageLength',
                    topEnd: [{
                            search: {
                                placeholder: 'Tìm kiếm nhân viên...'
                            }
                        },
                        $(`<div class="dt-toolbar d-flex">
                            <select id="f_status" class="form-select"><option value="">Trạng thái</option></select>
                            <select id="f_department" class="form-select"><option value="">Bộ phận</option></select>
                            <select id="f_team" class="form-select"><option value="">Đội nhóm</option></select>
                            <select id="f_account_type" class="form-select"><option value="">Loại tài khoản</option></select>
                            <button id="btn-clear-filters" class="btn btn-sm">Xoá lọc</button>
                        </div>`)
                    ],
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
            });

            $.getJSON('{!! route('users.filter_data') !!}')
                .done(function(res) {
                    fill('#f_status', res.status_data);
                    fill('#f_department', res.department_data);
                    fill('#f_team', res.team_data);
                    fill('#f_account_type', res.account_type_data);

                    function fill(selector, items) {
                        let element = $(selector);

                        if (!items) {
                            items = [];
                        }
                        items.forEach(item => {
                            let option = new Option(item.text, item.id);
                            element.append(option);
                        })
                    }

                    $('#f_status, #f_department, #f_team, #f_account_type').select2({
                        theme: 'bootstrap4'
                    });
                })
                .fail(function(err) {
                    console.error('Fail to get data for filters: ', err);
                })

            $(document).on('change', '.dt-toolbar select', function() {
                table.ajax.reload();
            })
            $(document).on('click', '#btn-clear-filters', function() {
                $('#f_status, #f_department, #f_team, #f_account_type').val('');
                table.ajax.reload();
            })
        });
    </script>
@endsection
