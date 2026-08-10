<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public const PLAN_TYPES = [
        'basic_plan' => 'Basic',
        'silver_plan' => 'Silver',
        'golden_plan' => 'Gold',
    ];

    public const DURATION_UNITS = [
        'days' => 'Days',
        'months' => 'Months',
    ];

    /**
     * Locked feature catalog per plan. Admin cannot invent custom features.
     */
    public const STATIC_FEATURES = [
        'basic_plan' => [
            ['key' => 'pay_to_view_booking_details', 'label' => 'Pay to view booking details'],
            ['key' => 'verified_technician_badge', 'label' => 'Verified technician badge'],
            ['key' => 'enable_customer_reviews_ratings', 'label' => 'Enable customer reviews & ratings'],
        ],
        'silver_plan' => [
            ['key' => 'profile_featured', 'label' => 'Profile featured for 7 days'],
            ['key' => 'increased_profile_visibility', 'label' => 'Increased profile visibility'],
            ['key' => 'verified_badge', 'label' => 'Verified badge'],
            ['key' => 'priority_search_placement', 'label' => 'Priority search placement'],
            ['key' => 'more_customer_reach', 'label' => 'More customer reach'],
        ],
        'golden_plan' => [
            ['key' => 'profile_featured', 'label' => 'Profile featured for 15 days'],
            ['key' => 'increased_profile_visibility', 'label' => 'Increased profile visibility'],
            ['key' => 'verified_badge', 'label' => 'Verified badge'],
            ['key' => 'priority_search_placement', 'label' => 'Priority search placement'],
            ['key' => 'more_customer_reach', 'label' => 'More customer reach'],
        ],
    ];

    protected $guarded = [];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public static function planTypeKeys(): array
    {
        return array_keys(self::PLAN_TYPES);
    }

    public static function featuresForPlanType(string $planType): array
    {
        return self::STATIC_FEATURES[$planType] ?? [];
    }

    public static function featureLabelsForPlanType(string $planType): array
    {
        return array_values(array_map(
            fn (array $feature) => $feature['label'],
            self::featuresForPlanType($planType)
        ));
    }

    public static function featureKeysForPlanType(string $planType): array
    {
        return array_values(array_map(
            fn (array $feature) => $feature['key'],
            self::featuresForPlanType($planType)
        ));
    }

    public function getPlanTypeLabelAttribute(): string
    {
        return self::PLAN_TYPES[$this->plan_type] ?? ucwords(str_replace('_', ' ', (string) $this->plan_type));
    }

    public function getDurationUnitAttribute($value): string
    {
        $unit = $value ?: 'months';

        return in_array($unit, ['days', 'months'], true) ? $unit : 'months';
    }

    public function getDurationValueAttribute(): int
    {
        return max(1, (int) ($this->duration_months ?: 1));
    }

    public function getDurationLabelAttribute(): string
    {
        $value = $this->duration_value;
        $unit = $this->duration_unit;

        if ($unit === 'days') {
            return $value . ' ' . ($value === 1 ? 'Day' : 'Days');
        }

        return $value . ' ' . ($value === 1 ? 'Month' : 'Months');
    }

    public function endsAtFrom($from = null): \Carbon\Carbon
    {
        $from = $from ? \Carbon\Carbon::parse($from) : now();
        $value = $this->duration_value;

        return $this->duration_unit === 'days'
            ? $from->copy()->addDays($value)
            : $from->copy()->addMonths($value);
    }

    public function resolvedFeatureLabels(): array
    {
        $planType = $this->plan_type ?? 'basic_plan';
        $locked = self::featureLabelsForPlanType($planType);
        if (!empty($locked)) {
            return $locked;
        }

        $features = is_array($this->features) ? $this->features : json_decode($this->features ?? '[]', true);
        if (!is_array($features)) {
            $features = array_filter(array_map('trim', explode(',', (string) $this->features)));
        }

        return array_values($features);
    }

    public function toApiArray(): array
    {
        $planType = $this->plan_type ?? 'basic_plan';
        $originalPrice = (float) $this->price_pkr;
        $savingPrice = (float) ($this->saving_price ?? 0);
        $payablePrice = $savingPrice > 0 ? $savingPrice : $originalPrice;
        $discountPercent = $originalPrice > 0 && $savingPrice > 0 && $savingPrice < $originalPrice
            ? (int) round((($originalPrice - $savingPrice) / $originalPrice) * 100)
            : (int) ($this->discount_percent ?? 0);

        return [
            'id' => $this->id,
            'name' => $this->name ?: ($this->plan_type_label),
            'plan_type' => $planType,
            'plan_type_label' => $this->plan_type_label,
            'duration' => $this->duration_value,
            'duration_months' => $this->duration_value,
            'duration_unit' => $this->duration_unit,
            'duration_label' => $this->duration_label,
            'price_pkr' => $originalPrice,
            'saving_price' => $savingPrice,
            'payable_price' => $payablePrice,
            'discount_percent' => $discountPercent,
            'tax_percent' => (int) ($this->tax_percent ?? 0),
            'features' => $this->resolvedFeatureLabels(),
            'feature_keys' => self::featureKeysForPlanType($planType),
            'is_active' => (bool) $this->is_active,
        ];
    }
}
