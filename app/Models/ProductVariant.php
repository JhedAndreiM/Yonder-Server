<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
    'product_id',
    'variant_name',
    'variant_option',
    'stock',
    'critical_level',
    'lead_time',
    'safety_stock',
    'critical_mode',
    ];

    public function product()
    {
    return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
