<?php

namespace App\Listeners;

use App\Events\ProductRestocked;
use App\Models\Notification;
use App\Models\Wishlist;
use App\Events\NewNotification;

class NotifyWishlistUsers
{

    /**
     * Handle the event.
     */
    public function handle(ProductRestocked $event): void
    {
        $product = $event->product;
        $previousStock = $event->previousStock;
        $newStock = $event->newStock;

        // Only notify if product was out of stock and now has stock
        // OR if stock increased significantly from low stock
        $shouldNotify = false;
        $notificationMessage = '';

        if ($previousStock == 0 && $newStock > 0) {
            // Product was out of stock, now restocked
            $shouldNotify = true;
            $notificationMessage = "Good news! '{$product->name}' is back in stock with {$newStock} items available.";
        } elseif ($previousStock > 0 && $previousStock <= ($product->critical_level ?? 5) && $newStock > ($product->critical_level ?? 5)) {
            // Product was low in stock, now well-stocked
            $shouldNotify = true;
            $notificationMessage = "'{$product->name}' has been restocked! Now {$newStock} items available.";
        }

        if (!$shouldNotify) {
            return;
        }

        // Find all users who have this product in their wishlist
        $wishlistUsers = Wishlist::where('product_id', $product->product_id)
            ->with('user')
            ->get();

        foreach ($wishlistUsers as $wishlistItem) {
            if ($wishlistItem->user) {
                // Check if a similar notification was created recently (within 5 minutes) to prevent duplicates
                $recentNotification = Notification::where('user_id', $wishlistItem->user->id)
                    ->where('title', 'Wishlist Item Restocked')
                    ->where('message', $notificationMessage)
                    ->where('created_at', '>', now()->subMinutes(5))
                    ->first();

                if (!$recentNotification) {
                    // Create notification for each user
                    $notification = Notification::create([
                        'user_id' => $wishlistItem->user->id,
                        'title' => 'Wishlist Item Restocked',
                        'message' => $notificationMessage,
                        'is_read' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Fire the NewNotification event for live updates
                    event(new NewNotification($notification));
                }
            }
        }
    }
}