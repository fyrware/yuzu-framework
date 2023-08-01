<?php

function yz_table(array $props): void {
    $id         = yz_prop($props, 'id', '');
    $class      = yz_prop($props, 'class', '');
    $fixed      = yz_prop($props, 'fixed', false);
    $striped    = yz_prop($props, 'striped', false);
    $selectable = yz_prop($props, 'selectable', false);
    $columns    = yz_prop($props, 'columns', []);
    $rows       = yz_prop($props, 'rows', []);

    assert(is_bool($fixed));
    assert(is_bool($striped));
    assert(is_bool($selectable));
    assert(is_array($columns));
    assert(is_array($rows));

    $classes = [
        'yuzu',
        'table',
        'wp-list-table',
        'widefat'
    ];

    if ($fixed) {
        $classes[] = 'fixed';
    }

    if ($striped) {
        $classes[] = 'striped';
    }

    if ($selectable) {
        $classes[] = 'selectable';
    }

    if ($class) {
        $classes[] = $class;
    }

    yz_element('table', [
        'id' => $id,
        'class' => yz_join($classes),
        'children' => function() use($selectable, $columns, $rows) {
            yz_element('thead', [
                'children' => function() use($selectable, $columns) {
                    yz_element('tr', [
                        'class'    => 'yuzu table-row',
                        'children' => function() use($selectable, $columns) {
                            if ($selectable) {
                                yz_element('th', [
                                    'class'    => 'yuzu table-select-column',
                                    'children' => function() {
                                        yz_element('input', ['attributes' => ['type' => 'checkbox']]);
                                    }
                                ]);
                            }
                            foreach ($columns as $column) {
                                $col_sortable = yz_prop($column, 'sortable', false);
                                $col_children = yz_prop($column, 'children');

                                yz_element('th', [
                                    'class'      => $col_sortable ? 'sortable desc' : '',
                                    'attributes' => ['scope'    => 'col'],
                                    'children'   => $col_children
                                ]);
                            }
                        }
                    ]);
                }
            ]);
            yz_element('tbody', [
                'class'    => 'yuzu table-body',
                'children' => function() use($rows) {
                    foreach ($rows as $row) {
                        $row_id       = yz_prop($row, 'id', '');
                        $row_class    = yz_prop($row, 'class', '');
                        $row_children = yz_prop($row, 'children', []);

                        $row_classes = [
                            'yuzu',
                            'table-row'
                        ];

                        if ($row_class) {
                            $row_classes[] = $row_class;
                        }

                        yz_element('tr', [
                            'id'       => $row_id,
                            'class'    => yz_join($row_classes),
                            'children' => function() use($row_children) {
                                foreach ($row_children as $cell) {
                                    $cell_id       = yz_prop($cell, 'id', '');
                                    $cell_class    = yz_prop($cell, 'class', '');
                                    $cell_children = yz_prop($cell, 'children');

                                    $cell_classes = [
                                        'yuzu',
                                        'table-data'
                                    ];

                                    if ($cell_class) {
                                        $cell_classes[] = $cell_class;
                                    }

                                    yz_element('td', [
                                        'id'       => $cell_id,
                                        'class'    => yz_join($cell_classes),
                                        'children' => $cell_children
                                    ]);
                                }
                            }
                        ]);
                    }
                }
            ]);
        }
    ]);
}