<?php

function yz_toolbar(array $props): void {
    $id = yz_prop($props, 'id', '');
    $class = yz_prop($props, 'class', '');
    $children = yz_prop($props, 'children');

    $classes = [
        'toolbar'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_card([
        'id' => $id,
        'class' => yz_join($classes),
        'style' => [
            'padding' => '5px 20px',
        ],
        'children' => function() use ($children) {
            yz_flex_layout([
                'gap' => 20,
                'alignment' => 'center',
                'style' => [
                    'height' => '48px'
                ],
                'children' => function() use($children) {
                    if (is_callable($children)) {
                        $children();
                    }
                }
            ]);
        }
    ]);
}