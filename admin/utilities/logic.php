<?php

function yz_prop($props, $key, $default = null) {
    return $props[$key] ?? $default;
}

function yz_plugin_activated($plugin_name): bool {
    return is_plugin_active($plugin_name . '/' . $plugin_name . '.php');
}