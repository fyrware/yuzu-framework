<?php

class Yz_Grid_Layout {

    public static function render(array $props): void {
        global $yz;

        $id       = $yz->tools->key_or_default($props, 'id');
        $class    = $yz->tools->key_or_default($props, 'class');
        $data     = $yz->tools->key_or_default($props, 'data', []);
        $rows     = $yz->tools->key_or_default($props, 'rows', 0);
        $columns  = $yz->tools->key_or_default($props, 'columns', 0);
        $gap      = $yz->tools->key_or_default($props, 'gap');
        $children = $yz->tools->key_or_default($props, 'children');
        $as       = $yz->tools->key_or_default($props, 'as', 'section');

        if (is_int($gap) || is_double($gap)) {
            $gap .= 'px';
        }

        $styles = [];

        if ($rows) {
            if (is_string($rows)) {
                $styles[ 'grid_template_rows' ] = $rows;
            } else if (is_array($rows)) {
                $styles[ 'grid_template_rows' ] = implode(' ', $rows);
            } else {
                $styles[ 'grid_template_rows' ] = 'repeat(' . $rows . ', minmax(0, 1fr))';
            }
        }

        if ($columns) {
            if (is_string($columns)) {
                $styles[ 'grid_template_columns' ] = $columns;
            } else if (is_array($columns)) {
                $styles[ 'grid_template_columns' ] = implode(' ', $columns);
            } else {
                $styles[ 'grid_template_columns' ] = 'repeat(' . $columns . ', minmax(0, 1fr))';
            }
        }

        if (isset($gap)) {
            if (is_string($gap)) {
                $styles[ 'gap' ] = $gap;
            } else if (is_array($gap)) {
                $styles[ 'row_gap' ] = $gap[0];
                $styles[ 'column_gap' ] = $gap[1];
            } else {
                $styles[ 'gap' ] = $gap . 'px';
            }
        }

        $classes = [
            'yz',
            'grid-layout'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element($as, [
            'id'       => $id,
            'data'     => $data,
            'class'    => $classes,
            'style'    => $styles,
            'children' => function() use($children) {
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="grid-layout">
            .yz.grid-layout {
                display: grid;
                grid-auto-columns: 1fr;
                grid-auto-rows: 1fr;
            }
        </style>
    <?php }
}
