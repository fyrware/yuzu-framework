<?php

class Yz_Avatar {

    public static function render(array $props): void {
        $id    = Yz_Array::value_or($props, 'id');
        $class = Yz_Array::value_or($props, 'class');
        $size  = Yz_Array::value_or($props, 'size', 80);
        $src   = Yz_Array::value_or($props, 'src', 'https://i.pravatar.cc/' . $size . '?u=' . rand(0, 1000));

        $classes = [
            'yuzu',
            'avatar'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Element('img', [
            'id' => $id,
            'class' => Yz_Array::join($classes),
            'attributes' => [
                'src' => $src,
                'width' => $size,
                'height' => $size
            ],
            'style' => Yz_Array::join_key_value([
                'border-radius' => '50%',
                'object-fit' => 'cover'
            ])
        ]);
    }
}