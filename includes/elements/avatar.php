<?php

class Yz_Avatar {

    public static function render(array $props): void {
        global $yz;

        $id    = $yz->tools->key_or_default($props, 'id');
        $class = $yz->tools->key_or_default($props, 'class');
        $size  = $yz->tools->key_or_default($props, 'size', 80);
        $src   = $yz->tools->key_or_default($props, 'src', 'https://i.pravatar.cc/' . $size . '?u=' . rand(0, 1000));

        $classes = [
            'yuzu',
            'avatar'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->image($src, [
            'id'    => $id,
            'class' => $classes,
            'attr'  => [
                'width'  => $size,
                'height' => $size
            ],
            'style' => [
                'border_radius' => '50%',
                'object_fit'     => 'cover'
            ]
        ]);
    }
}