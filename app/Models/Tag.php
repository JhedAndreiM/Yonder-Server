<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
     protected $fillable = ['name', 'usage_count', 'is_admin'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag', 'tag_id', 'product_id');
    }

    /**
     * Scope to search tags by name (case-insensitive)
     */
    public function scopeSearchByName($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', '%' . strtolower($searchTerm) . '%');
    }
}
