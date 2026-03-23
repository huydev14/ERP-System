@props([
    'title' => 'Create',
])

<button
    class="toolbar-btn-primary tw-flex tw-items-center tw-gap-8 tw-bg-blue-600 hover:tw-bg-blue-700 tw-text-white tw-font-medium tw-text-sm tw-transition-colors tw-shadow-sm">
    <span>{{ $title }}</span>
    <i class="fas fa-plus tw-text-xs"></i>
</button>
