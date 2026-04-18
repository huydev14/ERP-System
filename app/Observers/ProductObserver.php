<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        AuditLogService::log(
            "Tạo mới sản phẩm: $product->name (ID: $product->id)",
            $product,
            'product'
        );
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $newData = $product->getChanges();
        $oldData = Arr::only($product->getOriginal(), array_keys($newData));

        $properties = [
            'old' => $oldData,
            'attributes' => $newData,
        ];

        AuditLogService::log(
            "Cập nhật sản phẩm: $product->name (ID: $product->id)",
            $product,
            'product',
            Auth::user(),
            $properties
        );
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        AuditLogService::log(
            "Xóa sản phẩm: $product->name (ID: $product->id)",
            $product,
            'product'
        );
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        AuditLogService::log(
            "Khôi phục sản phẩm: $product->name (ID: $product->id)",
            $product,
            'product',
            Auth::user(),
        );
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        AuditLogService::log(
            "Xóa vĩnh viễn sản phẩm: $product->name (ID: $product->id)",
            $product,
            'product',
            Auth::user(),
        );
    }
}
