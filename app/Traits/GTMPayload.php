<?php
namespace App\Traits;

use Exception;

trait GTMPayload
{

    /**
     * @param array  $item
     * @param string $event
     */
    public function getGTMPayloadForCart(array $item, string $event = 'add_to_cart'): array
    {
        try {
            $options = collect($item['variant']['options'] ?? []);

            $formattedOptions = $options->map(fn($opt) => "{$opt['attribute']}: {$opt['attribute_value']}")->implode(', ');

            return [
                'event'     => $event,
                'user_type' => auth()->check() ? 'user' : 'guest',
                'user_id'   => auth()->check() ? auth()->user()->id : null,
                'currency'  => getSessionCurrency(),
                'ecommerce' => [
                    'items' => [
                        [
                            'item_id'      => $item['id'],
                            'item_name'    => $item['name'],
                            'price'        => (float) $item['price'],
                            'quantity'     => (int) $item['qty'],
                            'discount'     => (float) $item['discounted_amount'],
                            'item_variant' => $item['has_variant'] ? $formattedOptions : null,
                            'item_brand'   => $item['vendor_name'],
                        ],
                    ],
                ],
            ];
        } catch (Exception $e) {
            logError("Unable to process GTM DATA", $e);
            return [];
        }
    }

    /**
     * @param object $product
     * @param string $event
     */
    public function getGTMPayloadForWishlist($product, string $event = 'add_to_wishlist'): array
    {
        return [
            'event'     => $event,
            'user_type' => auth()->check() ? 'user' : 'guest',
            'user_id'   => auth()->check() ? auth()->user()->id : null,
            'currency'  => getSessionCurrency(),
            'ecommerce' => [
                'items' => [
                    [
                        'item_id'    => $product->sku,
                        'item_name'  => $product->name,
                        'price'      => (float) $product->price,
                        'item_brand' => $product->vendor->shop_name ?? null,
                    ],
                ],
            ],
        ];
    }
}
