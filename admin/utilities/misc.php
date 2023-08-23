<?php

function yz_get_option(string $option, mixed $default = null): mixed {
    return apply_filters('get_option_' . $option, get_option($option, $default));
}

function yz_set_option(string $option, mixed $value): bool {
    return update_option($option, apply_filters('set_option_' . $option, $value));
}