<?php

class Yz_String {

    public static function format_slug(string $slug): string {
        return strtolower(str_replace(' ', '-', str_replace('&', 'and', $slug)));
    }

    public static function format_field_name(string $name): string {
        return strtolower(str_replace(' ', '_', str_replace('&', 'and', $name)));
    }

    public static function format_currency(
        $value,
        $currency = '$',
        $decimals = 2,
        $decimal_separator = '.',
        $thousands_separator = ','
    ): string {
        return $currency . number_format($value, $decimals, $decimal_separator, $thousands_separator);
    }

    public static function format_capability(string $capability): string {
        $capability    = str_replace('_', ' ', strtolower($capability));
        $capability    = str_replace('-', ' ', $capability);
        $capability[0] = strtoupper($capability[0]);

        return $capability;
    }

    public static function format_data_url(string $mime_type, string $data): string {
        return 'data:' . $mime_type . ';base64,' . base64_encode($data);
    }
}