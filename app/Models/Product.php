<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

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
    
    public function user()
    {
    return $this->belongsTo(User::class);
    }
}
