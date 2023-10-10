<?php

class Yz_Select {

    public static function render(array $props = []): void {
        $id       = Yz_Array::value_or($props, 'id');
        $name     = Yz_Array::value_or($props, 'name', $id);
        $class    = Yz_Array::value_or($props, 'class',);
        $value    = Yz_Array::value_or($props, 'value');
        $required = Yz_Array::value_or($props, 'required', false);
        $options  = Yz_Array::value_or($props, 'options', []);

        $classes = [
            'yuzu',
            'select'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Element('select', [
            'id'    => $id,
            'name'  => $name,
            'class' => Yz_Array::join($classes),
            'attr'  => [
                'required' => $required
            ],
            'children' => function() use($value, $options) {
                foreach ($options as $option) {
                    $opt_value    = Yz_Array::value_or($option, 'value', '');
                    $opt_label    = Yz_Array::value_or($option, 'label', $opt_value);
                    $opt_selected = Yz_Array::value_or($option, 'selected', false);

                    Yz::Element('option', [
                        'attr' => [
                            'value'    => $opt_value,
                            'selected' => $value === $opt_value || $opt_selected
                        ],
                        'children' => function() use($opt_label) {
                            echo $opt_label;
                        }
                    ]);
                }
            }
        ]);
    }
}