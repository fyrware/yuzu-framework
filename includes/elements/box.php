<?php

class Yz_Box {

    public static function render(array $props): void {
        $id = Yz_Array::value_or($props, 'id');
        $class = Yz_Array::value_or($props, 'class');
        $children = Yz_Array::value_or($props, 'children');
        $padding = Yz_Array::value_or($props, 'padding', 0);
        $margin = Yz_Array::value_or($props, 'margin', 0);

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

        Yz::Element('div', [
            'id' => $id,
            'class' => Yz_Array::join($classes),
            'children' => $children,
            'style' => Yz_Array::join_key_value([
                'padding' => $padding,
                'margin' => $margin
            ])
        ]);
    }
}