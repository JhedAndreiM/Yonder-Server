<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TradeOffer extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'parent_offer_id',
        'status',
        'message',
        'cancellation_reason',
        'decline_reason',
        'responded_at',
        'completed_at',
        'sender_confirmed',
        'recipient_confirmed',
        'sender_confirmed_at',
        'recipient_confirmed_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'completed_at' => 'datetime',
        'sender_confirmed_at' => 'datetime',
        'recipient_confirmed_at' => 'datetime',
        'sender_confirmed' => 'boolean',
        'recipient_confirmed' => 'boolean',
    ];

    /**
     * Get the user who sent the trade offer
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received the trade offer
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get all items in this trade offer
     */
    public function items(): HasMany
    {
        return $this->hasMany(TradeOfferItem::class);
    }

    /**
     * Get items offered by the sender
     */
    public function senderItems(): HasMany
    {
        return $this->hasMany(TradeOfferItem::class)->where('side', 'sender');
    }

    /**
     * Get items requested from the recipient
     */
    public function recipientItems(): HasMany
    {
        return $this->hasMany(TradeOfferItem::class)->where('side', 'recipient');
    }

    /**
     * Get the parent offer if this is a counter-offer
     */
    public function parentOffer(): BelongsTo
    {
        return $this->belongsTo(TradeOffer::class, 'parent_offer_id');
    }

    /**
     * Get all counter-offers made in response to this offer
     */
    public function counterOffers(): HasMany
    {
        return $this->hasMany(TradeOffer::class, 'parent_offer_id');
    }

    /**
     * Check if this is a counter-offer
     */
    public function isCounterOffer(): bool
    {
        return !is_null($this->parent_offer_id);
    }

    /**
     * Check if the trade offer is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the trade offer is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the trade offer is countered
     */
    public function isCountered(): bool
    {
        return $this->status === 'countered';
    }

    /**
     * Check if the trade offer is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if both parties have confirmed receipt
     */
    public function isBothConfirmed(): bool
    {
        return $this->sender_confirmed && $this->recipient_confirmed;
    }

    /**
     * Check if user has confirmed receipt
     */
    public function hasUserConfirmed(int $userId): bool
    {
        if ($userId === $this->sender_id) {
            return $this->sender_confirmed;
        }
        if ($userId === $this->recipient_id) {
            return $this->recipient_confirmed;
        }
        return false;
    }
}
