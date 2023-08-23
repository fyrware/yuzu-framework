<?php

function yz_avatar(array $props): void {
    $id    = yz_prop($props, 'id', '');
    $class = yz_prop($props, 'class', '');
    $size  = yz_prop($props, 'size', 80);
    $src   = yz_prop($props, 'src', 'https://i.pravatar.cc/' . $size . '?u=' . rand(0, 1000));

    $classes = [
        'yuzu',
        'avatar'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element('img', [
        'id' => $id,
        'class' => yz_join($classes),
        'attributes' => [
            'src' => $src,
            'width' => $size,
            'height' => $size
        ],
        'style' => yz_css([
            'border-radius' => '50%',
            'object-fit' => 'cover'
        ])
    ]);
}