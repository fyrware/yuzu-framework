<?php

function yz_icon(array $props): void {
    $id         = yz_prop($props, 'id', '');
    $class      = yz_prop($props, 'class', '');
    $appearance = yz_prop($props, 'appearance', 'regular');

    $classes = [
        'yuzu',
        'icon'
    ];

    if ($appearance) {
        $classes[] = 'icon-' . $appearance;
    }

    if ($class) {
        $classes[] = $class;
    }

    $svg_url  = yz_icon_url($props);
    $svg_icon = file_get_contents($svg_url);
    $svg_icon = str_replace('<svg', '<svg class="' . yz_join($classes) . '"', $svg_icon);

    if ($id) {
        $svg_icon = str_replace('<svg', '<svg id="' . $id . '"', $svg_icon);
    }

    echo $svg_icon;
}

function yz_icon_url(array $props): string {
    $appearance = $props['appearance'] ?? 'regular';
    $file_dir   = plugin_dir_url(__FILE__) . '../../icons/assets/' . $appearance;
    $file_name  = ($appearance === 'regular' ? $props['glyph'] : $props['glyph'] . '-' . $props['appearance']) . '.svg';

    return $file_dir . '/' . $file_name;
}

function yz_icon_svg(array $props): string {
    return yz_capture(fn() => yz_icon($props));
}
