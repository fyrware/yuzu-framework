<?php

/**
 * Return an SVG icon from the Phosphor icon set (phosphoricons.com)
 * @return string
 */
function yuzu_icon(string $icon_name, string $icon_appearance = 'regular'): string {
    return file_get_contents(plugin_dir_path(__FILE__) . "icons/assets/$icon_appearance/$icon_name-$icon_appearance.svg");
}

/**
 * Render (echo) an SVG icon from the Phosphor icon set (phosphoricons.com)
 * @return void
 */
function render_yuzu_icon(string $icon_name, string $icon_appearance = 'regular'): void {
    echo yuzu_icon($icon_name, $icon_appearance);
}