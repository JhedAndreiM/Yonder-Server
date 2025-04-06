<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'supplier_type',
        'mode_of_transaction',
        'condition',
        'price',
        'college',
        'for',
    ];
}
