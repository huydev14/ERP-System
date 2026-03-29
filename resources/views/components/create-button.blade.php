@props([
    'title' => 'Create',
    'target' => null,
    'btnId' => '',
])

<button id="{{ $btnId }}" type="button" data-slideover-target="{{ $target }}"
    class="toolbar-btn-primary tw-flex tw-items-center tw-gap-8 tw-duration-100 tw-transition-all tw-font-medium tw-text-sm tw-border-none tw-cursor-pointer">
    <span>{{ $title }}</span>
    <i class="fas fa-plus tw-text-xs"></i>
</button>
