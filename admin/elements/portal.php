<?php

$yuzu_registered_portals = [];

function yz_portal(string $name = 'default_portal', array $props = []): void {
    global $yuzu_registered_portals;

    $id    = yz_prop($props, 'id', $name);
    $class = yz_prop($props, 'class', '');

    $classes = [
        'yuzu',
        'portal'
    ];

    if ($class) {
        $classes[] = $class;
    }

    if (!isset($yuzu_registered_portals[$name])) {
        $yuzu_registered_portals[$name] = '';
    }

    yz_element([
        'id'       => $id,
        'class'    => yz_join($classes),
        'data_set' => ['portal' => $name],
        'children' => function() use ($name) {
            global $yuzu_registered_portals;
            echo $yuzu_registered_portals[$name] ?? '';
        }
    ]);
}

function yz_portal_inject(string $name, callable $children): void {
    global $yuzu_registered_portals;

    if (!isset($yuzu_registered_portals[$name])) {
        $yuzu_registered_portals[$name] = '';
    }

    $yuzu_registered_portals[$name] .= yz_capture(fn() => $children());
}
