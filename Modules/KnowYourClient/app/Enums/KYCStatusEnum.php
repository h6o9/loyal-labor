<?php
namespace Modules\KnowYourClient\app\Enums;

enum KYCStatusEnum: int {
    case PENDING  = 0;
    case APPROVED = 1;
    case REJECTED = 2;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }
}
