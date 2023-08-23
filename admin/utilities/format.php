<?php

function yz_format_slug(string $text, bool $underscore = true): string {
    return $underscore ? str_replace('-', '_', sanitize_title($text)) : sanitize_title($text);
}

function yz_format_currency(
    $value,
    $currency = '$',
    $decimals = 2,
    $decimal_separator = '.',
    $thousands_separator = ','
): string {
    return $currency . number_format($value, $decimals, $decimal_separator, $thousands_separator);
}

function yz_format_css(array $style): string {
    $css = '';

    foreach ($style as $property => $value) {
        $css .= str_replace('_', '-', $property) . ':' . $value . ';';
    }

    return $css;
}

function yz_format_capability(string $capability): string {
    $capability    = str_replace('_', ' ', strtolower($capability));
    $capability    = str_replace('-', ' ', $capability);
    $capability[0] = strtoupper($capability[0]);

    return $capability;
}

function yz_join(array $collection, $separator = ' '): string {
    return trim(implode($separator, $collection));
}