<?php

class Yz_Icon {

    public static function svg(string $glyph, array $props): string {
        global $yz;
        return $yz->tools->capture_buffer(fn() => static::render($glyph, $props));
    }

    public static function url(string $glyph, array $props): string {
        global $yz;

        $appearance = $yz->tools->get_value($props, 'appearance', 'regular');

        $file_dir    = plugin_dir_url(__FILE__) . '../../icons/assets/' . $appearance;
        $file_name   = ($appearance === 'regular' ? $glyph : $glyph . '-' . $appearance) . '.svg';

        return $file_dir . '/' . $file_name;
    }

    public static function path(string $glyph, array $props): string {
        global $yz;

        $appearance = $yz->tools->get_value($props, 'appearance', 'regular');

        $file_dir    = plugin_dir_path(__FILE__) . '../../icons/assets/' . $appearance;
        $file_name   = ($appearance === 'regular' ? $glyph : $glyph . '-' . $appearance) . '.svg';

        return $file_dir . '/' . $file_name;
    }

    public static function render(string $glyph, array $props): void {
        global $yz;
        
        $id         = $yz->tools->get_value($props, 'id', '');
        $class      = $yz->tools->get_value($props, 'class', '');
        $appearance = $yz->tools->get_value($props, 'appearance', 'regular');
        $color      = $yz->tools->get_value($props, 'color');
        $size       = $yz->tools->get_value($props, 'size');

        $classes = [
            'yz',
            'yuzu',
            'icon'
        ];

        if ($appearance) {
            $classes[] = 'icon-' . $appearance;
        }

        if ($class) {
            $classes[] = $class;
        }

        $svg_icon = file_get_contents(static::path($glyph, ['appearance' => $appearance]));
        $svg_icon = str_replace('<svg', '<svg class="' . $yz->tools->join_values($classes) . '"', $svg_icon);

        if ($color) {
            $svg_icon = str_replace('<svg', '<svg style="fill:' . $color . '"', $svg_icon);
        }

        if ($id) {
            $svg_icon = str_replace('<svg', '<svg id="' . $id . '"', $svg_icon);
        }

        if ($size) {
            $svg_icon = str_replace('<svg', '<svg width="' . $size . '" height="' . $size . '"', $svg_icon);
        }

        echo $svg_icon;
    }
}