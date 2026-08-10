<?php

namespace Modules\BasicPayment\database\seeders;

use Illuminate\Database\Seeder;
use Modules\BasicPayment\app\Models\PaymentGateway;
use Modules\Currency\app\Models\MultiCurrency;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_info = [
            'razorpay_status'           => 'active',
            'razorpay_key'              => 'razorpay_key',
            'razorpay_secret'           => 'razorpay_secret',
            'razorpay_name'             => 'WebSolutionUs',
            'razorpay_description'      => 'This is test payment window',
            'razorpay_charge'           => 0.00,
            'razorpay_theme_color'      => '#6d0ce4',
            'razorpay_currency_id'      => MultiCurrency::where('currency_code', 'INR')->first()?->id,
            'razorpay_image'            => 'website/images/gateways/razorpay.webp',
            'flutterwave_status'        => 'active',
            'flutterwave_public_key'    => 'flutterwave_public_key',
            'flutterwave_secret_key'    => 'flutterwave_secret_key',
            'flutterwave_app_name'      => 'WebSolutionUs',
            'flutterwave_charge'        => 0.00,
            'flutterwave_currency_id'   => MultiCurrency::where('currency_code', 'NGN')->first()?->id,
            'flutterwave_image'         => 'website/images/gateways/flutterwave.webp',
            'paystack_status'           => 'active',
            'paystack_public_key'       => 'paystack_public_key',
            'paystack_secret_key'       => 'paystack_secret_key',
            'paystack_charge'           => 0.00,
            'paystack_image'            => 'website/images/gateways/paystack.webp',
            'paystack_currency_id'      => MultiCurrency::where('currency_code', 'NGN')->first()?->id,
            'mollie_status'             => 'active',
            'mollie_key'                => 'mollie_key',
            'mollie_charge'             => 0.00,
            'mollie_image'              => 'website/images/gateways/mollie.webp',
            'mollie_currency_id'        => MultiCurrency::where('currency_code', 'CAD')->first()?->id,
            'instamojo_status'          => 'active',
            'instamojo_account_mode'    => 'Sandbox',
            'instamojo_client_id'       => 'instamojo_client_id',
            'instamojo_client_secret'   => 'instamojo_client_secret',
            'instamojo_charge'          => 0.00,
            'instamojo_image'           => 'website/images/gateways/instamojo.webp',
            'instamojo_currency_id'     => MultiCurrency::where('currency_code', 'INR')->first()?->id,
            'sslcommerz_status'         => 'active',
            'sslcommerz_store_id'       => 'test669499013b632',
            'sslcommerz_store_password' => 'test669499013b632@ssl',
            'sslcommerz_image'          => 'website/images/gateways/sslcommerz.webp',
            'sslcommerz_test_mode'      => 1,
            'sslcommerz_localhost'      => 1,
            'sslcommerz_charge'         => 0,
            'sslcommerz_currency_id'    => MultiCurrency::where('currency_code', 'BDT')->first()?->id,
            'crypto_status'             => 'active',
            'crypto_sandbox'            => true,
            'crypto_api_key'            => 'WzrKM5s3vzWKj4wDGrz6uJzG81Hdf35pe7ov7Wyv',
            'crypto_image'              => 'website/images/gateways/crypto.webp',
            'crypto_charge'             => 0,
            'crypto_currency_id'        => MultiCurrency::where('currency_code', 'USD')->first()?->id,
            'crypto_receive_currency'   => 'BTC',
        ];

        foreach ($payment_info as $index => $payment_item) {
            $new_item        = new PaymentGateway;
            $new_item->key   = $index;
            $new_item->value = $payment_item;
            $new_item->save();
        }
    }
}
