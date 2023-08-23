<?php

const YUZU_FLEX_LAYOUT_VALID_DIRECTIONS = [
    'row',
    'row-reverse',
    'column',
    'column-reverse'
];

const YUZU_FLEX_LAYOUT_VALID_JUSTIFICATIONS = [
    'start',
    'end',
    'center',
    'between',
    'around',
    'evenly'
];

const YUZU_FLEX_LAYOUT_VALID_ALIGNMENTS = [
    'start',
    'end',
    'center',
    'baseline',
    'stretch'
];

function yz_flex_layout(array $props): void {
    $as            = yz_prop($props, 'as', 'section');
    $id            = yz_prop($props, 'id', '');
    $inline        = yz_prop($props, 'inline', false);
    $direction     = yz_prop($props, 'direction', 'row');
    $justification = yz_prop($props, 'justification', 'start');
    $alignment     = yz_prop($props, 'alignment', 'stretch');
    $wrap          = yz_prop($props, 'wrap', '');
    $class_name    = yz_prop($props, 'class', '');
    $style         = yz_prop($props, 'style', []);
    $gap           = yz_prop($props, 'gap', 0);
    $children      = yz_prop($props, 'children');
    $items         = yz_prop($props, 'items', []);

    assert(is_string($as));
    assert(is_bool($inline));
    assert(in_array($direction,     YUZU_FLEX_LAYOUT_VALID_DIRECTIONS));
    assert(in_array($justification, YUZU_FLEX_LAYOUT_VALID_JUSTIFICATIONS));
    assert(in_array($alignment,     YUZU_FLEX_LAYOUT_VALID_ALIGNMENTS));
    assert(is_array($items));

    if ($gap && (is_int($gap) || is_double($gap))) {
        $gap .= 'px';
    }

    if ($gap) {
        $style['gap'] = $gap;
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

    yz_element($as, [
        'id'       => $id,
        'class'    => trim(implode(' ', $classes)),
        'style'    => yz_format_css($style),
        'children' => function() use($children, $items) {
            if ($children) $children();
            foreach ($items as $item_props) {
                $item_id       = yz_prop($item_props, 'id', '');
                $item_grow     = yz_prop($item_props, 'grow', null);
                $item_shrink   = yz_prop($item_props, 'shrink', null);
                $item_class    = yz_prop($item_props, 'class', '');
                $item_children = yz_prop($item_props, 'children');
                $item_style    = yz_prop($item_props, 'style', []);

                $item_class_names = [
                    'yuzu',
                    'flex-item'
                ];

                if ($item_class) {
                    $item_class_names[] = $item_class;
                }

                if (isset($item_shrink)) {
                    $item_style['flex_shrink'] = $item_shrink;
                }

                if (isset($item_grow)) {
                    $item_style['flex_grow'] = $item_grow;
                }

                if (isset($item_shrink) && $item_shrink && isset($item_grow) && $item_grow) {
                    $item_style['width'] = 0;
                }

                yz_element([
                    'id'       => $item_id,
                    'class'    => trim(implode(' ', $item_class_names)),
                    'style'    => yz_format_css($item_style),
                    'children' => $item_children
                ]);
            }
        }
    ]);
}