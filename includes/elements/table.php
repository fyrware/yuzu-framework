<?php

class Yz_Table {

    public static function render(array $props): void  {
        $id = Yz_Array::value_or($props, 'id');
        $class = Yz_Array::value_or($props, 'class');
        $fixed = Yz_Array::value_or($props, 'fixed', true);
        $striped = Yz_Array::value_or($props, 'striped', true);
        $columns = Yz_Array::value_or($props, 'columns', []);
        $rows = Yz_Array::value_or($props, 'rows', []);

        $classes = [
            'yz',
            'table'
        ];

        if ($fixed) {
            $classes[] = 'fixed';
        }

        if ($striped) {
            $classes[] = 'striped';
        }

        if ($class) {
            $classes[] = $class;
        }

        Yz::Element('table', [
            'id' => $id,
            'class' => implode(' ', $classes),
            'children' => function() use($columns, $rows) {
                Yz::Element('thead', [
                    'children' => function() use($columns) {
                        Yz::Element('tr', [
                            'children' => function() use($columns) {
                                foreach ($columns as $column) {
                                    Yz::Element('th', [
                                        'children' => $column
                                    ]);
                                }
                            }
                        ]);
                    }
                ]);
                Yz::Element('tbody', [
                    'children' => function() use($rows) {
                        foreach ($rows as $row) {
                            Yz::Element('tr', [
                                'children' => function() use($row) {
                                    foreach ($row as $cell) {
                                        Yz::Element('td', [
                                            'children' => $cell
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

    public static function render_style(): void { ?>
        <style>
            .yz.table {
                width: 100%;
                margin: 0;
                border: 1px solid #c3c4c7;
                border-spacing: 0;
                border-radius: 4px;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                background: #fff;
            }

            .yz.table.fixed {
                table-layout: fixed;
            }

            .yz.table thead tr > * {
                padding: 8px 10px;
                border-bottom: 1px solid #c3c4c7;
                line-height: 1.4em;
                text-align: left;
                font-size: 14px;
                font-weight: 400;
                color: #2c3338;
            }

            .yz.table.striped tbody tr:nth-child(odd) {
                background: none;
            }

            .yz.table.striped tbody tr:nth-child(odd) > * {
                background-color: #f6f7f7;
            }

            .yz.table tbody tr:last-child > *:first-child {
                border-bottom-left-radius: 4px;
            }

            .yz.table tbody tr:last-child > *:last-child {
                border-bottom-right-radius: 4px;
            }

            .yz.table tbody tr > * {
                padding: 8px 10px;
            }
        </style>
    <?php }
}