<?php

namespace App\Models;

use App\Observers\BrandObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(BrandObserver::class)]
class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
