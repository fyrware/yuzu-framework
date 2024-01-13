<?php

/** @deprecated */
class Yz_Array {

    /** @deprecated */
    public static function value_or(array $array, string $key, $default = null): mixed {
        return $array[$key] ?? $default;
    }

    /** @deprecated */
    public static function join(array $array, string $glue = ' '): string {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = Yz_Array::join($value, $glue);
            }
        }
        return trim(implode($glue, $array));
    }

    /** @deprecated */
    public static function join_key_value(array $array, string $eq = ': ', string $glue = '; '): string {
        $pairs = [];

        foreach ($array as $key => $value) {
            $pairs[] = Yz_Array::join([$key, $value], $eq);
        }

        return trim(Yz_Array::join($pairs, $glue) . (empty($array) ? '' : $glue));
    }

    /** @deprecated */
    public static function merge(array ...$arrays): array {
        return array_merge(...$arrays);
    }

    /** @deprecated */
    public static function find($array, $callback) {
        foreach ($array as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }
    }

    /** @deprecated */
    public static function first($array) {
        return $array[array_key_first($array)];
    }

    /** @deprecated */
    public static function last($array) {
        return $array[array_key_last($array)];
    }

    /** @deprecated */
    public static function index_of($array, $value) {
        return array_search($value, $array);
    }

    /** @deprecated */
    public static function filter($array, $callback) {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    /** @deprecated */
    public static function sort($array, $callback) {
        uasort($array, $callback);
        return $array;
    }
}