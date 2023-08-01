<?php

function yz_prop($props, $key, $default = null) {
    return isset($props[$key]) ? $props[$key] : $default;
}