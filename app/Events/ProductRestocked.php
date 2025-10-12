<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductRestocked
{
    use Dispatchable, SerializesModels;

    public $product;
    public $previousStock;
    public $newStock;

    /**
     * Create a new event instance.
     */
    public function __construct(Product $product, $previousStock, $newStock)
    {
        $this->product = $product;
        $this->previousStock = $previousStock;
        $this->newStock = $newStock;
    }
}