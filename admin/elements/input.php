<?php

function yz_input(array $props): void {
    $id            = yz_prop($props, 'id', '');
    $class         = yz_prop($props, 'class', '');
    $name          = yz_prop($props, 'name', $id);
    $type          = yz_prop($props, 'type', 'text');
    $step          = yz_prop($props, 'step', null);
    $label         = yz_prop($props, 'label', '');
    $checked       = yz_prop($props, 'checked', false);
    $required      = yz_prop($props, 'required', false);
    $hidden        = yz_prop($props, 'hidden', false);
    $placeholder   = yz_prop($props, 'placeholder', null);
    $default_value = yz_prop($props, 'value', '');

    if ($type === 'currency') {
        $step = 'any';
    }

    $classes = [
        'yuzu',
        'input'
    ];

    if ($class) {
        $classes[] = $class;
    }

    if ($type === 'checkbox' || $type === 'radio') {
        yz_element('label', [
            'class' => 'yuzu input-label input-' . $type . '-label',
            'attributes' => ['for' => $id],
            'children' => function() use($id, $name, $classes, $type, $checked, $label) {
                yz_element('input', [
                    'id'         => $id,
                    'name'       => $name,
                    'class'      => yz_join($classes),
                    'attributes' => [
                        'type'    => $type,
                        'checked' => $checked
                    ]
                ]);
                yz_text($label, ['class' => 'yuzu input-label checkbox-label']);
            }
        ]);
    } else {
        $decoration = yz_capture(function() use($type) {
            switch ($type) {
                case 'currency': yz_icon(['glyph' => 'coins', 'appearance' => 'duotone']); break;
                case 'email':    yz_icon(['glyph' => 'envelope', 'appearance' => 'duotone']); break;
                case 'number':   yz_icon(['glyph' => 'hash', 'appearance' => 'duotone']); break;
                case 'password': yz_icon(['glyph' => 'keyhole', 'appearance' => 'duotone']); break;
                case 'phone':    yz_icon(['glyph' => 'phone', 'appearance' => 'duotone']); break;
                case 'search':   yz_icon(['glyph' => 'magnifying-glass', 'appearance' => 'duotone']); break;
                case 'url':      yz_icon(['glyph' => 'globe-simple', 'appearance' => 'duotone']); break;
                default:         yz_icon(['glyph' => 'text-aa', 'appearance' => 'duotone']); break;
            }
        });

        if ($type === 'currency') $type = 'number';
        if ($type === 'title')    $type = 'text';

        $input = yz_capture(function() use(
            $id,
            $name,
            $class,
            $type,
            $required,
            $default_value,
            $placeholder,
            $hidden,
            $step
        ) {
            yz_element('input', [
                'id'         => $id,
                'name'       => $name,
                'class'      => $class,
                'attributes' => [
                    'type'        => $type,
                    'value'       => $default_value,
                    'placeholder' => $placeholder,
                    'required'    => $required,
                    'hidden'      => $hidden,
                    'step'        => $step
                ]
            ]);
        });

        if (isset($props['label'])) {
            yz_flex_layout([
                'gap' => 5,
                'direction' => 'column',
                'class' => $hidden ? 'input-container input-container-hidden' : 'input-container',
                'items' => [
                    ['children' => function() use($id, $label, $required) {
                        yz_element('label', [
                            'class' => 'yuzu input-label',
                            'attributes' => ['for' => $id],
                            'children'   => function() use($label) {
                                yz_text($label);
                            }
                        ]);
                    }],
                    ['children' => function() use($input, $decoration) {
                        yz_flex_layout(['items' => [
                            ['class' => 'yuzu input-decoration', 'children' => function() use($decoration) {
                                echo $decoration;
                            }],
                            ['children' => function() use($input) {
                                echo $input;
                            }]
                        ]]);
                    }]
                ]
            ]);
        } else if (!$hidden) {
            yz_flex_layout(['items' => [
                ['class' => 'yuzu input-decoration', 'children' => function() use($decoration) {
                    echo $decoration;
                }],
                ['children' => function() use($input) {
                    echo $input;
                }]
            ]]);
        } else {
            echo $input;
        }
    }
}