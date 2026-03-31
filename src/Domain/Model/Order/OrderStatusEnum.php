<?php

declare(strict_types=1);

namespace App\Domain\Model\Order;

enum OrderStatusEnum: string
{

    case PROCESSING   = 'processing';
    case CONFIRMED    = 'confirmed';
    case OUT_OF_STOCK = 'out_of_stock';
    case SHIPPED      = 'shipped';
    case DELIVERED    = 'delivered';
    case CANCELLED = 'cancelled';

}
