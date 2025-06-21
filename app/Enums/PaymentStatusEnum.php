<?php

namespace App\Enums;

/**
 * Enum representing different payment statuses.
 *
 * @package App\Enums
 * @since 2025-06-21
 */
enum PaymentStatusEnum: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case EXPIRED = 'expired';

    /**
     * Get all available payment status values.
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
