<?php

class Yz_Divider {

    private const VALID_ORIENTATIONS = [
        'horizontal',
        'vertical'
    ];

    public static function render(array $props): void {
        $id          = yz()->tools->get_value($props, 'id');
        $orientation = yz()->tools->get_value($props, 'orientation', 'horizontal');
        $class_name  = yz()->tools->get_value($props, 'class');
        $style       = yz()->tools->get_value($props, 'style');

        assert(in_array($orientation, static::VALID_ORIENTATIONS));

        $classes = [
            'yz',
            'divider'
        ];

        if ($class_name) {
            $classes[] = $class_name;
        }

        if ($orientation) {
            $classes[] = $orientation;
        }

        yz()->html->element('hr', [
            'id'    => $id,
            'class' => yz()->tools->join_values($classes),
            'style' => $style
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="divider">
            .yz.divider {
                border: none;
                margin: 0;
                padding: 0;
                background-color: #8c8f94;
                opacity: 0.5;
            }

            .yz.divider.horizontal {
                width: 100%;
                height: 1px;
            }

            .yz.divider.vertical {
                width: 1px;
                height: 100%;
            }
        </style>
    <?php }
}