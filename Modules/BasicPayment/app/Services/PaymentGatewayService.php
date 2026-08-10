<?php

namespace Modules\BasicPayment\app\Services;

use Modules\BasicPayment\app\Enums\PaymentGatewaySupportedCurrencyListEnum;

class PaymentGatewayService extends PaymentMethodService
{
    const MOLLIE = 'mollie';

    const RAZORPAY = 'razorpay';

    const FLUTTERWAVE = 'flutterwave';

    const INSTAMOJO = 'instamojo';

    const PAYSTACK = 'paystack';

    const SSLCOMMERZ = 'sslcommerz';

    const CRYPTO = 'crypto';

    /**
     * @var mixed
     */
    protected $previousService;

    /**
     * @param PaymentMethodService $previousService
     */
    public function __construct(?PaymentMethodService $previousService = null)
    {
        if (is_null($previousService)) {
            $previousService = PaymentMethodService::class;
        }

        $this->previousService = $previousService;

        self::extendSupportedPayments([
            self::RAZORPAY,
            self::FLUTTERWAVE,
            self::MOLLIE,
            self::INSTAMOJO,
            self::PAYSTACK,
            self::SSLCOMMERZ,
            self::CRYPTO,
        ]);

        self::extendMultiCurrencySupported([
            self::RAZORPAY,
            self::FLUTTERWAVE,
            self::MOLLIE,
            self::INSTAMOJO,
            self::PAYSTACK,
            self::SSLCOMMERZ,
            self::CRYPTO,
        ]);
    }

    /**
     * @param string $gatewayName
     */
    public function getPaymentName(string $gatewayName): ?string
    {
        return match ($gatewayName) {
            self::RAZORPAY => 'Razorpay',
            self::FLUTTERWAVE => 'Flutterwave',
            self::MOLLIE => 'Mollie',
            self::INSTAMOJO => 'Instamojo',
            self::PAYSTACK => 'Paystack',
            self::SSLCOMMERZ => 'Sslcommerz',
            self::CRYPTO => 'Crypto',
            default => $this->previousService->getPaymentName($gatewayName),
        };
    }

