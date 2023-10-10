<?php

class Yz_Text_Area {

    public static function render(array $props): void {
        $id            = Yz_Array::value_or($props, 'id');
        $class         = Yz_Array::value_or($props, 'class');
        $label         = Yz_Array::value_or($props, 'label');
        $placeholder   = Yz_Array::value_or($props, 'placeholder');
        $value         = Yz_Array::value_or($props, 'value');
        $name          = Yz_Array::value_or($props, 'name', $id);
        $required      = Yz_Array::value_or($props, 'required', false);
        $hidden        = Yz_Array::value_or($props, 'hidden', false);
        $rows          = Yz_Array::value_or($props, 'rows', 3);

        $classes = [
            'yz',
            'textarea'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Flex_Layout([
            'gap' => 5,
            'direction' => 'column',
            'children' => function() use($label, $id, $name, $classes, $placeholder, $required, $hidden, $value, $rows) {
                Yz::Text($label, [
                    'class'   => 'text-area-label',
                    'variant' => 'label'
                ]);
                Yz::Element('textarea', [
                    'id'    => $id,
                    'name'  => $name,
                    'class' => Yz_Array::join($classes),
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
        <style>
            .yz.text-area-label {
                font-weight: 700;
            }
        </style>
    <?php }
}