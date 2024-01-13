<?php

class Yz_Box {

    public static function render(array $props): void {
        global $yz;

        $id       = $yz->tools->key_or_default($props, 'id');
        $class    = $yz->tools->key_or_default($props, 'class');
        $children = $yz->tools->key_or_default($props, 'children');
        $padding  = $yz->tools->key_or_default($props, 'padding', 0);
        $margin   = $yz->tools->key_or_default($props, 'margin', 0);

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