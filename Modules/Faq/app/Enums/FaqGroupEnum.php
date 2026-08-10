<?php

namespace Modules\Faq\app\Enums;

enum FaqGroupEnum:int {
    case WEBSITE_RELATED       = 1;
    case SHOPPING_INFORMATION  = 2;
    case RETURNS_EXCHANGES     = 3;
    case SELLER_RELATED        = 4;

    public function label(): string
    {
        return match($this) {
            self::WEBSITE_RELATED      => __('Website Related'),
            self::SHOPPING_INFORMATION => __('Shopping information'),
            self::RETURNS_EXCHANGES    => __('Returns & exchanges'),
            self::SELLER_RELATED       => __('Seller Related'),
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}