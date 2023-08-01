<?php

function yz_button_group(array $props): void {
    $id      = yz_prop($props, 'id', '');
    $class   = yz_prop($props, 'class', '');
    $size    = yz_prop($props, 'size', '');
    $variant = yz_prop($props, 'variant', 'secondary');
    $type    = yz_prop($props, 'type', 'button');
    $buttons = yz_prop($props, 'buttons', []);

    assert(is_array($buttons), 'Buttons must be an array');

    $classes = [
        'yuzu',
        'button-group'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'id'       => $id,
        'class'    => implode(' ', $classes),
        'children' => function() use($buttons, $size, $variant, $type) {
            foreach ($buttons as $button) {
                $button_id      = yz_prop($button, 'id', '');
                $button_class   = yz_prop($button, 'class', '');
                $button_label   = yz_prop($button, 'label', '');
                $button_href    = yz_prop($button, 'href', '');
                $button_icon    = yz_prop($button, 'icon', '');
                $button_type    = yz_prop($button, 'type', $type);
                $button_size    = yz_prop($button, 'size', $size);
                $button_variant = yz_prop($button, 'variant', $variant);

                yz_button([
                    'id' => $button_id,
                    'class' => $button_class,
                    'label' => $button_label,
                    'href' => $button_href,
                    'icon' => $button_icon,
                    'type' => $button_type,
                    'size' => $button_size,
                    'variant' => $button_variant
                ]);
            }
        }
    ]);
}