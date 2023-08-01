<?php

function yz_color_picker(array $props): void {
    $id          = yz_prop($props, 'id', '');
    $name        = yz_prop($props, 'name', $id);
    $class       = yz_prop($props, 'class', '');
    $label       = yz_prop($props, 'label', '');
    $description = yz_prop($props, 'description', '');
    $value       = yz_prop($props, 'value', '');

    assert(is_string($label));
    assert(is_string($description));
    assert(is_string($value));

    $classes = [
        'yuzu',
        'color-picker'
    ];

    if ($class) {
        $classes[] = $class;
    }

    $input = yz_capture(fn() =>
        yz_element([
            'class' => yz_join($classes),
            'children' => function() use($id, $name, $value) {
                yz_element('link', [
                    'attributes' => [
                        'rel' => 'stylesheet',
                        'href' => 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css'
                    ]
                ]);
                yz_element('script', [
                    'attributes' => [
                        'src' => 'https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js'
                    ]
                ]);
                yz_element('input', [
                    'id' => $id,
                    'attributes' => [
                        'type' => 'text',
                        'name' => $name,
                        'value' => $value
                    ],
                    'data_set' => [
                        'coloris' => true
                    ]
                ]);
            }
        ])
    );

    if ($label) {
        yz_flex_layout([
            'direction' => 'column',
            'items' => [
                ['children' => function() use($id, $label, $description) {
                    if ($label) {
                        yz_element('label', [
                            'attributes' => [
                                'for' => $id
                            ],
                            'children' => function() use($label) {
                                yz_text($label);
                            }
                        ]);
                    }
                    if ($description) {
                        yz_paragraph([
                            'variant' => 'description',
                            'children' => function() use($description) {
                                yz_text($description);
                            }
                        ]);
                    }
                }],
                ['children' => function() use($input) {
                    echo $input;
                }]
            ]
        ]);
    } else {
        echo $input;
    }
}