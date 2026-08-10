<?php

namespace Modules\Coupon\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name'                     => ['required', Rule::unique('coupon_translations')->ignore($this->coupon, 'coupon_id')->where('lang_code', getSessionLanguage())],
            'coupon_code'              => 'required|unique:coupons,coupon_code,' . $this->coupon,
            'is_percent'               => 'required',
            'discount'                 => 'required',
            'start_date'               => 'required',
            'end_date'                 => 'required_if:is_never_expired,false',
            'product_id'               => 'required_if:apply_for,product',
            'product_id.*'             => 'required_if:apply_for,product|exists:products,id',
            'category_id'              => 'required_if:apply_for,category',
            'category_id.*'            => 'required_if:apply_for,category|exists:categories,id',
            'is_never_expired'         => 'nullable',
            'minimum_spend'            => 'nullable',
            'usage_limit_per_coupon'   => 'nullable',
            'usage_limit_per_customer' => 'nullable',
            'apply_for'                => 'required',
            'status'                   => 'required',
            'free_shipping'            => 'nullable',
            'can_use_with_campaign'    => 'nullable',
            'show_homepage'            => 'required|in:0,1',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'product_id.required_if'           => __('Product is required'),
            'category_id.required_if'          => __('Category is required'),
            'apply_for.required'               => __('Apply for is required'),
            'status.required'                  => __('Status is required'),
            'name.required'                    => __('Name is required'),
            'code.required'                    => __('Code is required'),
            'code.unique'                      => __('Code already exist'),
            'name.unique'                      => __('Name already exist'),
            'discount.required'                => __('Discount amount is required'),
            'start_date.required'              => __('Start date is required'),
            'is_percent.required'              => __('Discount type is required'),
            'end_date.required_if'             => __('End date is required'),
            'end_date.required_if'             => __('End date is required if "Is never expired" is false'),
            'minimum_spend.numeric'            => __('Minimum spend must be a number'),
            'usage_limit_per_coupon.numeric'   => __('Usage limit per coupon must be a number'),
            'usage_limit_per_customer.numeric' => __('Usage limit per customer must be a number'),
            'show_homepage.required'           => __('Show on homepage is required'),
            'show_homepage.in'                 => __('Show on homepage must be either 0 or 1'),
            'free_shipping.boolean'            => __('Free shipping must be true or false'),
            'can_use_with_campaign.boolean'    => __('Can use with campaign must be true or false'),
            'is_never_expired.boolean'         => __('Is never expired must be true or false'),
            'apply_for.in'                     => __('Apply for must be either "product" or "category"'),
            'product_id.*.exists'              => __('Selected product does not exist'),
            'category_id.*.exists'             => __('Selected category does not exist'),
            'product_id.*.required_if'         => __('Product is required when apply for product'),
            'category_id.*.required_if'        => __('Category is required when apply for category'),
            'product_id.required_if'           => __('Product is required when apply for product'),
            'category_id.required_if'          => __('Category is required when apply for category'),
            'coupon_code.unique'               => __('Coupon code already exist'),
            'coupon_code.required'             => __('Coupon code is required'),
            'coupon_code.string'               => __('Coupon code must be a string'),
            'coupon_code.max'                  => __('Coupon code must not exceed 255 characters'),
            'coupon_code.min'                  => __('Coupon code must be at least 3 characters'),
            'coupon_code.regex'                => __('Coupon code must contain only letters, numbers, dashes, and underscores'),
            'coupon_code.not_in'               => __('Coupon code cannot be a reserved word'),
            'coupon_code.not_regex'            => __('Coupon code cannot contain special characters other than dashes and underscores'),
            'coupon_code.not_in'               => __('Coupon code cannot be a reserved word'),
            'show_homepage.required'           => __('Show on homepage is required'),
            'show_homepage.in'                 => __('Show on homepage must be either 0 or 1'),
            'status.required'                  => __('Status is required'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
