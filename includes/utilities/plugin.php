<?php

class Yz_Plugin {

    public static function is_active(string $plugin_name): bool {
        return is_plugin_active($plugin_name . '/' . $plugin_name . '.php');
    }

    public static function get_option(string $option, mixed $default = null): mixed {
        return apply_filters('get_option_' . $option, get_option($option, $default));
    }

    public static function set_option(string $option, mixed $value): bool {
        return update_option($option, apply_filters('set_option_' . $option, $value));
    }
}