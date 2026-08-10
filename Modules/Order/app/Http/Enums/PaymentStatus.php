<?php

namespace Modules\Order\app\Http\Enums;

enum PaymentStatus: string {
    case PENDING    = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED  = 'completed';
    case FAILED     = 'failed';
    case REJECTED   = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::PROCESSING => __('Processing'),
            self::COMPLETED => __('Completed'),
            self::FAILED => __('Failed'),
            self::REJECTED => __('Rejected'),
            default => __('Unknown')
        };
    }

    public static function getAll(): array
    {
        return [
            self::PENDING->value,
            self::PROCESSING->value,
            self::COMPLETED->value,
            self::FAILED->value,
            self::REJECTED->value,
        ];
    }

    public function class (): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PROCESSING => 'info',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::REJECTED => 'danger',
            default => 'secondary'
        };
    }
}
