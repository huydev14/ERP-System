@extends('layouts.main')

@section('content')
    <div class="fluent-card ">
        <div class="card-header tw-bg-white tw-border-b-0">
            {{-- Toolbar --}}
            <x-toolbar dataTableInstance="auditLogTable" />

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
                    <x-filter-select id="f_logName" label="Phân hệ" />
                    <x-filter-select id="f_causer" label="Actor / Causer" />
                </div>
            </div>
        </div>

        <div class="card-body tw-pt-0">
            <table id="audit-log-table" class="display table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Phân hệ</th>
                        <th>Actor / Causer</th>
                        <th>Action / Event</th>
                        <th>Target</th>
                        <th>Session ID</th>
                        <th>Details</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                // ---- RENDER TABLE --------------------------
                window.auditLogTable = new DataTable('#audit-log-table', {
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: '{!! route('audit-logs.data') !!}',
                    },
                    order: [],
                    columns: [{
                            data: 'created_at',
                            name: 'created_at',
                        },
                        {
                            data: 'log_name',
                            name: 'log_name',
                        },
                        {
                            data: 'causer_name',
                            name: 'causer_name',
                        },
                        {
                            data: 'description',
                            name: 'description',
                        },
                        {
                            data: 'subject_id',
                            name: 'subject_id',
                        },
                        {
                            data: 'session_id',
                            name: 'session_id',
                            orderable: false,
                        },
                        {
                            data: 'details',
                            name: 'details',
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    layout: {
                        topStart: null,
                        topEnd: null,
                        bottomStart: 'pageLength',
                        bottomEnd: 'paging',
                    },
                });
                $('#custom-search-input').on('keyup', function() {
                    auditLogTable.search(this.value).draw();
                });

                // ---- FILTER PANEL TOGGLE ---------------------------
                $('#toggle-filter-btn').on('click', function() {
                    $('#filter-panel').slideToggle('fast');
                    $(this).toggleClass('tw-text-[#0f6cbd] tw-bg-blue-50 tw-rounded');

                    // Reset filter
                    $('#f_logName, #f_causer').val('').trigger(
                        'change.select2');
                    auditLogTable.ajax.reload();
                });

                // ---- RENDER OPTIONS FOR SELECT FIELDs ----------------
                $.getJSON('{!! route('audit-logs.filter_data') !!}')
                    .done(function(res) {
                        renderOptions('#f_logName', res.logNameData);
                        renderOptions('#f_causer', res.causerData)
                    })
                    .fail(function(xhr) {
                        console.error('Load error:', xhr.status)
                        console.error('Load error:', xhr.responseText)
                    });

                $(document).on('change', '#filter-panel select', function() {
                    auditLogTable.ajax.reload();
                });

                // Clear filter
                $(document).on('click', '#btn-clear-filters', function() {
                    $('#f_logName, #f_causer').val('').trigger('change.select2');
                    auditLogTable.ajax.reload();
                });

                $('#f_logName, #f_causer').select2({
                theme: 'bootstrap4',
                minimumResultsForSearch: 10,
                width: '100%',
            });
            });
        </script>
    @endpush
@endsection
