<?php

const YUZU_PARAGRAPH_VALID_VARIANTS = [
    'default',
    'description'
];

function yz_paragraph(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $variant  = yz_prop($props, 'variant', 'default');
    $children = yz_prop($props, 'children');

    assert(in_array($variant, YUZU_PARAGRAPH_VALID_VARIANTS), 'Invalid paragraph variant');

    $classes = [
        'yuzu',
        'paragraph'
    ];

    if ($variant) {
        $classes[] = $variant;
    }

    if ($class) {
        $classes[] = $class;
    }

    yz_element('p', [
        'id'       => $id,
        'class'    => yz_join($classes),
        'children' => $children
    ]);
}