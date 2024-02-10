<?php

class Yz_Text_Area {

    public static function render(array $props): void {
        global $yz;

        $id          = $yz->tools->get_value($props, 'id');
        $class       = $yz->tools->get_value($props, 'class');
        $label       = $yz->tools->get_value($props, 'label');
        $placeholder = $yz->tools->get_value($props, 'placeholder');
        $value       = $yz->tools->get_value($props, 'value');
        $name        = $yz->tools->get_value($props, 'name', $id);
        $required    = $yz->tools->get_value($props, 'required', false);
        $hidden      = $yz->tools->get_value($props, 'hidden', false);
        $rows        = $yz->tools->get_value($props, 'rows', 3);

        $classes = [
            'yz',
            'textarea'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->flex_layout([
            'gap'       => 5,
            'direction' => 'column',
            'children'  => function() use($yz, $label, $id, $name, $classes, $placeholder, $required, $hidden, $value, $rows) {
                $yz->html->text($label, [
                    'class'   => 'text-area-label',
                    'variant' => 'label'
                ]);
                $yz->html->element('textarea', [
                    'id'    => $id,
                    'name'  => $name,
                    'class' => $classes,
                    'attr'  => [
                        'placeholder' => $placeholder,
                        'required'    => $required,
                        'hidden'      => $hidden,
                        'value'       => $value,
                        'rows'        => $rows
                    ],
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="textarea">
            .yz.text-area-label {
                font-weight: 700;
            }
        </style>
    <?php }
}