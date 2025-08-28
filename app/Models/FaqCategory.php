<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FaqQuestion;

class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * A category can have many FAQs
     */
    public function faqs()
    {
        return $this->hasMany(FaqQuestion::class, 'category_id'); // correct model
    }
}
