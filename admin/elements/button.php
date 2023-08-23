<?php

const YUZU_BUTTON_VALID_TYPES = [
    'button',
    'submit',
    'reset',
    'link'
];

const YUZU_BUTTON_VALID_VARIANTS = [
    'primary',
    'secondary'
];

const YUZU_BUTTON_VALID_SIZES = [
    'small',
    'medium',
    'large',
    'hero'
];

const YUZU_BUTTON_VALID_COLORS = [
    'primary',
    'secondary',
    'success',
    'warning',
    'danger',
    'info'
];

function yz_button(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $label    = yz_prop($props, 'label', '');
    $href     = yz_prop($props, 'href', '');
    $icon     = yz_prop($props, 'icon', '');
    $type     = yz_prop($props, 'type', 'button');
    $size     = yz_prop($props, 'size', 'medium');
    $variant  = yz_prop($props, 'variant', 'secondary');
    $color    = yz_prop($props, 'color', 'primary');
    $class    = yz_prop($props, 'class', '');
    $disabled = yz_prop($props, 'disabled', false);

    assert(in_array($type,    YUZU_BUTTON_VALID_TYPES),    'Invalid button type');
    assert(in_array($size,    YUZU_BUTTON_VALID_SIZES),    'Invalid button size');
    assert(in_array($variant, YUZU_BUTTON_VALID_VARIANTS), 'Invalid button variant');
    assert(in_array($color,   YUZU_BUTTON_VALID_COLORS),   'Invalid button color');

    $class_names = [
        'yuzu',
        'button'
    ];

    if ($size) {
        $class_names[] = 'button-' . $size;
    }

    if ($variant) {
        $class_names[] = 'button-' . $variant;
    }

    if ($color) {
        $class_names[] = 'button-color-' . $color;
    }

    if ($class) {
        $class_names[] = $class;
    }

    if ($type === 'link') {
        yz_element('a', [
            'id'         => $id,
            'class'      => yz_join($class_names),
            'attributes' => ['href' => $href],
            'children'   => function() use($icon, $label) {
                echo $icon;
                echo $label;
            }
        ]);
    } else {
        yz_element('button', [
            'id'         => $id,
            'class'      => yz_join($class_names),
            'attributes' => ['type' => $type, 'disabled' => $disabled],
            'children'   => function() use($icon, $label) {
                echo $icon;
                echo $label;
            }
        ]);
    }
}