    /**
     * @param string $gatewayName
     */
    public function getGatewayDetails(string $gatewayName): ?object
    {
        $paymentSetting = $this->getPaymentGatewayInfo();

        return match ($gatewayName) {
            self::RAZORPAY => (object) [
                'razorpay_key'         => $paymentSetting->razorpay_key ?? null,
                'razorpay_secret'      => $paymentSetting->razorpay_secret ?? null,
                'razorpay_name'        => $paymentSetting->razorpay_name ?? null,
                'razorpay_description' => $paymentSetting->razorpay_description ?? null,
                'razorpay_theme_color' => $paymentSetting->razorpay_theme_color ?? null,
                'razorpay_status'      => $paymentSetting->razorpay_status ?? null,
                'razorpay_image'       => $paymentSetting->razorpay_image ?? null,
                'currency_id'          => $paymentSetting->razorpay_currency_id ?? null,
                'charge'               => $paymentSetting->razorpay_charge ?? null,
            ],
            self::FLUTTERWAVE => (object) [
                'flutterwave_public_key' => $paymentSetting->flutterwave_public_key ?? null,
                'flutterwave_secret_key' => $paymentSetting->flutterwave_secret_key ?? null,
                'flutterwave_app_name'   => $paymentSetting->flutterwave_app_name ?? null,
                'charge'                 => $paymentSetting->flutterwave_charge ?? null,
                'currency_id'            => $paymentSetting->flutterwave_currency_id ?? null,
                'flutterwave_status'     => $paymentSetting->flutterwave_status ?? null,
                'flutterwave_image'      => $paymentSetting->flutterwave_image ?? null,
            ],
            self::PAYSTACK => (object) [
                'paystack_public_key' => $paymentSetting->paystack_public_key ?? null,
                'paystack_secret_key' => $paymentSetting->paystack_secret_key ?? null,
                'paystack_status'     => $paymentSetting->paystack_status ?? null,
                'charge'              => $paymentSetting->paystack_charge ?? null,
                'paystack_image'      => $paymentSetting->paystack_image ?? null,
                'currency_id'         => $paymentSetting->paystack_currency_id ?? null,
            ],
            self::MOLLIE => (object) [
                'mollie_key'    => $paymentSetting->mollie_key ?? null,
                'charge'        => $paymentSetting->mollie_charge ?? null,
                'mollie_image'  => $paymentSetting->mollie_image ?? null,
                'mollie_status' => $paymentSetting->mollie_status ?? null,
                'currency_id'   => $paymentSetting->mollie_currency_id ?? null,
            ],
            self::INSTAMOJO => (object) [
                'instamojo_account_mode'  => $paymentSetting->instamojo_account_mode ?? null,
                'instamojo_client_id'     => $paymentSetting->instamojo_client_id ?? null,
                'instamojo_client_secret' => $paymentSetting->instamojo_client_secret ?? null,
                'charge'                  => $paymentSetting->instamojo_charge ?? null,
                'instamojo_image'         => $paymentSetting->instamojo_image ?? null,
                'currency_id'             => $paymentSetting->instamojo_currency_id ?? null,
                'instamojo_status'        => $paymentSetting->instamojo_status ?? null,
            ],
            self::SSLCOMMERZ => (object) [
                'sslcommerz_store_id'       => $paymentSetting->sslcommerz_store_id ?? null,
                'sslcommerz_store_password' => $paymentSetting->sslcommerz_store_password ?? null,
                'sslcommerz_image'          => $paymentSetting->sslcommerz_image ?? null,
                'sslcommerz_test_mode'      => $paymentSetting->sslcommerz_test_mode ?? 1,
                'sslcommerz_localhost'      => $paymentSetting->sslcommerz_localhost ?? 1,
                'sslcommerz_status'         => $paymentSetting->sslcommerz_status ?? null,
                'charge'                    => $paymentSetting->sslcommerz_charge ?? 0,
                'currency_id'               => $paymentSetting->sslcommerz_currency_id ?? null,
            ],
            self::CRYPTO => (object) [
                'crypto_sandbox'          => $paymentSetting->crypto_sandbox ?? null,
                'crypto_api_key'          => $paymentSetting->crypto_api_key ?? null,
                'crypto_image'            => $paymentSetting->crypto_image ?? null,
                'crypto_status'           => $paymentSetting->crypto_status ?? null,
                'crypto_receive_currency' => $paymentSetting->crypto_receive_currency ?? null,
                'charge'                  => $paymentSetting->crypto_charge ?? null,
                'currency_id'             => $paymentSetting->crypto_currency_id ?? null,
            ],
            default => $this->previousService->getGatewayDetails($gatewayName),
        };
    }

    /**
     * @param string $gatewayName
     */
    public function isActive(string $gatewayName): bool
    {
        $gatewayDetails = $this->getGatewayDetails($gatewayName);
        $activeStatus   = 'active';

        return match ($gatewayName) {
            self::MOLLIE => $gatewayDetails->mollie_status == $activeStatus,
            self::RAZORPAY => $gatewayDetails->razorpay_status == $activeStatus,
            self::FLUTTERWAVE => $gatewayDetails->flutterwave_status == $activeStatus,
            self::INSTAMOJO => $gatewayDetails->instamojo_status == $activeStatus,
            self::PAYSTACK => $gatewayDetails->paystack_status == $activeStatus,
            self::SSLCOMMERZ => $gatewayDetails->sslcommerz_status == $activeStatus,
            self::CRYPTO => $gatewayDetails->crypto_status == $activeStatus,
            default => $this->previousService->isActive($gatewayName),
        };
    }

    /**
     * @param string $gatewayName
     */
    public function getIcon(string $gatewayName): string
    {
        return match ($gatewayName) {
            self::MOLLIE => 'fa-cc-mollie',
            self::RAZORPAY => 'fa-cc-razorpay',
            self::FLUTTERWAVE => 'fa-cc-flutterwave',
            self::INSTAMOJO => 'fa-cc-instamojo',
            self::PAYSTACK => 'fa-cc-paystack',
            self::SSLCOMMERZ => 'fa-money-bill-alt',
            self::CRYPTO => 'fa-money-bill-alt',
            default => $this->previousService->getIcon($gatewayName),
        };
    }

