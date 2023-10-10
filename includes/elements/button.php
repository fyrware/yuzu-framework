<?php

class Yz_Button {

    private const VALID_TYPES = [
        'button',
        'submit',
        'reset',
        'link'
    ];

    private const VALID_VARIANTS = [
        'primary',
        'secondary',
        'tertiary',
        'quaternary'
    ];

    private const VALID_SIZES = [
        'small',
        'medium',
        'large',
    ];

    private const VALID_COLORS = [
        'primary',
        'secondary',
        'success',
        'warning',
        'danger',
        'info'
    ];

    public static function render(array $props): void {
        $id         = Yz_Array::value_or($props, 'id');
        $label      = Yz_Array::value_or($props, 'label');
        $href       = Yz_Array::value_or($props, 'href');
        $icon       = Yz_Array::value_or($props, 'icon');
        $icon_style = Yz_Array::value_or($props, 'icon_appearance', 'duotone');
        $type       = Yz_Array::value_or($props, 'type', 'button');
        $size       = Yz_Array::value_or($props, 'size', 'medium');
        $variant    = Yz_Array::value_or($props, 'variant', 'secondary');
        $color      = Yz_Array::value_or($props, 'color', 'primary');
        $class      = Yz_Array::value_or($props, 'class', '');
        $disabled   = Yz_Array::value_or($props, 'disabled', false);

        assert(in_array($type,    Yz_Button::VALID_TYPES),    'Invalid button type');
        assert(in_array($size,    Yz_Button::VALID_SIZES),    'Invalid button size');
        assert(in_array($variant, Yz_Button::VALID_VARIANTS), 'Invalid button variant');
        assert(in_array($color,   Yz_BUtton::VALID_COLORS),   'Invalid button color');

        $classes = [
            'yuzu',
            'button'
        ];

        if ($size) {
            $classes[] = 'button-size-' . $size;
        }

        if ($variant) {
            $classes[] = 'button-' . $variant;
        }

        if ($color) {
            $classes[] = 'button-color-' . $color;
        }

        if ($class) {
            $classes[] = $class;
        }

        if ($type === 'link') {
            Yz::Element('a', [
                'id'    => $id,
                'class' => Yz_Array::join($classes),
                'attr'  => [
                    'href' => $href
                ],
                'children' => function() use($icon, $icon_style, $label) {
                    if ($icon) Yz::Icon($icon, ['appearance' => $icon_style]);
                    if ($label) echo $label;
                }
            ]);
        } else {
            Yz::Element('button', [
                'id'    => $id,
                'class' => Yz_Array::join($classes),
                'attr'  => [
                    'type'     => $type,
                    'disabled' => $disabled
                ],
                'children' => function() use($icon, $icon_style, $label) {
                    if ($icon) Yz::Icon($icon, ['appearance' => $icon_style]);
                    if ($label) Yz::Text($label);
                }
            ]);
        }
    }

    public static function render_style() { ?>
        <style>
            .yuzu.button {
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            .yuzu.button .yuzu.icon {
                width: 22px;
                height: 22px;
            }

            .yuzu.button.button-size-small {
                min-height: 24px;
                line-height: 2;
                padding: 0 6px;
                font-size: 11px;
            }

            .yuzu.button.button-size-small .icon {
                width: 16px;
                height: 16px;
            }

            .yuzu.button.button-size-large {
                min-height: 36px;
                line-height: 2;
                padding: 4px 12px;
                font-size: 16px;
            }

            .yuzu.button.button-size-large .icon {
                width: 24px;
                height: 24px;
            }
        </style>
    <?php }
}
