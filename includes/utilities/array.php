<?php

class Yz_Array {

    public static function value_or(array $array, string $key, $default = null): mixed {
        return $array[$key] ?? $default;
    }

    public static function join(array $array, string $glue = ' '): string {
        return trim(implode($glue, $array));
    }

    public static function join_key_value(array $array, string $eq = ': ', string $glue = '; '): string {
        $pairs = [];

        foreach ($array as $key => $value) {
            $pairs[] = Yz_Array::join([$key, $value], $eq);
        }

        return trim(Yz_Array::join($pairs, $glue) . (empty($array) ? '' : $glue));
    }

    public static function merge(array ...$arrays): array {
        return array_merge(...$arrays);
    }

    public static function find($array, $callback) {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
    }

    public static function first($array) {
        return $array[array_key_first($array)];
    }

    public static function last($array) {
        return $array[array_key_last($array)];
    }

    public static function index_of($array, $value) {
        return array_search($value, $array);
    }

    public static function filter($array, $callback) {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    public static function sort($array, $callback) {
        uasort($array, $callback);
        return $array;
    }
}