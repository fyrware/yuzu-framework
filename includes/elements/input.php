<?php

class Yz_Input {

    private const INPUT_DECORATIONS = [
        'color'          => 'eyedropper-sample',
        'currency'       => 'coins',
        'date'           => 'calendar',
        'datetime-local' => 'calendar',
        'location'       => 'map-pin',
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
        global $yz;

        $id            = $yz->tools->key_or_default($props, 'id');
        $class         = $yz->tools->key_or_default($props, 'class');
        $step          = $yz->tools->key_or_default($props, 'step');
        $label         = $yz->tools->key_or_default($props, 'label');
        $placeholder   = $yz->tools->key_or_default($props, 'placeholder');
        $value         = $yz->tools->key_or_default($props, 'value');
        $name          = $yz->tools->key_or_default($props, 'name', $id);
        $type          = $yz->tools->key_or_default($props, 'type', 'text');
        $checked       = $yz->tools->key_or_default($props, 'checked', false);
        $required      = $yz->tools->key_or_default($props, 'required', false);
        $disabled      = $yz->tools->key_or_default($props, 'disabled', false);
        $hidden        = $yz->tools->key_or_default($props, 'hidden', false);
        $layout        = $yz->tools->key_or_default($props, 'layout', 'column');
        $width         = $yz->tools->key_or_default($props, 'width');
        $autofocus     = $yz->tools->key_or_default($props, 'autofocus', false);
        $data_set      = $yz->tools->key_or_default($props, 'data', []);

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

            $yz->html->element('label', [
                'class'    => $yz->tools->join_values($label_classes),
                'attr'     => [ 'for' => $id ],
                'children' => function() use($yz, $id, $name, $classes, $type, $checked, $label, $disabled, $autofocus, $data_set) {
                    $yz->html->element('input', [
                        'id'    => $id,
                        'name'  => $name,
                        'class' => array_merge($classes, [ $type ]),
                        'data'  => $data_set,
                        'attr'  => [
                            'type'     => $type,
                            'checked'  => $checked,
                            'disabled' => $disabled,
                            'autofocus' => $autofocus
                        ]
                    ]);
                    $yz->html->text($label ?? '');
                }
            ]);

            return;
        }

        $decoration = $type;

        if ($decoration === 'currency') $type = 'number';
        if ($decoration === 'location') $type = 'text';
        if ($decoration === 'title')    $type = 'text';

        $input = $yz->tools->capture_buffer(fn() =>
            $yz->html->element('input', [
                'id'    => $id,
                'name'  => $name,
                'class' => $classes,
                'data'  => $data_set,
                'attr'  => [
                    'type'        => $type,
                    'value'       => $value,
                    'placeholder' => $placeholder,
                    'required'    => $required,
                    'disabled'    => $disabled,
                    'hidden'      => $hidden,
                    'step'        => $step,
                    'autofocus'   => $autofocus
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

        $yz->html->flex_layout([
            'gap'       => $layout === 'column' ? 5 : 10,
            'direction' => $layout,
            'class'     => $input_container_classes,
            'style'     => $input_container_style,
            'width'     => $layout === 'column' ? $width : 'auto',
            'children'  => function() use($yz, $id, $label, $decoration, $input, $required, $width) {
                if ($label) {
                    $label_classes = [
                        'yz',
                        'input-label',
                    ];

                    if ($required) {
                        $label_classes[] = 'input-label-required';
                    }

                    $yz->html->text($label, [
                        'class'   => $label_classes,
                        'variant' => 'label',
                        'attr'    => [ 'for' => $id ]
                    ]);
                }
                $yz->html->flex_layout([
                    'alignment' => 'center',
                    'class'     => 'input-container-inner',
                    'width'     => $width,
                    'children'  => function() use($yz, $decoration, $input) {
                        $input_decoration = $yz->tools->key_or_default(Yz_Input::INPUT_DECORATIONS, $decoration, 'text-aa');

                        $input_decoration_classes = [
                            'yz',
                            'input-decoration'
                        ];

                        $yz->html->element('div', [
                            'class' => $yz->tools->join_values($input_decoration_classes),
                            'children' => function() use($yz, $input_decoration) {
                                $yz->html->icon($input_decoration, [
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
        <style data-yz-element="input">
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