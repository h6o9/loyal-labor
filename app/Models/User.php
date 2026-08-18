<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatus;
use App\Models\ScopesTraits\GlobalActiveScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Blog\app\Models\BlogComment;
use Modules\KnowYourClient\app\Models\KycInformation;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\ProductReview;

class User extends Authenticatable
{
    use GlobalActiveScopeTrait, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'certificates' => 'array',
        'skills' => 'array',
        'service_area' => 'array',
        'is_verified' => 'boolean',
        'cnic_front_verified' => 'boolean',
        'cnic_back_verified' => 'boolean',
        'photo_verified' => 'boolean',
        'certificates_verified' => 'boolean',
        'currently_available' => 'boolean',
    ];

    /**
     * @return mixed
     */
    public function getFullAddressAttribute()
    {
        $this->load('city', 'state', 'country');

        return $this->address . ', ' . optional($this->city)->name . ', ' . optional($this->state)->name . ', ' . $this->zip_code . ', ' . optional($this->country)->name;
    }

    /**
     * @return mixed
     */
    public function getIsShopVerifiedAttribute()
    {
        if (!$this->load('seller.kyc')) {
            return false;
        }

        return $this->seller?->kyc?->status ?? false;
    }

    /**
     * @return mixed
     */
    public function seller()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'user_id');
    }

    public function technicianReviews()
    {
        return $this->hasMany(TechnicianReview::class, 'technician_id');
    }

    public function approvedTechnicianReviews()
    {
        return $this->hasMany(TechnicianReview::class, 'technician_id')->where('is_approved', true);
    }

    /**
     * @return mixed
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function savedAddresses(): HasMany
    {
        return $this->hasMany(UserSavedAddress::class);
    }

    public function memberSince(): ?string
    {
        return $this->created_at ? $this->created_at->format('F Y') : null;
    }

    public function memberSinceFrom(): ?string
    {
        return $this->created_at ? $this->created_at->toDateString() : null;
    }

    /**
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', UserStatus::ACTIVE);
    }

    /**
     * @return mixed
     */
    public function scopeInactive($query)
    {
        return $query->where('status', UserStatus::INACTIVE);
    }

    /**
     * @return mixed
     */
    public function scopeBanned($query)
    {
        return $query->where('is_banned', UserStatus::BANNED);
    }

    /**
     * @return mixed
     */
    public function scopeUnbanned($query)
    {
        return $query->where('is_banned', UserStatus::UNBANNED);
    }

    /**
     * @return mixed
     */
    public function socialite()
    {
        return $this->hasMany(SocialiteCredential::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return mixed
     */
    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return mixed
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return mixed
     */
    public function scopeVerify($query)
    {
        return $query->where('email_verified_at', '!=', null);
    }

    /**
     * @return mixed
     */
    public function kyc()
    {
        return $this->belongsTo(KycInformation::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function walletRequests()
    {
        return $this->hasMany(WithdrawRequest::class, 'user_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TechnicianAvailability::class, 'technician_id');
    }

    public function documentUrl(?string $path, string $dummyFile): string
    {
        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset('dummy/' . $dummyFile);
    }

    public function allDocumentsVerified(): bool
    {
        return $this->cnic_front_verified
            && $this->cnic_back_verified
            && $this->photo_verified
            && $this->certificates_verified;
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function hasActiveSubscription(): bool
    {
        if (($this->subscription ?? null) !== 'active' || !$this->subscription_id) {
            return false;
        }

        if ($this->subscription_end && now()->toDateString() > (string) $this->subscription_end) {
            return false;
        }

        return true;
    }

    public function activeFeatureKeys(): array
    {
        if (!$this->hasActiveSubscription()) {
            return [];
        }

        $plan = $this->relationLoaded('subscriptionPlan')
            ? $this->subscriptionPlan
            : $this->subscriptionPlan()->first();

        if (!$plan) {
            return [];
        }

        return Subscription::featureKeysForPlanType($plan->plan_type ?? 'basic_plan');
    }

    public function hasPlanFeature(string $featureKey): bool
    {
        return in_array($featureKey, $this->activeFeatureKeys(), true);
    }

    public function isFeaturedTechnician(): bool
    {
        return $this->hasPlanFeature('profile_featured');
    }

    public function hasVerifiedPlanBadge(): bool
    {
        return $this->hasPlanFeature('verified_badge')
            || $this->hasPlanFeature('verified_technician_badge');
    }

    public function referralShop()
    {
        return $this->belongsTo(Shop::class, 'referral_shop_id');
    }

    public function serviceCategories()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'service_category_user',
            'user_id',
            'service_category_id'
        )->withTimestamps();
    }

    public function scopePublicTechnicians($query)
    {
        return $query->where('user_type', 'technician')
            ->where('status', 'active');
    }

    public function workGalleries()
    {
        return $this->hasMany(TechnicianWorkGallery::class, 'technician_id')->orderBy('sort_order');
    }
}

