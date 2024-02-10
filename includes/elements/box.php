<?php

class Yz_Box {

    public static function render(array $props): void {
        global $yz;

        $id       = $yz->tools->get_value($props, 'id');
        $class    = $yz->tools->get_value($props, 'class');
        $children = $yz->tools->get_value($props, 'children');
        $padding  = $yz->tools->get_value($props, 'padding', 0);
        $margin   = $yz->tools->get_value($props, 'margin', 0);

        if (is_numeric($padding)) {
            $padding .= 'px';
        }

        if (is_numeric($margin)) {
            $margin .= 'px';
        }

        $classes = [
            'yuzu',
            'box'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('div', [
            'id'       => $id,
            'class'    => $classes,
            'children' => $children,
            'style'    => [
                'padding' => $padding,
                'margin'  => $margin
            ]
        ]);
    }
}