    /**
     * @param $gatewayName
     */
    public function getLogo($gatewayName): ?string
    {
        $paymentSetting = $this->getPaymentGatewayInfo();

        return match ($gatewayName) {
            self::MOLLIE => $paymentSetting->mollie_image ? asset($paymentSetting->mollie_image) : asset('website/images/gateways/mollie.webp'),
            self::RAZORPAY => $paymentSetting->razorpay_image ? asset($paymentSetting->razorpay_image) : asset('website/images/gateways/razorpay.webp'),
            self::FLUTTERWAVE => $paymentSetting->flutterwave_image ? asset($paymentSetting->flutterwave_image) : asset('website/images/gateways/flutterwave.webp'),
            self::INSTAMOJO => $paymentSetting->instamojo_image ? asset($paymentSetting->instamojo_image) : asset('website/images/gateways/instamojo.webp'),
            self::PAYSTACK => $paymentSetting->paystack_image ? asset($paymentSetting->paystack_image) : asset('website/images/gateways/paystack.webp'),
            self::SSLCOMMERZ => $paymentSetting->sslcommerz_image ? asset($paymentSetting->sslcommerz_image) : asset('website/images/gateways/sslcommerz.webp'),
            self::CRYPTO => $paymentSetting->crypto_status ? asset($paymentSetting->crypto_image) : asset('website/images/gateways/crypto.webp'),
            default => $this->previousService->getLogo($gatewayName),
        };
    }

    /**
     * @param $gatewayName
     * @param $code
     */
    public function isCurrencySupported($gatewayName, $code = null): bool
    {
        if (is_null($code)) {
            $code = getSessionCurrency();
        }

        return match ($gatewayName) {
            self::MOLLIE => PaymentGatewaySupportedCurrencyListEnum::isMollieSupportedCurrencies($code),
            self::RAZORPAY => PaymentGatewaySupportedCurrencyListEnum::isRazorpaySupportedCurrencies($code),
            self::FLUTTERWAVE => PaymentGatewaySupportedCurrencyListEnum::isFlutterwaveSupportedCurrencies($code),
            self::INSTAMOJO => PaymentGatewaySupportedCurrencyListEnum::isInstamojoSupportedCurrencies($code),
            self::PAYSTACK => PaymentGatewaySupportedCurrencyListEnum::isPaystackSupportedCurrencies($code),
            self::SSLCOMMERZ => PaymentGatewaySupportedCurrencyListEnum::isSslcommerzSupportedCurrencies($code),
            self::CRYPTO => PaymentGatewaySupportedCurrencyListEnum::isCryptoSupportedCurrencies($code),
            default => $this->previousService->isCurrencySupported($gatewayName, $code),
        };
    }

    /**
     * @param $gatewayName
     */
    public function getSupportedCurrencies($gatewayName): array
    {
        return match ($gatewayName) {
            self::MOLLIE => PaymentGatewaySupportedCurrencyListEnum::getMollieSupportedCurrencies(),
            self::RAZORPAY => PaymentGatewaySupportedCurrencyListEnum::getRazorpaySupportedCurrencies(),
            self::FLUTTERWAVE => PaymentGatewaySupportedCurrencyListEnum::getFlutterwaveSupportedCurrencies(),
            self::INSTAMOJO => PaymentGatewaySupportedCurrencyListEnum::getInstamojoSupportedCurrencies(),
            self::PAYSTACK => PaymentGatewaySupportedCurrencyListEnum::getPaystackSupportedCurrencies(),
            self::SSLCOMMERZ => PaymentGatewaySupportedCurrencyListEnum::getSslcommerzSupportedCurrencies(),
            self::CRYPTO => PaymentGatewaySupportedCurrencyListEnum::getCryptoSupportedCurrencies(),
            default => $this->previousService->getSupportedCurrencies($gatewayName),
        };
    }

    /**
     * @param string $gatewayName
     */
    public function getBladeView(string $gatewayName): ?string
    {
        return match ($gatewayName) {
            self::MOLLIE => 'basicpayment::gateway-actions.mollie',
            self::RAZORPAY => 'basicpayment::gateway-actions.razorpay',
            self::FLUTTERWAVE => 'basicpayment::gateway-actions.flutterwave',
            self::INSTAMOJO => 'basicpayment::gateway-actions.instamojo',
            self::PAYSTACK => 'basicpayment::gateway-actions.paystack',
            self::SSLCOMMERZ => 'basicpayment::gateway-actions.sslcommerz',
            self::CRYPTO => 'basicpayment::gateway-actions.crypto',
            default => $this->previousService->getBladeView($gatewayName),
        };
    }
}
