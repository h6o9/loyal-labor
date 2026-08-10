<?php

namespace Modules\Frontend\app\Enums;

enum ManageThemeEnum: int {
    case THEME_ONE   = 1;
    case THEME_TWO   = 2;
    case THEME_THREE = 3;
    case THEME_FOUR  = 4;

    public static function themes()
    {
        return (object) [
            (object) [
                'name'       => self::THEME_ONE,
                'title'      => self::THEME_ONE->label(),
                'screenshot' => self::THEME_ONE->screenshot(),
            ],
            (object) [
                'name'       => self::THEME_TWO,
                'title'      => self::THEME_TWO->label(),
                'screenshot' => self::THEME_TWO->screenshot(),
            ],
            (object) [
                'name'       => self::THEME_THREE,
                'title'      => self::THEME_THREE->label(),
                'screenshot' => self::THEME_THREE->screenshot(),
            ],
            (object) [
                'name'       => self::THEME_FOUR,
                'title'      => self::THEME_FOUR->label(),
                'screenshot' => self::THEME_FOUR->screenshot(),
            ],
        ];
    }

    /**
     * @param $theme
     */
    public function label()
    {
        return match ($this) {
            self::THEME_ONE => __('Theme One'),
            self::THEME_TWO => __('Theme Two'),
            self::THEME_THREE => __('Theme Three'),
            self::THEME_FOUR => __('Theme Four'),
            default => __('Unknown Theme'),
        };
    }

    public function screenshot()
    {
        return match ($this) {
            self::THEME_ONE => 'backend/img/theme/1.webp',
            self::THEME_TWO => 'backend/img/theme/2.webp',
            self::THEME_THREE => 'backend/img/theme/3.webp',
            self::THEME_FOUR => 'backend/img/theme/4.webp',
            default => 'backend/img/theme/1.webp',
        };
    }

    public static function allThemeValueArray(): array
    {
        return (array) [
            self::THEME_ONE->value,
            self::THEME_TWO->value,
            self::THEME_THREE->value,
            self::THEME_FOUR->value,
        ];
    }

    public function getSections()
    {
        return match ($this) {
            self::THEME_ONE => HomepageOneEnum::sections(),
            self::THEME_TWO => HomepageTwoEnum::sections(),
            self::THEME_THREE => HomepageThreeEnum::sections(),
            self::THEME_FOUR => HomepageFourEnum::sections(),
            default => HomepageOneEnum::sections(),
        };
    }
}
