<?php
namespace Modules\Order\app\Http\Enums;

enum OrderStatus: string {
    case PENDING    = 'pending';
    case APPROVED   = 'approved';
    case PROCESSING = 'processing';
    case PACKED     = 'packed';
    case SHIPPED    = 'shipped';
    case DELIVERED  = 'delivered';
    case CANCELLED  = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __('Pending'),
            self::APPROVED => __('Approved'),
            self::PROCESSING => __('Processing'),
            self::PACKED => __('Packed'),
            self::SHIPPED => __('Shipped'),
            self::DELIVERED => __('Delivered'),
            self::CANCELLED => __('Cancelled'),
            default => __('Unknown')
        };
    }

    public function serial(): string
    {
        return match ($this) {
            self::PENDING => 0,
            self::APPROVED => 1,
            self::PROCESSING => 2,
            self::PACKED => 3,
            self::SHIPPED => 4,
            self::DELIVERED => 5,
            self::CANCELLED => 6,
            default => 0
        };
    }

    public static function casesBySerial(): array
    {
        $cases = self::cases();

        usort($cases, fn($a, $b) => $a->serial() <=> $b->serial());

        return array_values($cases);
    }

    /**
     * @return mixed
     */
    public function previous(): ?self
    {
        $cases = self::casesBySerial();
        $index = array_search($this, $cases, true);

        return $cases[$index - 1] ?? null;
    }

    /**
     * @return mixed
     */
    public function next(): ?self
    {
        $cases = self::casesBySerial();
        $index = array_search($this, $cases, true);

        return $cases[$index + 1] ?? null;
    }

    public function class (): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'info',
            self::PROCESSING => 'primary',
            self::PACKED => 'secondary',
            self::SHIPPED => 'success',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    public static function getAll(): array
    {
        return [
            self::PENDING->value,
            self::APPROVED->value,
            self::PROCESSING->value,
            self::PACKED->value,
            self::SHIPPED->value,
            self::DELIVERED->value,
            self::CANCELLED->value,
        ];
    }
}
