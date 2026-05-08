<?php

if (!function_exists('get_tax_rate')) {
    /**
     * Get tax rate decimal from tax slab string
     */
    function get_tax_rate(?string $slab): float
    {
        return match (strtolower((string) $slab)) {
            '0', 'nil_rate' => 0.00,
            '5', '5_slab' => 0.05,
            '18', '18_slab' => 0.18,
            '40', '40_slab' => 0.40,
            'special_rates' => 0.28,
            default => 0.00
        };
    }
}

if (!function_exists('format_tax_rate_label')) {
    function format_tax_rate_label(float $rate): string
    {
        $percentage = rtrim(rtrim(number_format($rate * 100, 2, '.', ''), '0'), '.');

        return $percentage . '%';
    }
}

if (!function_exists('calculate_cart_item_tax')) {
    function calculate_cart_item_tax(array $item): float
    {
        $price = (float) ($item['price'] ?? 0);
        $quantity = (int) ($item['quantity'] ?? 0);
        $rate = get_tax_rate($item['tax_slab'] ?? '0');

        return $price * $quantity * $rate;
    }
}

if (!function_exists('cart_tax_rates')) {
    function cart_tax_rates(array $cartItems): array
    {
        return array_values(array_unique(array_filter(array_map(
            static fn (array $item): string => format_tax_rate_label(get_tax_rate($item['tax_slab'] ?? '0')),
            array_filter($cartItems, static fn ($item): bool => get_tax_rate($item['tax_slab'] ?? '0') > 0)
        ))));
    }
}

if (!function_exists('cart_tax_label')) {
    function cart_tax_label(array $cartItems): string
    {
        $rates = cart_tax_rates($cartItems);

        if ($rates === []) {
            return 'Tax';
        }

        return 'Tax (' . implode(', ', $rates) . ')';
    }
}

if (!function_exists('cart_summary')) {
    function cart_summary(array $cartItems): array
    {
        $subtotal = array_sum(array_map(
            static fn (array $item): float => (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 0),
            $cartItems
        ));

        $tax = array_sum(array_map(
            static fn (array $item): float => calculate_cart_item_tax($item),
            $cartItems
        ));

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $subtotal + $tax,
            'tax_label' => cart_tax_label($cartItems),
        ];
    }
}

