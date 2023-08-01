<?php

const YUZU_TEXT_VALID_VARIANTS = [
    'label',
    'span',
    'strong',
    'em'
];

function yz_text(string $content = '', array $props = []): void {
    $id         = yz_prop($props, 'id', '');
    $variant    = yz_prop($props, 'variant', 'span');
    $class      = yz_prop($props, 'class', '');
    $style      = yz_prop($props, 'style', '');
    $attributes = yz_prop($props, 'attributes', []);
    $aria_set   = yz_prop($props, 'aria_set', []);
    $data_set   = yz_prop($props, 'data_set', []);

    assert(is_string($content), 'Content must be a string');
    assert(in_array($variant, YUZU_TEXT_VALID_VARIANTS), 'Invalid text variant');

    $classes = [
        'yuzu',
        'text',
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'tag'        => $variant,
        'id'         => $id,
        'class'      => yz_join($classes),
        'style'      => $style,
        'attributes' => $attributes,
        'aria_set'   => $aria_set,
        'data_set'   => $data_set,
        'children'   => function() use ($content) {
            echo $content;
        }
    ]);
}