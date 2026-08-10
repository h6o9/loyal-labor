<?php

namespace App\Enums;

enum SocialiteDriverType: string
{
    case GOOGLE = 'google';

    public static function getIcons(): array
    {
        return [
            self::GOOGLE->value => 'website/images/gmail_logo.svg',
        ];
    }

    public static function getAll(): array
    {
        return [
            self::GOOGLE->value,
        ];
    }
}
