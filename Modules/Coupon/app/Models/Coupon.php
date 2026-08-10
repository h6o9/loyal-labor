<?php

namespace Modules\Coupon\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Modules\Product\app\Models\Category;
use Modules\Product\app\Models\Product;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'author_id',
        'coupon_code',
        'discount',
        'apply_for',
        'minimum_spend',
        'usage_limit_per_coupon',
        'usage_limit_per_customer',
        'can_use_with_campaign',
        'free_shipping',
        'is_percent',
        'start_date',
        'expired_date',
        'is_never_expired',
        'used',
        'status',
        'show_homepage',
    ];

    /**
     * @return mixed
     */
    public function getNameAttribute(): ?string
    {
        return $this->translation?->name ?? '';
    }

    /**
     * @return mixed
     */
    public function translation(): ?HasOne
    {
        return $this->hasOne(CouponTranslation::class)->where('lang_code', getSessionLanguage());
    }

    /**
     * @param  $code
     * @return mixed
     */
    public function getTranslation($code): ?CouponTranslation
    {
        return $this->hasOne(CouponTranslation::class)->where('lang_code', $code)->first();
    }

    /**
     * @return mixed
     */
    public function translations(): ?HasMany
    {
        return $this->hasMany(CouponTranslation::class, 'coupon_id');
    }

    /**
     * @return mixed
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products', 'coupon_id', 'product_id');
    }

    /**
     * @return mixed
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, CouponCategory::class, 'coupon_id', 'category_id');
    }

    /**
     * @return mixed
     */
    public function couponHistories()
    {
        return $this->hasMany(CouponHistory::class);
    }

    public function isCouponValid()
    {
        $now = Carbon::now();

        // If the coupon starts in the future
        if (!is_null($this->start_date) && Carbon::parse($this->start_date)->startOfDay()->greaterThan($now)) {
            return false;
        }

        // If the coupon is expired
        if (
            !$this->is_never_expired &&
            !is_null($this->expired_date) &&
            Carbon::parse($this->expired_date)->endOfDay()->lessThan($now)
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param $cartProductIds
     */
    public function isCouponValidForAllCartProducts($cartProductIds)
    {
        // Get all product IDs associated with the coupon
        $couponProductIds = $this->products()->pluck('products.id')->toArray();

        // Check if every product in the cart is in the coupon's valid product list
        $missingProducts = array_diff($cartProductIds, $couponProductIds);

        // If there are missing products (i.e., cart products not in the coupon's product list), return false
        if (!empty($missingProducts)) {
            return false; // One or more cart products are not in the coupon's valid products
        }

        return true; // All cart products are valid for the coupon
    }

    /**
     * @param  $cartProductIds
     * @return mixed
     */
    public function isCouponValidForAllCartProductCategories($cartProductIds)
    {
        // Get all category IDs associated with the coupon
        $couponCategoryIds = $this->categories()->pluck('categories.id')->toArray();

        // Get the category IDs for each product in the cart
        $cartProductCategoryIds = Product::whereIn('id', $cartProductIds)
            ->with('categories') // Make sure we load the related categories
            ->get()
            ->flatMap(function ($product) {
                return $product->categories->pluck('id'); // Get category IDs of each product
            })
            ->toArray();

        // Check if every product's category is in the coupon's valid categories
        $missingCategories = array_diff($cartProductCategoryIds, $couponCategoryIds);

        // If there are missing categories (i.e., cart product categories not in the coupon's valid categories), return false
        if (!empty($missingCategories)) {
            return false; // One or more cart products have categories that are not valid for the coupon
        }

        return true; // All cart products have valid categories for the coupon
    }

    /**
     * @param $cartTotal
     */
    public function isCouponValidForMinimumSpend($cartTotal)
    {
        // Check if the coupon has a minimum spend requirement
        if (!is_null($this->minimum_spend) && $cartTotal < $this->minimum_spend) {
            return false; // Cart total is less than the minimum spend required
        }

        return true; // Coupon is valid based on minimum spend
    }

    public function isCouponValidForUsageLimit()
    {
        // Check if there is a usage limit set for the coupon
        if (!is_null($this->usage_limit_per_coupon) && $this->used >= $this->usage_limit_per_coupon) {
            return false; // Coupon usage limit has been reached
        }

        return true; // Coupon is valid for use
    }

    /**
     * @param $userId
     */
    public function isCouponValidForCustomer($userId)
    {
        // Check if the coupon has a usage limit per customer
        if (!is_null($this->usage_limit_per_customer)) {
            // Count how many times the customer has used this coupon
            $customerUsageCount = CouponHistory::where('coupon_id', $this->id)
                ->where('user_id', $userId)
                ->count();

            if ($customerUsageCount >= $this->usage_limit_per_customer) {
                return false; // Customer has reached the limit
            }
        }

        return true; // Coupon is valid for this customer
    }

    /**
     * @param $cartProducts
     */
    public function isCouponValidForCampaign($cartProducts)
    {
        // If the coupon does not allow usage with campaign-priced products
        if ($this->can_use_with_campaign == 0) {
            // Check if any product in the cart has a campaign price
            foreach ($cartProducts as $product) {
                if ($product->is_flash_deal_active && $product->flash_deal_qty > 0) {
                    return false;
                }
            }
        }

        return true; // Coupon is valid for the cart
    }

    /**
     * @return mixed
     */
    public function isFreeShippingApplicable()
    {
        return $this->free_shipping == 1;
    }

    /**
     * @param  $cartTotal
     * @return mixed
     */
    public function calculateCouponValue($cartTotal)
    {
        if ($this->is_percent) {
            return $cartTotal * ($this->discount / 100);
        }

        return $this->discount;
    }
}
