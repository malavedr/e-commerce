<?php

namespace App\Enums;

/**
 * Enum representing the different user roles in the system.
 *
 * @package App\Enums
 * @since 2025-06-21
 */
enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';

    /**
     * Get all available user role values.
     *
     * @return array<string> List of role string values
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
