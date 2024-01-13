<?php

class Yz_Table {

    public static function render(array $props): void  {
        global $yz;

        $id      = $yz->tools->key_or_default($props, 'id');
        $class   = $yz->tools->key_or_default($props, 'class');
        $fixed    = $yz->tools->key_or_default($props, 'fixed', true);
        $striped = $yz->tools->key_or_default($props, 'striped', true);
        $columns = $yz->tools->key_or_default($props, 'columns', []);
        $rows    = $yz->tools->key_or_default($props, 'rows', []);

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

        $yz->html->element('table', [
            'id' => $id,
            'class' => $classes,
            'children' => function() use($yz, $columns, $rows) {
                $yz->html->element('thead', [
                    'children' => function() use($yz, $columns) {
                        $yz->html->element('tr', [
                            'children' => function() use($yz, $columns) {
                                foreach ($columns as $column) {
                                    $yz->html->element('th', [
                                        'children' => $column
                                    ]);
                                }
                            }
                        ]);
                    }
                ]);
                $yz->html->element('tbody', [
                    'children' => function() use($yz, $rows) {
                        foreach ($rows as $row) {
                            $yz->html->element('tr', [
                                'children' => function() use($yz, $row) {
                                    foreach ($row as $cell) {
                                        $yz->html->element('td', [
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