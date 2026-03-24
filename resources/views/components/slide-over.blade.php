@props(['id', 'title'])

<div id="{{ $id }}" class="slideover-container tw-fixed tw-inset-0 tw-z-50 tw-hidden">

    <div
        class="slideover-backdrop close-slideover tw-absolute tw-inset-0 tw-bg-black/30 tw-opacity-0 tw-transition-opacity tw-duration-300">
    </div>

    <div
        class="slideover-panel tw-absolute tw-inset-y-0 tw-right-0 tw-w-full tw-max-w-md tw-bg-white tw-shadow-2xl tw-flex tw-flex-col tw-transform tw-transition-transform tw-duration-300 tw-translate-x-full">

        <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-gray-200">
            <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">{{ $title }}</h2>
            <button type="button" class="close-slideover tw-text-gray-400 hover:tw-text-gray-700 tw-transition-colors">
                <i class="fas fa-times tw-text-lg"></i>
            </button>
        </div>

        {{ $slot }}

    </div>
</div>

@once
    <script>
        $(function() {
            // Open slide-over panel
            window.openSlideover = function(id) {
                let container = $(`#${id}`);
                if (!container.length) return;

                let panel = container.find('.slideover-panel');
                let backdrop = container.find('.slideover-backdrop');

                container.removeClass('tw-hidden');

                setTimeout(() => {
                    panel.removeClass('tw-translate-x-full').addClass('tw-translate-x-0');
                    backdrop.removeClass('tw-opacity-0').addClass('tw-opacity-100');
                }, 10);
            }

            // Close slide-over panel
            window.closeSlideover = function(containerElement) {
                let container = $(containerElement);
                let panel = container.find('.slideover-panel');
                let backdrop = container.find('.slideover-backdrop');

                panel.removeClass('tw-translate-x-0').addClass('tw-translate-x-full');
                backdrop.removeClass('tw-opacity-100').addClass('tw-opacity-0');

                setTimeout(() => {
                    container.addClass('tw-hidden');
                }, 300)
            }

            // Open slide-over panel event handler
            $(document).on('click', '[data-slideover-target]', function(e) {
                e.preventDefault();
                
                let targetId = $(this).data('slideover-target');
                openSlideover(targetId)
            })

            // Close slide-over panel event handler
            $(document).on('click', '.close-slideover', function(e) {
                e.preventDefault();

                let container = $(this).closest('.slideover-container');
                if (container.length) {
                    closeSlideover(container)
                }
            })
        })
    </script>
@endonce
