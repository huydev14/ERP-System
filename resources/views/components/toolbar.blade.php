{{-- Component: Toolbar --}}
@props(['target' => null, 'btnId' => ''])

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
            <x-icon-delete />
        </button>
        <button class="tw-text-gray-500 hover:tw-text-gray-900 tw-transition-colors">
            <x-icon-download />
        </button>
        <button class="tw-text-gray-500 hover:tw-text-gray-900 tw-transition-colors" onclick="table.ajax.reload()">
            <x-icon-sync />
        </button>
    </div>

    <x-create-button :btnId="$btnId" :target="$target" />
</div>
