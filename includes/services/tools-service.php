<?php

class Yz_Tools_Service {

    public function __construct() {}

    public function get_value(array $array, mixed $key, mixed $default = null) {
        return array_key_exists($key, $array) ? $array[$key] ?? $default : $default;
    }

    public function get_first(array $array, mixed $default = null) {
        return reset($array) ?: $default;
    }

    public function get_last(array $array, mixed $default = null) {
        return end($array) ?: $default;
    }

    public function get_query_arg(string $key, mixed $default = null) {
        return $this->get_value($_GET, $key, $default);
    }

    public function split_string(string $string, string $delimiter = ' '): array {
        return explode($delimiter, $string);
    }

    public function join_values(array $array, string $glue = ' '): string {
        return implode($glue, $array);
    }

    public function join_key_value_pairs(array $array, string $glue = ': ', string $pair_glue = '; '): string {
        $pairs = [];

        foreach ($array as $key => $value) {
            $pairs[] = $key . $glue . $value;
        }

        return implode($pair_glue, $pairs);
    }

    public function capture_buffer(callable $callback): string {
        ob_start();
        $callback();
        return ob_get_clean();
    }

    public function format_data_url(string $mime_type, string $data): string {
        return 'data:' . $mime_type . ';base64,' . base64_encode($data);
    }

    public function format_field_name(string $name): string {
        return strtolower(str_replace(' ', '_', str_replace('&', 'and', $name)));
    }

    public function format_url_slug(string $slug): string {
        return strtolower(str_replace(' ', '-', str_replace('&', 'and', $slug)));
    }

    public function format_currency(
        $value,
        $currency = '$',
        $decimals = 2,
        $decimal_separator = '.',
        $thousands_separator = ','
    ): string {
        return $currency . number_format($value, $decimals, $decimal_separator, $thousands_separator);
    }

    public function filter_array($array, $callback): array {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public function sort_array($array, $callback): array {
        uasort($array, $callback);
        return $array;
    }

    public function index_of($array, $value) {
        return array_search($value, $array);
    }

    public function first($array) {
        return $array[array_key_first($array)];
    }

    public function last($array) {
        return $array[array_key_last($array)];
    }

    public function find_value($array, $callback) {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
    }

    public function has_value(array $array, callable $callback): bool {
        return $this->find_value($array, $callback) !== null;
    }

    public function console_log(mixed ...$values) { ?>
        <script>
            console.log(...<?= json_encode($values) ?>);
        </script>
    <?php }
}