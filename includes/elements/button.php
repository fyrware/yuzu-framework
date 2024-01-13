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
        global $yz;

        $id         = $yz->tools->key_or_default($props, 'id');
        $label      = $yz->tools->key_or_default($props, 'label');
        $href       = $yz->tools->key_or_default($props, 'href');
        $icon       = $yz->tools->key_or_default($props, 'icon');
        $icon_style = $yz->tools->key_or_default($props, 'icon_appearance', 'duotone');
        $type       = $yz->tools->key_or_default($props, 'type', 'button');
        $size       = $yz->tools->key_or_default($props, 'size', 'medium');
        $variant    = $yz->tools->key_or_default($props, 'variant', 'secondary');
        $color      = $yz->tools->key_or_default($props, 'color', 'primary');
        $class      = $yz->tools->key_or_default($props, 'class', '');
        $disabled   = $yz->tools->key_or_default($props, 'disabled', false);

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
            $yz->html->element('a', [
                'id'    => $id,
                'class' => $classes,
                'attr'  => [
                    'href' => $href
                ],
                'children' => function() use($yz, $icon, $icon_style, $label) {
                    if ($icon) $yz->html->icon($icon, ['appearance' => $icon_style]);
                    if ($label) echo $label;
                }
            ]);
        } else {
            $yz->html->element('button', [
                'id'    => $id,
                'class' => $classes,
                'attr'  => [
                    'type'     => $type,
                    'disabled' => $disabled
                ],
                'children' => function() use($yz, $icon, $icon_style, $label) {
                    if ($icon) $yz->html->icon($icon, ['appearance' => $icon_style]);
                    if ($label) echo $label;
                }
            ]);
        }
    }

    public static function render_style() { ?>
        <style data-yz-element="button">
            .yz.button {
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            .yz.button .yz.icon {
                width: 22px;
                height: 22px;
            }

            .yz.button.button-size-small {
                min-height: 24px;
                line-height: 2;
                padding: 0 6px;
                font-size: 11px;
            }

            .yz.button.button-size-small .icon {
                width: 16px;
                height: 16px;
            }

            .yz.button.button-size-large {
                min-height: 36px;
                line-height: 2;
                padding: 4px 12px;
                font-size: 16px;
            }

            .yz.button.button-size-large .icon {
                width: 24px;
                height: 24px;
            }
        </style>
    <?php }
}
