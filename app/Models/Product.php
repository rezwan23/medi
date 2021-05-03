<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function image() : HasOne
    {
        return $this->hasOne(ProductImage::class);
    }

    public function variantPrices() : HasMany
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function variants() : HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

}
