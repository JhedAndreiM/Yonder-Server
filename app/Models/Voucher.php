<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $fillable = [
        'user_id',
        'seller_id',
        'amount',
        'status',
        'cart_item_id',
    ];

    // Relationships (optional but super useful!)

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
