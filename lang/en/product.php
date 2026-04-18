<?php

return [
    'filter' => [
        'title' => 'Filter',
        'product' => 'Product',
        'category' => 'Category',
        'brand' => 'Brand',
        'status' => 'Status',
        'placeholder' => 'Choose an option',
    ],

    'table' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'category' => 'Category',
        'brand' => 'Brand',
        'status' => 'Status',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'action' => 'Action',
    ],

    'status' => [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ],

    'action' => [
        'edit' => 'Edit',
        'delete' => 'Delete product',
    ],

    'modal' => [
        'create_title' => 'Create new product',
        'create_subtitle' => 'Please fill in the basic information below.',
        'edit_title' => 'Update product',
        'edit_subtitle' => 'Edit product information and save your changes.',
        'save_create' => 'Save product',
        'save_edit' => 'Save changes',
    ],

    'form' => [
        'name' => 'Product name',
        'name_placeholder' => 'Eg: iPhone 16 Pro Max',
        'description' => 'Description',
        'description_placeholder' => 'Enter product description',
        'category' => 'Category',
        'category_placeholder' => 'Select category',
        'brand' => 'Brand',
        'brand_placeholder' => 'Select brand',
        'status' => 'Status',
        'metadata' => 'Metadata (JSON)',
        'metadata_placeholder' => '{"color":"black","warranty":"12 months"}',
    ],

    'validation' => [
        'name_required' => 'Please enter a product name.',
        'name_unique' => 'This product name already exists in the system.',
        'category_required' => 'Please select a category.',
        'status_required' => 'Please select a status.',
        'metadata_json' => 'Metadata must be valid JSON format.',
    ],

    'messages' => [
        'create_success' => 'Product created successfully!',
        'update_success' => 'Product updated successfully!',
        'delete_success' => 'Product deleted.',
        'restore_success' => 'Product restored successfully.',
        'restore_error' => 'System error, unable to restore product.',
        'system_error' => 'System error',
    ],

    'js' => [
        'confirm_delete' => 'Confirm deleting this product?',
        'undo' => 'Undo',
        'save_loading' => 'Saving...',
        'code_prefix' => 'Error code:',

        'toast' => [
            'success_title' => 'Success',
            'delete_title' => 'Product deleted',
            'delete_description' => 'The product has been moved to trash.',
            'undo_success_title' => 'Undo successful',
            'undo_success_description' => 'The product has been restored.',
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
