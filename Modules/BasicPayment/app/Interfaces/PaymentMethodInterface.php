<?php

namespace Modules\BasicPayment\app\Interfaces;

interface PaymentMethodInterface
{
    /**
     * Get the name of a specific payment gateway.
     */
    public function getPaymentName(string $gatewayName): ?string;

    /**
     * Get details for a specific payment gateway.
     */
    public function getGatewayDetails(string $gatewayName): ?object;

    /**
     * Check if a specific payment gateway is active.
     */
    public function isActive(string $gatewayName): bool;

    /**
     * Get the icon associated with a specific payment gateway.
     */
    public function getIcon(string $gatewayName): string;

    /**
     * Get the logo associated with a specific payment gateway.
     */
    public function getLogo(string $gatewayName): ?string;

    /**
     * Check if a specific currency is supported by a payment gateway.
     *
     * @param  string|null  $code
     */
    public function isCurrencySupported(string $gatewayName, $code = null): bool;

    /**
     * Get the list of supported currencies for a specific payment gateway.
     */
    public function getSupportedCurrencies(string $gatewayName): array;

    public function getBladeView(string $gatewayName): ?string;
}
