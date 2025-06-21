<?php

namespace App\Enums;

/**
 * Enum representing different types of user contacts.
 *
 * Provides a list of predefined contact types such as mobile, home, work,
 * and social media options.
 *
 * @package App\Enums
 * @since 2025-06-21
 */

enum ContactTypeEnum: string {
    case MOBILE = 'mobile';
    case HOME = 'home';
    case WORK = 'work';
    case WHATSAPP = 'whatsapp';
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';

    /**
     * Get all available contact type values.
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
