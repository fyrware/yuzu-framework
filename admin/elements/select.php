<?php

function yz_select(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $name     = yz_prop($props, 'name', $id);
    $class    = yz_prop($props, 'class', '');
    $value    = yz_prop($props, 'value', '');
    $required = yz_prop($props, 'required', false);
    $options  = yz_prop($props, 'options', []);

    $classes = [
        'yuzu',
        'select'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element('select', [
        'id'         => $id,
        'name'       => $name,
        'class'      => yz_join($classes),
        'attributes' => [
            'required' => $required
        ],
        'children' => function() use($value, $options) {
            foreach ($options as $option) {
                $opt_value    = yz_prop($option, 'value', '');
                $opt_label    = yz_prop($option, 'label', '');
                $opt_selected = yz_prop($option, 'selected', false);

                yz_element('option', [
                    'attributes' => [
                        'value'    => $opt_value,
                        'selected' => $value === $opt_value || $opt_selected
                    ],
                    'children' => function() use($opt_label) {
                        yz_text($opt_label);
                    }
                ]);
            }
        }
    ]);
}
