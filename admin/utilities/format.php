<?php

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

function yz_join(array $collection, $separator = ' '): string {
    return trim(implode($separator, $collection));
}