<?php

namespace App\Enums;

/**
 * Enum representing different statuses of an order.
 *
 * @package App\Enums
 * @since 2025-06-21
 */
enum OrderStatusEnum: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    /**
     * Get all available order status values.
     *
     * Returns a list of the enum values as strings.
     *
     * @return array<string>
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
