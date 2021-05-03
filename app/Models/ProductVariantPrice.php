<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantPrice extends Model
{
    protected $fillable = ['product_variant_one', 'product_variant_two', 'product_variant_three', 'price', 'stock', 'product_id'];

    public function variantOne() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one');
    }

    public function variantTwo() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two');
    }

    public function variantThree() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three');
    }
}
