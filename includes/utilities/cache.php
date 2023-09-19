<?php

class Yz_Cache {
    private static array $cache = [];

    public static function get(string $key): mixed {
        return Yz_Array::value_or(Yz_Cache::$cache, $key);
    }

    public static function set(string $key, mixed $value): void {
        Yz_Cache::$cache[$key] = $value;
    }

    public static function do_once(string $key, callable $callback): void {
        $cache = Yz_Cache::get($key);

        if (!$cache) {
            Yz_Cache::set($key, true);
            $callback();
        }
    }
}