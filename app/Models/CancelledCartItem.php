<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancelledCartItem extends Model
{
    protected $fillable = [
        'original_cart_id',
        'product_id',
        'buyer_id',
        'seller_id',
        'product_name',
        'unit_price',
        'quantity',
        'selected_variant',
        'voucher_applied',
        'payment_type',
        'payment_confirmation',
        'gcash_receipt',
        'seller_qr_image',
        'status',
        'original_created_at',
        'original_updated_at',
        'cancelled_by',
        'cancel_reason',
        'custom_reason',
        'cancelled_at',
        'cancellation_notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'voucher_applied' => 'decimal:2',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Scopes
    public function scopeByReason($query, $reason)
    {
        return $query->where('cancel_reason', $reason);
    }

    public function scopeByBuyer($query, $buyerId)
    {
        return $query->where('buyer_id', $buyerId);
    }

    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeCancelledBy($query, $cancelledBy)
    {
        return $query->where('cancelled_by', $cancelledBy);
    }

    // Accessors
    public function getTotalAmountAttribute()
    {
        return ($this->unit_price * $this->quantity) - $this->voucher_applied;
    }

    public function getFormattedCancelledAtAttribute()
    {
        return $this->cancelled_at->format('M d, Y g:i A');
    }
}
