<?php

function yz_image(string $src, array $props = []): void {
    $id    = yz_prop($props, 'id');
    $class = yz_prop($props, 'class');
    $alt   = yz_prop($props, 'alt', '');

    $classes = [
        'yuzu',
        'image'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'tag'        => 'img',
        'id'         => $id,
        'class'      => yz_join($classes),
        'attributes' => [
            'src' => $src,
            'alt' => $alt
        ]
    ]);
}