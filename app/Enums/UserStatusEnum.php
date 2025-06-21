<?php

namespace App\Enums;

/**
 * Enum representing different statuses a user can have.
 *
 * @package App\Enums
 * @since 2025-06-21
 */
enum UserStatusEnum: string
{
    case UNVERIFIED = 'unverified';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';

    /**
     * Get all available user status values.
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
