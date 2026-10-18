<?php

namespace App\GP247\Plugins\ShippingStandard\Services;

use App\GP247\Plugins\ShippingStandard\AppConfig;
use GP247\Shop\Shipping\Contracts\ShippingProvider;
use GP247\Shop\Shipping\Data\Money;
use GP247\Shop\Shipping\Data\RateRequest;
use GP247\Shop\Shipping\Data\RateResult;
use GP247\Shop\Shipping\Data\ShippingRate;
use GP247\Shop\Shipping\RateRequestFactory;

/**
 * ShippingStandard on the standard shipping contract: the simplest possible provider —
 * one rate, a flat fee waived above a threshold, no shipment capability. The fee and
 * the threshold are read for the request's store, in base currency, and returned in
 * base currency (the core converts to display once) — the same figures getInfo()
 * computed (US-shipping-standard-provider-contract).
 *
 * @aidlc-unit plugin-shipping-standard
 * @aidlc-story US-shipping-standard-provider-contract
 * @aidlc-adr shop-shipping_provider-capability-contract
 */
class StandardShippingProvider implements ShippingProvider
{
    /**
     * @param RateRequest $request Parcel; only the declared value matters here.
     * @return RateResult One rate.
     */
    public function rates(RateRequest $request): RateResult
    {
        $config = new AppConfig();
        $fee = $config->baseSetting('fee', $request->storeId);
        $freeFrom = $config->baseSetting('shipping_free', $request->storeId);
        $base = RateRequestFactory::baseCurrency();

        // WHY compare in base: declaredValue is the cart value in base currency, the same
        // unit the owner typed the threshold in (no display-currency rounding involved).
        $declared = strtoupper($request->declaredValue->currency) === $base ? $request->declaredValue->value() : 0.0;
        if ($declared >= $freeFrom) {
            $fee = 0.0;
        }

        return new RateResult([new ShippingRate(
            pluginKey: 'ShippingStandard',
            serviceCode: '',
            title: (string) $config->title,
            amount: new Money((string) $fee, $base),
            meta: ['image' => (string) $config->image],
        )]);
    }
}
