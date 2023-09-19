<?php

class Yz_Input {

    private const INPUT_DECORATIONS = [
        'color'          => 'eyedropper-sample',
        'currency'       => 'coins',
        'date'           => 'calendar',
        'datetime-local' => 'calendar',
        'email'          => 'envelope',
        'file'            => 'folder-notch-open',
        'month'          => 'calendar',
        'number'         => 'hash',
        'password'       => 'keyhole',
        'phone'          => 'phone',
        'tel'            => 'phone',
        'search'         => 'magnifying-glass',
        'time'           => 'clock-countdown',
        'url'            => 'globe-simple',
        'title'          => 'text-aa',
        'week'           => 'calendar'
    ];

    public static function render(array $props): void {
        $id            = Yz_Array::value_or($props, 'id');
        $class         = Yz_Array::value_or($props, 'class');
        $step          = Yz_Array::value_or($props, 'step');
        $label         = Yz_Array::value_or($props, 'label');
        $placeholder   = Yz_Array::value_or($props, 'placeholder');
        $value         = Yz_Array::value_or($props, 'value');
        $name          = Yz_Array::value_or($props, 'name', $id);
        $type          = Yz_Array::value_or($props, 'type', 'text');
        $checked       = Yz_Array::value_or($props, 'checked', false);
        $required      = Yz_Array::value_or($props, 'required', false);
        $hidden        = Yz_Array::value_or($props, 'hidden', false);

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

        if ($type === 'checkbox' || $type === 'radio' || $type === 'range') {
            $label_classes = [
                'yuzu',
                'input-label',
            ];

            Yz::Element('label', [
                'class'    => Yz_Array::join($label_classes),
                'attr'     => [ 'for' => $id ],
                'children' => function() use($id, $name, $classes, $type, $checked, $label) {
                    Yz::Element('input', [
                        'id'    => $id,
                        'name'  => $name,
                        'class' => Yz_Array::join($classes),
                        'attr'  => [
                            'type'    => $type,
                            'checked' => $checked
                        ]
                    ]);
                    Yz::Text($label);
                }
            ]);

            return;
        }

        $decoration = $type;

        if ($decoration === 'currency') $type = 'number';
        if ($decoration === 'title')    $type = 'text';

        $input = Yz_Buffer::capture(fn() =>
            Yz::Element('input', [
                'id'         => $id,
                'name'       => $name,
                'class'      => $class,
                'attributes' => [
                    'type'        => $type,
                    'value'       => $value,
                    'placeholder' => $placeholder,
                    'required'    => $required,
                    'hidden'      => $hidden,
                    'step'        => $step
                ]
            ])
        );

        if ($hidden) {
            echo $input;
            return;
        }

        if ($label) {
            $input_container_classes = [
                'yuzu',
                'input-container'
            ];

            Yz::Flex_Layout([
                'gap'       => 5,
                'direction' => 'column',
                'class'     => Yz_Array::join($input_container_classes),
                'children'  => function() use($id, $label, $decoration, $input, $required) {
                    if ($label) {
                        $label_classes = [
                            'yuzu',
                            'input-label',
                        ];

                        if ($required) {
                            $label_classes[] = 'input-label-required';
                        }

                        Yz::Text($label, [
                            'class'   => Yz_Array::join($label_classes),
                            'variant' => 'label',
                            'attr'    => [ 'for' => $id ]
                        ]);
                    }
                    Yz::Flex_Layout([
                        'alignment' => 'center',
                        'children'  => function() use($decoration, $input) {
                            $input_decoration = Yz_Array::value_or(Yz_Input::INPUT_DECORATIONS, $decoration, 'text-aa');
                            $input_decoration_classes = [
                                'yuzu',
                                'input-decoration'
                            ];

                            Yz::Element('div', [
                                'class' => Yz_Array::join($input_decoration_classes),
                                'children' => function() use($input_decoration) {
                                    Yz::Icon($input_decoration, [
                                        'appearance' => 'duotone'
                                    ]);
                                }
                            ]);

                            echo $input;
                        }
                    ]);
                }
            ]);
        }
    }

    public static function render_style() { ?>
        <style>
            .yuzu.input-label {
                display: inline-flex;
                width: 100%;
                align-items: center;
                gap: 5px;
            }
            .yuzu.input-label input[type="checkbox"],
            .yuzu.input-label input[type="radio"] {
                margin: 0;
            }
            .yuzu.input-label input[type="checkbox"] + span,
            .yuzu.input-label input[type="radio"] + span {
                flex-shrink: 1;
                flex-grow: 0;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                font-weight: normal;
            }
            .yuzu.input-label.input-label-required::after {
                content: '*';
                color: #d63638;
                font-weight: bold;
            }
            .yuzu.input-decoration {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 30px;
                height: 30px;
                border: 1px solid #8c8f94;
                border-right: none;
                border-radius: 4px 0 0 4px;
                box-sizing: border-box;
            }
            .yuzu.input-decoration .yuzu.icon {
                width: 20px;
                height: 20px;
            }
            .yuzu.input-decoration + input {
                flex-grow: 1;
                border-radius: 0 4px 4px 0;
                margin: 0;
            }
        </style>
    <?php }
}