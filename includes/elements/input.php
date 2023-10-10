<?php

class Yz_Input {

    private const INPUT_DECORATIONS = [
        'color'          => 'eyedropper-sample',
        'currency'       => 'coins',
        'date'           => 'calendar',
        'datetime-local' => 'calendar',
        'location'       => 'map-pin',
        'email'          => 'envelope',
        'file'           => 'folder-notch-open',
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
        $disabled      = Yz_Array::value_or($props, 'disabled', false);
        $hidden        = Yz_Array::value_or($props, 'hidden', false);
        $layout        = Yz_Array::value_or($props, 'layout', 'column');
        $width         = Yz_Array::value_or($props, 'width');

        if ($type === 'currency') {
            $step = 'any';
        }

        $classes = [
            'yz',
            'input'
        ];

        if ($class) {
            $classes[] = $class;
        }

        if ($type === 'checkbox' || $type === 'radio' || $type === 'range') {
            $label_classes = [
                'yz',
                'input-label',
            ];

            Yz::Element('label', [
                'class'    => Yz_Array::join($label_classes),
                'attr'     => [ 'for' => $id ],
                'children' => function() use($id, $name, $classes, $type, $checked, $label, $disabled) {
                    Yz::Element('input', [
                        'id'    => $id,
                        'name'  => $name,
                        'class' => Yz_Array::join($classes),
                        'attr'  => [
                            'type'     => $type,
                            'checked'  => $checked,
                            'disabled' => $disabled
                        ]
                    ]);
                    Yz::Text($label);
                }
            ]);

            return;
        }

        $decoration = $type;

        if ($decoration === 'currency') $type = 'number';
        if ($decoration === 'location') $type = 'text';
        if ($decoration === 'title')    $type = 'text';

        $input = Yz_Buffer::capture(fn() =>
            Yz::Element('input', [
                'id'         => $id,
                'name'       => $name,
                'class'      => Yz_Array::join($classes),
                'attr' => [
                    'type'        => $type,
                    'value'       => $value,
                    'placeholder' => $placeholder,
                    'required'    => $required,
                    'disabled'    => $disabled,
                    'hidden'      => $hidden,
                    'step'        => $step
                ]
            ])
        );

        if ($hidden) {
            echo $input;
            return;
        }

        $input_container_classes = [
            'yz',
            'input-container'
        ];

        $input_container_style = [];

//        if ($width) {
//            $input_container_style['width'] = is_string($width) ? $width : $width . 'px';
//        }

        Yz::Flex_Layout([
            'gap'       => $layout === 'column' ? 5 : 10,
            'direction' => $layout,
            'class'     => Yz_Array::join($input_container_classes),
            'width'     => $layout === 'column' ? $width : 'auto',
            'children'  => function() use($id, $label, $decoration, $input, $required, $width) {
                if ($label) {
                    $label_classes = [
                        'yz',
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
                    'class'     => 'input-container-inner',
                    'width'     => $width,
                    'children'  => function() use($decoration, $input) {
                        $input_decoration = Yz_Array::value_or(Yz_Input::INPUT_DECORATIONS, $decoration, 'text-aa');
                        $input_decoration_classes = [
                            'yz',
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

    public static function render_style() { ?>
        <style>
            .yz.input-label {
                display: inline-flex;
                white-space: nowrap;
                align-items: center;
                gap: 5px;
                font-weight: 700;
            }
            .yz.input-label input[type="checkbox"],
            .yz.input-label input[type="radio"] {
                margin: 0;
            }
            .yz.input-label input[type="checkbox"] + span,
            .yz.input-label input[type="radio"] + span {
                flex-shrink: 1;
                flex-grow: 0;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                font-weight: normal;
            }
            .yz.input-label.input-label-required::after {
                content: '*';
                color: #d63638;
                font-weight: bold;
            }
            .yz.input-container-inner {
                flex-grow: 1;
                width: 100%;
            }
            .yz.input-decoration {
                flex-shrink: 0;
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
            .yz.input-decoration svg {
                width: 20px;
                height: 20px;
            }
            .yz.input-decoration + input {
                flex-grow: 1;
                flex-shrink: 1;
                width: 0;
                border-radius: 0 4px 4px 0;
                margin: 0;
            }
            .yz.input[disabled] {
                border: 1px solid #8c8f94;
                background: white;
            }
            .yz.input-container:has([disabled]) {
                opacity: 0.55;
            }
        </style>
    <?php }
}