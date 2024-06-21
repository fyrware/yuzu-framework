<?php

class Yz_Image {

    public static function render(string $src, array $props): void {
        global $yz;

        $id    = $yz->tools->get_value($props, 'id');
        $class = $yz->tools->get_value($props, 'class');
        $alt   = $yz->tools->get_value($props, 'alt');
        $title = $yz->tools->get_value($props, 'title', $alt);
        $width = $yz->tools->get_value($props, 'width');
        $height = $yz->tools->get_value($props, 'height');
        $object_fit = $yz->tools->get_value($props, 'object_fit');

        $classes = [
            'image'
        ];

        if ($class) {
            $classes[] = $class;
        }

        if ($object_fit) {
            $classes[] = 'object-fit-' . $object_fit;
        }

        $yz->html->element('img', [
            'id'    => $id,
            'class' => $classes,
            'attr'  => [
                'src' => $src,
                'alt' => $alt,
                'title' => $title,
                'width' => $width,
                'height' => $height
            ]
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="image">
            .yz.image.object-fit-contain {
                object-fit: contain;
            }
            .yz.image.object-fit-cover {
                object-fit: cover;
            }
        </style>
    <?php }
}