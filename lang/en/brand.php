<?php

return [
    'filter' => [
        'title' => 'Filter',
        'brand' => 'Brand',
        'status' => 'Status',
        'placeholder' => 'Choose an option',
    ],

    'table' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'logo' => 'Logo',
        'website' => 'Website',
        'status' => 'Status',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'action' => 'Action',
        'no_logo' => 'No image',
    ],

    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'hidden' => 'Hidden',
    ],

    'action' => [
        'edit' => 'Edit',
        'delete' => 'Delete brand',
    ],

    'modal' => [
        'create_title' => 'Create new brand',
        'create_subtitle' => 'Please fill in the basic information below.',
        'edit_title' => 'Update brand',
        'edit_subtitle' => 'Edit brand information and save your changes.',
        'save_create' => 'Save brand',
        'save_edit' => 'Save changes',
    ],

    'form' => [
        'name' => 'Brand name',
        'name_placeholder' => 'Eg: Apple, Samsung, Nike...',
        'website' => 'Website',
        'website_placeholder' => 'https://example.com',
        'logo' => 'Brand logo',
        'logo_preview' => 'Logo preview',
        'logo_hint' => 'Supported formats: PNG, JPG, WEBP. Max 2MB.',
        'active_label' => 'Active status',
        'active_hint' => 'Allow this brand to be shown in the system',
    ],

    'validation' => [
        'name_required' => 'Please enter a brand name.',
        'name_unique' => 'This brand name already exists in the system.',
        'logo_max' => 'Logo image size must not exceed 2MB.',
    ],

    'messages' => [
        'create_success' => 'Brand created successfully!',
        'update_success' => 'Brand updated successfully!',
        'delete_success' => 'Brand deleted.',
        'restore_success' => 'Brand restored successfully.',
        'restore_error' => 'System error, unable to restore brand.',
        'system_error' => 'System error',
    ],

    'js' => [
        'confirm_delete' => 'Confirm deleting this brand?',
        'undo' => 'Undo',
        'save_loading' => 'Saving...',
        'code_prefix' => 'Error code:',

        'toast' => [
            'success_title' => 'Success',
            'delete_title' => 'Brand deleted',
            'delete_description' => 'The brand has been removed from the system.',
            'undo_success_title' => 'Undo successful',
            'undo_success_description' => 'The brand has been restored.',
            'restore_error_title' => 'Restore failed',
            'restore_error_description' => 'Unable to undo this action.',
            'generic_error_title' => 'Something went wrong!',
            'generic_error_description' => 'Please try again later',
            'process_failed_title' => 'Process failed',
            'process_failed_description' => 'Invalid data. Please check your inputs.',
            'system_error_title' => 'System error',
            'system_error_description' => 'A system error has occurred!',
        ],
    ],
];
