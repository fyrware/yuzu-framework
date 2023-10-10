<?php

function yz_textarea(array $props): void {
    $id            = yz_prop($props, 'id', '');
    $name          = yz_prop($props, 'name', $id);
    $class         = yz_prop($props, 'class', '');
    $rows          = yz_prop($props, 'rows', 3);
    $type          = yz_prop($props, 'type', 'text');
    $label         = yz_prop($props, 'label', '');
    $placeholder   = yz_prop($props, 'placeholder', null);
    $required      = yz_prop($props, 'required', false);
    $default_value = yz_prop($props, 'value', '');

    $classes = [
        'yuzu',
        'textarea'
    ];

    if ($class) {
        $classes[] = $class;
    }

    $textarea = yz_element('textarea', [
        'id'         => $id,
        'name'       => $name,
        'class'      => yz_join($classes),
        'attributes' => [
            'type'        => $type,
            'rows'        => $rows,
            'placeholder' => $placeholder,
            'value'       => $default_value,
            'required'    => $required
        ]
    ]);

    if ($label) {
        yz_flex_layout([
            'gap' => 5,
            'direction' => 'column',
            'items' => [
                ['children' => function() use($id, $label, $required) {
                    $label_classes = [
                        'yuzu',
                        'textarea-label'
                    ];
                    if ($required) {
                        $label_classes[] = 'textarea-label-required';
                    }
                    yz_element('label', [
                        'class' => yz_join($label_classes),
                        'attributes' => [
                            'for' => $id,
                        ],
                        'children' => function() use($label) {
                            yz_text($label);
                        }
                    ]);
                }],
                ['children' => function() use($textarea) {
                    echo $textarea;
                }]
            ]
        ]);
    } else {
        echo $textarea;
    }
}