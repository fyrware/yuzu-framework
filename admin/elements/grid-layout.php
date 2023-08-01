<?php

function yz_grid_layout(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $columns  = yz_prop($props, 'columns', 0);
    $gap      = yz_prop($props, 'gap', 0);
    $children = yz_prop($props, 'children');
    $items    = yz_prop($props, 'items', []);

    assert(is_array($items), 'Items must be an array');

    if (is_int($gap) || is_double($gap)) {
        $gap .= 'px';
    }

    $styles = [];
    $classes = [
        'yuzu',
        'grid-layout'
    ];

    if ($class) {
        $classes[] = $class;
    }

    if ($columns) {
        if (is_string($columns)) {
            $styles['grid_template_columns'] = $columns;
        } else if (is_array($columns)) {
            $styles['grid_template_columns'] = implode(' ', $columns);
        } else {
            $styles['grid_template_columns'] = 'repeat(' . $columns . ', 1fr)';
        }
    }

    if (isset($props['gap'])) {
        if (is_string($gap)) {
            $styles['gap'] = $gap;
        } else if (is_array($gap)) {
            $styles['row_gap'] = $gap[0];
            $styles['column_gap'] = $gap[1];
        } else {
            $styles['gap'] = $gap . 'px';
        }
    }

    yz_element('section', [
        'id'       => $id,
        'class'    => yz_join($classes),
        'style'    => yz_format_css($styles),
        'children' => function() use($children, $items) {
            if ($children) {
                $children();
            }
            foreach ($items as $item) {
                $item_id       = yz_prop($item, 'id', '');
                $item_class    = yz_prop($item, 'class', '');
                $item_shape    = yz_prop($item, 'shape', '');
                $item_children = yz_prop($item, 'children');

                $item_classes = [
                    'yuzu',
                    'grid-item'
                ];

                if ($item_class) {
                    $item_classes[] = $item_class;
                }

                if ($item_shape) {
                    $item_classes[] = 'grid-item-shape-' . $item_shape;
                }

                yz_element([
                    'id'       => $item_id,
                    'class'    => yz_join($item_classes),
                    'children' => $item_children
                ]);
            }
        }
    ]);
}