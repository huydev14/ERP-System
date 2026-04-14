<form id="form-create-brand" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data" class="tw-flex tw-flex-col tw-h-full">
    @csrf

    <div class="tw-px-6 tw-py-4 tw-border-b tw-border-gray-100 tw-bg-white">
        <h3 class="tw-text-lg tw-font-semibold tw-text-gray-900 tw-tracking-tight">Thêm Thương hiệu mới</h3>
        <p class="tw-text-sm tw-text-gray-500 tw-mt-0.5">Vui lòng điền các thông tin cơ bản dưới đây.</p>
    </div>

    <div class="tw-px-6 tw-py-5 tw-bg-white tw-overflow-y-auto tw-flex-1 tw-space-y-5">

        <div>
            <label for="name" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">
                Tên thương hiệu <span class="tw-text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" required placeholder="VD: Apple, Samsung, Nike..."
                class="tw-w-full tw-rounded-md tw-border-gray-300 tw-shadow-sm focus:tw-border-[#0078D4] focus:tw-ring-[#0078D4] tw-text-sm tw-px-3 tw-py-2 tw-transition-colors tw-outline-none">
        </div>

        <div>
            <label for="website" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">Website</label>
            <div class="tw-relative tw-rounded-md tw-shadow-sm">
                <div class="tw-absolute tw-inset-y-0 tw-left-0 tw-pl-3 tw-flex tw-items-center tw-pointer-events-none">
                    <i class="fas fa-globe tw-text-gray-400"></i>
                </div>
                <input type="url" name="website" id="website" placeholder="https://example.com"
                    class="tw-w-full tw-rounded-md tw-border-gray-300 tw-pl-9 focus:tw-border-[#0078D4] focus:tw-ring-[#0078D4] tw-text-sm tw-px-3 tw-py-2 tw-transition-colors tw-outline-none">
            </div>
        </div>

        <div>
            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1.5">Logo thương hiệu</label>
            <div class="tw-flex tw-items-center tw-gap-4 tw-p-3 tw-border tw-border-dashed tw-border-gray-300 tw-rounded-md tw-bg-gray-50 hover:tw-bg-gray-100 tw-transition-colors">
                <div class="tw-w-14 tw-h-14 tw-shrink-0 tw-rounded tw-border tw-border-gray-200 tw-bg-white tw-flex tw-items-center tw-justify-center tw-overflow-hidden">
                    <img id="logo-preview" src="" alt="Preview" class="tw-w-full tw-h-full tw-object-contain tw-hidden">
                    <i id="logo-placeholder" class="fas fa-image tw-text-gray-300 tw-text-xl"></i>
                </div>
                <div class="tw-flex-1">
                    <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/webp" class="tw-block tw-w-full tw-text-sm tw-text-gray-500 file:tw-mr-4 file:tw-py-1.5 file:tw-px-4 file:tw-rounded-sm file:tw-border-0 file:tw-text-sm file:tw-font-medium file:tw-bg-[#0078D4] file:tw-text-white hover:file:tw-bg-[#106ebe] file:tw-cursor-pointer tw-cursor-pointer tw-transition-colors" onchange="previewBrandLogo(this)">
                    <p class="tw-mt-1.5 tw-text-[11px] tw-text-gray-500">Định dạng hỗ trợ: PNG, JPG, WEBP. Tối đa 2MB.</p>
                </div>
            </div>
        </div>

        <div class="tw-flex tw-items-center tw-justify-between tw-pt-2">
            <div>
                <label for="is_active" class="tw-text-sm tw-font-medium tw-text-gray-800">Trạng thái hoạt động</label>
                <p class="tw-text-xs tw-text-gray-500 tw-mt-0.5">Cho phép hiển thị thương hiệu này trên hệ thống</p>
            </div>
            <label class="tw-relative tw-inline-flex tw-items-center tw-cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="tw-sr-only peer">
                <div class="tw-w-10 tw-h-5 tw-bg-gray-300 peer-focus:tw-outline-none tw-rounded-full peer peer-checked:after:tw-translate-x-full peer-checked:after:tw-border-white after:tw-content-[''] after:tw-absolute after:tw-top-[2px] after:tw-left-[2px] after:tw-bg-white after:tw-border-gray-300 after:tw-border after:tw-rounded-full after:tw-h-4 after:tw-w-4 after:tw-transition-all peer-checked:tw-bg-[#0078D4]"></div>
            </label>
        </div>
    </div>
</form>
