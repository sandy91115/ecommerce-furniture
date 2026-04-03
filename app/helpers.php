<?php

require_once __DIR__ . '/helpers/image_helpers.php';
require_once __DIR__ . '/helpers/tax_helpers.php';

if (! function_exists('currency_symbol')) {
    /**
     * Get the active currency symbol from settings.
     */
    function currency_symbol(): string
    {
        return \App\Models\Setting::get(\App\Models\Setting::CURRENCY_SYMBOL, '$') ?: '$';
    }
}

if (! function_exists('currency')) {
    /**
     * Format currency amount with dynamic symbol from settings.
     *
     * @param float|int|string $amount
     * @param int $decimals
     * @return string
     */
    function currency($amount, int $decimals = 2): string
    {
        return currency_symbol() . number_format((float) $amount, $decimals);
    }
}

