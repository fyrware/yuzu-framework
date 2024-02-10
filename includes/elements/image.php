<?php

class Yz_Image {

    public static function render(string $src, array $props): void {
        global $yz;

        $id    = $yz->tools->get_value($props, 'id');
        $class = $yz->tools->get_value($props, 'class');
        $alt   = $yz->tools->get_value($props, 'alt');
        $title = $yz->tools->get_value($props, 'title', $alt);

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
                'alt' => $alt,
                'title' => $title
            ]
        ]);
    }
}