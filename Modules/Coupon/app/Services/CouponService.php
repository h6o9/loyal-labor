<?php

namespace Modules\Coupon\app\Services;

use Illuminate\Http\Request;
use Modules\Coupon\app\Models\Coupon;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\Product;

class CouponService
{
    use GenerateTranslationTrait;

    /**
     * @param Coupon $coupon
     */
    public function __construct(private Coupon $coupon)
    {}

    /**
     * @param  Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->validated();
        if ($request->start_date) {
            $data['start_date'] = date($request->start_date);
        }

        if ($request->end_date) {
            $data['expired_date'] = date($request->end_date);
        }

        $coupon = $this->coupon->create($data);

        if ($request->apply_for == 'product') {
            $coupon->products()->sync($request->product_id);
        }

        if ($request->apply_for == 'category') {
            $coupon->categories()->sync($request->category_id);
        }

        $this->generateTranslations(
            TranslationModels::Coupon,
            $coupon,
            'coupon_id',
            $request,
        );

        return $coupon;
    }

    /**
     * @param  Request $request
     * @param  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $data = $request->validated();

        if ($request->start_date) {
            $data['start_date'] = date($request->start_date);
        }

        if ($request->end_date) {
            $data['expired_date'] = date($request->end_date);
        }

        $coupon = $this->coupon->find($id);

        $coupon->update($data);

        if ($request->apply_for == 'product') {
            $coupon->products()->sync($request->product_id);

            // delete categories
            $coupon->categories()->detach();
        } elseif ($request->apply_for == 'category') {
            $coupon->categories()->sync($request->category_id);

            // delete products
            $coupon->products()->detach();
        } else {
            $coupon->products()->detach();
            $coupon->categories()->detach();
        }

        $this->updateTranslations(
            $coupon,
            $request,
            $data,
        );

        return $coupon;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        $coupon = $this->coupon->find($id);

        // delete translations
        $coupon->translations()->delete();

        // delete products
        $coupon->products()->detach();

        // delete categories
        $coupon->categories()->detach();

        // delete coupon history
        $coupon->couponHistories()->delete();

        return $coupon->delete();
    }

    /**
     * @param  $code
     * @param  $cart_contents
     * @return mixed
     */
    public function applyCoupon($code, $cart_contents)
    {
        $productIds  = array_column($cart_contents, 'id');
        $totalAmount = array_sum(array_column($cart_contents, 'total'));

        $coupon = $this->coupon->where('coupon_code', $code)->where('status', 1)->first();

        if ($coupon) {
            if (!$coupon->isCouponValid()) {
                return ['success' => false, 'message' => __("Coupon Isn't valid or expired!")];
            }

            if ($coupon['apply_for'] == 'category') {
                $validCategory = $coupon->isCouponValidForAllCartProductCategories($productIds);

                if (!$validCategory) {
                    return ['success' => false, 'message' => __("Coupon isn't valid for this category")];
                }
            }
            if ($coupon['apply_for'] == 'product') {
                $validProduct = $coupon->isCouponValidForAllCartProducts($productIds);
                if (!$validProduct) {
                    return ['success' => false, 'message' => __("Coupon isn't valid for this product")];
                }
            }
            // check minimum spend
            $minimumSpend = $coupon->isCouponValidForMinimumSpend($totalAmount);
            if (!$minimumSpend) {
                return ['success' => false, 'message' => __("Coupon isn't valid for this minimum spend amount")];
            }

            // isCouponValidForUsageLimit
            $usageLimit = $coupon->isCouponValidForUsageLimit();
            if (!$usageLimit) {
                return ['success' => false, 'message' => __("Coupon usage limit exceeded")];
            }

            // isCouponValidForCustomer
            if ($coupon->usage_limit_per_customer && !auth()->check()) {
                return ['success' => false, 'message' => __("You must be logged in to use this coupon")];
            }

            if (auth()->check() && $coupon->usage_limit_per_customer) {
                $customer = $coupon->isCouponValidForCustomer(auth()->id());
                if (!$customer) {
                    return ['success' => false, 'message' => __("You have already used this coupon")];
                }
            }

            // check isCouponValidForCampaign
            $cartProducts  = Product::whereIn('id', $productIds)->get();
            $validCampaign = $coupon->isCouponValidForCampaign($cartProducts);

            if (!$validCampaign) {
                return ['success' => false, 'message' => __("Coupon isn't valid for this flash deal")];
            }

            // check free shipping
            $freeShipping = $coupon->isFreeShippingApplicable();
            $discount     = $freeShipping ? 0 : $coupon->calculateCouponValue($totalAmount);

            session()->put('free_shipping', $freeShipping);
            session()->put('coupon_discount', $discount);
            session()->put('coupon_id', $coupon->id);
            session()->put('coupon_code', $coupon->coupon_code);

            return ['success' => true, 'message' => __('Coupon applied successfully'), 'coupon_code' => $coupon->coupon_code];
        } else {
            return ['success' => false, 'message' => __('Coupon not found')];
        }
    }
}
