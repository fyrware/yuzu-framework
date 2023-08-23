<?php

function yz_card(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $children = yz_prop($props, 'children');
    $style    = yz_prop($props, 'style', []);

    $classes = [
        'yuzu',
        'card'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element('section', [
        'id' => $id,
        'class' => yz_join($classes),
        'children' => $children,
        'style' => yz_css($style)
    ]);
}