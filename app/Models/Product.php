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
    protected $casts = [
    'variants' => 'array',  
    ];
    public function user()
    {
    return $this->belongsTo(User::class);
    }
    public function tags()
    {
    return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class); 
    }
    public function colleges()
    {
        return $this->belongsToMany(College::class, 'college_product');
    }
    

}
