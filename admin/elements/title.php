<?php

function yz_title(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $level    = yz_prop($props, 'level', 1);
    $inline   = yz_prop($props, 'inline', false);
    $children = yz_prop($props, 'children');

    $classes = [
        'yuzu',
        'title',
    ];

    if ($inline) {
        $classes[] = 'wp-heading-inline';
    } else {
        $classes[] = 'wp-heading';
    }

    if ($class) {
        $classes[] = $class;
    }

    yz_element('h' . $level, [
        'id' => $id,
        'class' => yz_join($classes),
        'children' => $children
    ]);
}