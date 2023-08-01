<?php

function yz_portal(string $name, array $props = []): void {
    $id    = yz_prop($props, 'id', $name);
    $class = yz_prop($props, 'class', '');

    $classes = [
        'yuzu',
        'portal'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'id'       => $id,
        'class'    => yz_join($classes),
        'data_set' => ['portal' => $name],
        'children' => function() use ($name) {
            do_action('yuzu_portal_' . $name);
        }
    ]);
}

function yz_portal_inject(string $name, callable $children): void {
    add_action('yuzu_portal_' . $name, $children);
}
