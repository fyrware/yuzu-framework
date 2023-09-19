<?php

class Yz_Image {

    public static function render(string $src, array $props): void {
        $id    = Yz_Array::value_or($props, 'id');
        $class = Yz_Array::value_or($props, 'class');
        $alt   = Yz_Array::value_or($props, 'alt');

        $classes = [
            'yuzu',
            'image'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Element('img', [
            'id'    => $id,
            'class' => Yz_Array::join($classes),
            'attr'  => [
                'src' => $src,
                'alt' => $alt
            ]
        ]);
    }
}