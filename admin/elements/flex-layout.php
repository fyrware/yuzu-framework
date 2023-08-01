<?php

function yz_flex_layout(array $props): void {
    $id            = yz_prop($props, 'id', '');
    $inline        = yz_prop($props, 'inline', false);
    $direction     = yz_prop($props, 'direction', '');
    $justification = yz_prop($props, 'justification', '');
    $alignment     = yz_prop($props, 'alignment', '');
    $wrap          = yz_prop($props, 'wrap', '');
    $class_name    = yz_prop($props, 'class', '');
    $gap           = yz_prop($props, 'gap', 0);
    $children      = yz_prop($props, 'children');
    $items         = yz_prop($props, 'items', []);

    if (is_int($gap) || is_double($gap)) {
        $gap .= 'px';
    }

    $classes = [
        'yuzu',
        'flex-layout'
    ];

    if ($inline) {
        $classes[] = 'flex-inline';
    }

    if ($direction) {
        $classes[] = 'flex-direction-' . $direction;
    }

    if ($justification) {
        $classes[] = 'flex-justification-' . $justification;
    }

    if ($alignment) {
        $classes[] = 'flex-alignment-' . $alignment;
    }

    if ($wrap) {
        $classes[] = 'flex-wrap-' . $wrap;
    }

    if ($class_name) {
        $classes[] = $class_name;
    }

    yz_element('section', [
        'id'       => $id,
        'class'    => trim(implode(' ', $classes)),
        'style'    => yz_format_css(['gap' => $gap]),
        'children' => function() use($children, $items) {
            if ($children) $children();
            foreach ($items as $item_props) {
                $item_id       = yz_prop($item_props, 'id', '');
                $item_grow     = yz_prop($item_props, 'grow', false);
                $item_shrink   = yz_prop($item_props, 'shrink', false);
                $item_class    = yz_prop($item_props, 'class', '');
                $item_children = yz_prop($item_props, 'children');

                $item_class_names = [
                    'yuzu',
                    'flex-item'
                ];

                if ($item_grow) {
                    $item_class_names[] = 'flex-grow';
                }

                if ($item_shrink) {
                    $item_class_names[] = 'flex-shrink';
                }

                if ($item_class) {
                    $item_class_names[] = $item_class;
                }

                yz_element([
                    'id'       => $item_id,
                    'class'    => trim(implode(' ', $item_class_names)),
                    'children' => $item_children
                ]);
            }
        }
    ]);
}