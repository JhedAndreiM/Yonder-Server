<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradeOfferItem extends Model
{
    protected $fillable = [
        'trade_offer_id',
        'product_id',
        'side',
        'quantity',
        'variant_index',
        'variant_name',
        'price_at_time',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'variant_index' => 'integer',
        'price_at_time' => 'decimal:2',
    ];

    /**
     * Get the trade offer this item belongs to
     */
    public function tradeOffer(): BelongsTo
    {
        return $this->belongsTo(TradeOffer::class);
    }

    /**
     * Get the product being traded
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
