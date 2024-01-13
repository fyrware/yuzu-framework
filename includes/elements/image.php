<?php

class Yz_Image {

    public static function render(string $src, array $props): void {
        global $yz;

        $id    = $yz->tools->key_or_default($props, 'id');
        $class = $yz->tools->key_or_default($props, 'class');
        $alt   = $yz->tools->key_or_default($props, 'alt');

        $classes = [
            'yuzu',
            'image'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('img', [
            'id'    => $id,
            'class' => $classes,
            'attr'  => [
                'src' => $src,
                'alt' => $alt
            ]
        ]);
    }
}