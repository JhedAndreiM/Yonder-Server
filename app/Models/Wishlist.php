<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table='wishlists';
    protected $fillable = ['user_id', 'product_id'];

    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function product(){
        return $this->hasOne('App\Models\Product', 'product_id', 'product_id');
    }
}
