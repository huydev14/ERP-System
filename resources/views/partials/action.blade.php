<div aria-label="User actions">
    <a href="{{ route('users.show', $user->id) }}" class="user-action-btn tw-text-gray-500 hover:tw-text-[#0063B1] tw-transition-colors" title="Xem hồ sơ">
        <x-icon-view />
    </a>

    <button id="edit-user-btn" type="button" title="Sửa thông tin" class="user-action-btn user-action-btn--edit"
        data-edit-url="{{ route('users.edit', $user->id) }}"
        data-slideover-target="slideover-edit-user">
        <x-icon-edit />
    </button>

    <button id="delete-user-btn" type="button" title="Xóa người dùng" class="user-action-btn user-action-btn--delete"
        data-delete-url="{{ route('users.destroy', $user->id) }}"
        data-restore-url="{{ route('users.restore', $user->id) }}">
        <x-icon-delete />
    </button>
</div>
