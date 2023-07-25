<?php

function yz_icon(array $props) {

    $class_names = [
        'yuzu',
        'icon'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $class = trim(implode(' ', $class_names));
    $svg_url = yz_icon_url($props);
    $svg_icon = file_get_contents($svg_url);

    echo str_replace('<svg', '<svg class="' . $class . '"', $svg_icon);
}

function yz_icon_url(array $props): string {
    $appearance = $props['appearance'] ?? 'regular';
    $file_dir = plugin_dir_url(__FILE__) . '../../icons/assets/' . $appearance;
    $file_name = ($appearance === 'regular' ? $props['glyph'] : $props['glyph'] . '-' . $props['appearance']) . '.svg';

    return $file_dir . '/' . $file_name;
}

function yz_icon_svg(array $props): string {
    return yz_capture(fn() => yz_icon($props));
}
