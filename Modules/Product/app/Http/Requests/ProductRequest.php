<?php

namespace Modules\Product\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $return_policy_question
 * @property mixed $return_policy_answer
 */
class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->method() == 'PUT' && $this->code !== allLanguages()->first()->code) {
            $rules = [
                'name'              => 'required',
                'description'       => 'nullable',
                'short_description' => 'nullable',
                'seo_title'         => 'nullable',
                'seo_description'   => 'nullable',
            ];
        } else {
            $rules = [
                'name'                             => 'required',
                'slug'                             => 'required',
                'category_id'                      => 'required|array',
                'category_id.*'                    => 'required|exists:categories,id',
                'brand_id'                         => 'nullable',
                'vendor_id'                        => 'required|exists:vendors,id',
                'unit_type_id'                     => 'nullable',
                'short_description'                => 'required',
                'description'                      => 'required',
                'thumbnail_image'                  => 'required|image|max:1024',
                'flash_deal_image'                 => 'nullable|image|max:1024',
                'video_link'                       => 'nullable',
                'is_cash_delivery'                 => 'nullable',
                'is_return'                        => 'nullable',
                'is_featured'                      => 'nullable',
                'is_new'                           => 'nullable',
                'is_popular'                       => 'nullable',
                'is_best_selling'                  => 'nullable',
                'allow_checkout_when_out_of_stock' => 'nullable',
                'price'                            => 'required',
                'offer_price'                      => 'nullable',
                'offer_price_type'                 => 'nullable|in:fixed,percentage',
                'offer_price_start'                => 'nullable',
                'offer_price_end'                  => 'nullable',
                'manage_stock'                     => 'nullable',
                'stock_status'                     => 'nullable',
                'stock_qty'                        => 'nullable',
                'sku'                              => 'required',
                'barcode'                          => 'nullable',
                'status'                           => 'required',
                'tax_ids'                          => 'nullable|array',
                'tax_ids.*'                        => 'required|exists:taxes,id',
                'length'                           => 'nullable',
                'wide'                             => 'nullable',
                'height'                           => 'nullable',
                'tags'                             => 'required',
                'weight'                           => 'nullable',
                'seo_title'                        => 'nullable',
                'seo_description'                  => 'nullable',
                // 'return_policy_id'                 => 'nullable|exists:product_tos,id',
                // 'return_policy_question'           => 'nullable|string|max:200',
                // 'return_policy_answer'             => 'nullable|string|max:5000',
                'is_flash_deal'                    => 'required|in:0,1',
                'flash_deal_start'                 => 'required_if:is_flash_deal,1',
                'flash_deal_end'                   => 'required_if:is_flash_deal,1',
                'flash_deal_price'                 => 'required_if:is_flash_deal,1',
                'flash_deal_qty'                   => 'required_if:is_flash_deal,1',
            ];
        }

        if ($this->method() == 'PUT') {
            $rules['thumbnail_image'] = 'nullable';
        }

        return $rules;
    }

    /**
     * @param  $validator
     * @return mixed
     */
    public function withValidator($validator)
    {
        $validator->sometimes('offer_price', 'lt:price', function ($input) {
            return $input->offer_price_type === 'fixed' && !is_null($input->offer_price);
        });

        $validator->sometimes('flash_deal_price', 'lt:price', function ($input) {
            return $input->is_flash_deal == 1 && !is_null($input->flash_deal_price);
        });
    }

    public function messages()
    {
        return [
            'name.required'                => __('The product name is required.'),
            'slug.required'                => __('The product slug is required.'),
            'category_id.required'         => __('At least one product category must be selected.'),
            'category_id.array'            => __('The product categories must be an array.'),
            'category_id.*.required'       => __('Each selected product category is required.'),
            'category_id.*.exists'         => __('One or more selected categories do not exist.'),
            'brand_id.exists'              => __('The selected brand does not exist.'),
            'vendor_id.required'           => __('Select a vendor.'),
            'vendor_id.exists'             => __('The selected vendor does not exist.'),
            'unit_type_id.required'        => __('The product unit type is required.'),
            'short_description.required'   => __('A short description is required.'),
            'description.required'         => __('A full product description is required.'),
            'thumbnail_image.required'     => __('A thumbnail image is required.'),
            'thumbnail_image.image'        => __('The thumbnail must be a valid image.'),
            'thumbnail_image.max'          => __('The thumbnail image must not exceed 1MB.'),
            'flash_deal_image.image'       => __('The flash deal image must be a valid image.'),
            'flash_deal_image.max'         => __('The flash deal image must not exceed 1MB.'),
            'video_link.url'               => __('The video link must be a valid URL.'),
            'price.required'               => __('The product price is required.'),
            'sku.required'                 => __('The product SKU is required.'),
            'barcode.required'             => __('The product barcode is required.'),
            'status.required'              => __('The product status is required.'),
            'tags.required'                => __('At least one product tag is required.'),

            'tax_ids.array'                => __('The tax list must be an array.'),
            'tax_ids.*.required'           => __('Each selected tax is required.'),
            'tax_ids.*.exists'             => __('One or more selected taxes do not exist.'),

            'offer_price_type.in'          => __('The offer price type must be either fixed or percentage.'),

            'offer_price.lt'               => __('The offer price must be less than the regular price when the type is fixed.'),
            'offer_price.numeric'          => __('The offer price must be a numeric value.'),

            'return_policy_id.exists'      => __('The selected return policy does not exist.'),
            'return_policy_question.max'   => __('The return policy question must not exceed 200 characters.'),
            'return_policy_answer.max'     => __('The return policy answer must not exceed 5000 characters.'),

            'is_flash_deal.required'       => __('Please specify whether this product is part of a flash deal.'),
            'is_flash_deal.in'             => __('Flash deal status must be either enabled or disabled.'),

            'flash_deal_start.required_if' => __('The flash deal start time is required when flash deal is enabled.'),
            'flash_deal_end.required_if'   => __('The flash deal end time is required when flash deal is enabled.'),
            'flash_deal_price.required_if' => __('The flash deal price is required when flash deal is enabled.'),
            'flash_deal_price.lt'          => __('The flash deal price must be less than the regular price.'),
            'flash_deal_qty.required_if'   => __('The flash deal quantity is required when flash deal is enabled.'),
        ];
    }
}
