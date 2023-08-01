<?php

function yz_meta_box(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $title    = yz_prop($props, 'title', '');
    $children = yz_prop($props, 'children');

    $classes = [
        'yuzu',
        'metabox',
        'postbox'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element('section', [
        'id'       => $id,
        'class'    => yz_join($classes),
        'children' => function() use($title, $children) {
            yz_element('header', [
                'class'    => 'yuzu metabox-header postbox-header',
                'children' => function() use($title) {
                    yz_element('h2', [
                        'class'    => 'yuzu handle hndle ui-sortable-handle',
                        'children' => function() use($title) {
                            yz_text($title);
                        }
                    ]);
                }
            ]);
            yz_element([
                'class'    => 'yuzu inside',
                'children' => $children
            ]);
        }
    ]);
}