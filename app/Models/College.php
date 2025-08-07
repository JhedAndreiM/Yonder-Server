<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = ['code','name'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'college_product');
    }
}
