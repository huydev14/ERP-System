@extends('layouts.main')

@section('content')
    <div class="fluent-card ">
        <div class="card-header tw-bg-white tw-border-b-0">

            {{-- Toolbar --}}
            <x-toolbar dataTableInstance="brandTable">
                <x-create-button btnId="create-brand" />
            </x-toolbar>

            <div id="filter-panel" class="tw-py-3">

                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                    <h4 class="tw-text-base tw-font-bold tw-text-gray-800">Filter</h4>
                    <button id="close-filter-btn" class="tw-text-gray-400 hover:tw-text-gray-700 tw-transition-colors">
                        <i class="fas fa-times tw-text-lg"></i>
                    </button>
                </div>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-x-8 tw-gap-y-4">
                    <x-filter-select id="f_brandName" label="Thương hiệu" />
                    <x-filter-select id="f_isActive" label="Trạng thái" />
                </div>
            </div>
        </div>

        <div class="card-body tw-pt-0">
            <table id="brandTable" class="display table table-hover text-nowrap" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Logo</th>
                        <th>website</th>
                        <th>Status</th>
                        <th>Ngày tạo</th>
                        <th>Cập nhật</th>
                        <th>
                            <div class="tw-text-center">Tác vụ</div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <x-modal>
        <div id="create-brand-content"></div>
    </x-modal>

    @push('scripts')
        <script src="{{ asset('js/pages/brand.js') }}"></script>
    @endpush
@endsection
