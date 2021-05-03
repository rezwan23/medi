<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = ['variant', 'varient_id', 'product_id'];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variantType() : BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }
}
