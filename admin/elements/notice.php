<?php

const YUZU_NOTICE_VALID_VARIANTS = [
    'success',
    'warning',
    'error',
    'info'
];

function yz_notice(array $props): void {
    $id          = yz_prop($props, 'id', '');
    $class       = yz_prop($props, 'class', '');
    $alt         = yz_prop($props, 'alt', false);
    $dismissible = yz_prop($props, 'dismissible', false);
    $variant     = yz_prop($props, 'variant', 'info');
    $title       = yz_prop($props, 'title', '');
    $icon        = yz_prop($props, 'icon', '');
    $children    = yz_prop($props, 'children');
    $inline      = yz_prop($props, 'inline', false);

    assert(in_array($variant, YUZU_NOTICE_VALID_VARIANTS), 'Invalid variant');
    assert(is_string($title), 'Title must be a string');

    $classes = [
        'yuzu',
        'notice'
    ];

    if ($alt) {
        $classes[] = 'notice-alt';
    }

    if ($dismissible) {
        $classes[] = 'is-dismissible';
    }

    if ($inline) {
        $classes[] = 'inline';
    }

    if ($variant) {
        $classes[] = 'notice-' . $props['variant'];
    }

    if ($class) {
        $classes[] = $props['class'];
    }

    yz_element([
        'id' => $id,
        'class' => yz_join($classes),
        'children' => function() use($icon, $title, $children) {
            yz_paragraph([
                'children' => function() use($icon, $title, $children) {
                    if ($icon) echo $icon;
                    if ($title) yz_text($title, ['variant' => 'strong']);
                    if ($children) $children();
                }
            ]);
        }
    ]);
}
