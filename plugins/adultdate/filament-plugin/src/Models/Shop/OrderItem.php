<?php

declare(strict_types=1);

namespace Adultdate\FilamentShop\Models\Shop;

use Database\Factories\Shop\OrderItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'shop_order_items';
}
