<?php

class Yz_Icon {

    public static function svg(string $glyph, array $props): string {
        return Yz_Buffer::capture(fn() => static::render($glyph, $props));
    }

    public static function url(string $glyph, array $props): string {
        $appearance = Yz_Array::value_or($props, 'appearance', 'regular');

        $file_dir    = plugin_dir_url(__FILE__) . '../../icons/assets/' . $appearance;
        $file_name   = ($appearance === 'regular' ? $glyph : $glyph . '-' . $appearance) . '.svg';

        return $file_dir . '/' . $file_name;
    }

    public static function render(string $glyph, array $props): void {
        $id         = Yz_Array::value_or($props, 'id', '');
        $class      = Yz_Array::value_or($props, 'class', '');
        $appearance = Yz_Array::value_or($props, 'appearance', 'regular');

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

        $svg_icon = file_get_contents(static::url($glyph, ['appearance' => $appearance]));
        $svg_icon = str_replace('<svg', '<svg class="' . Yz_Array::join($classes) . '"', $svg_icon);

        if ($id) {
            $svg_icon = str_replace('<svg', '<svg id="' . $id . '"', $svg_icon);
        }

        echo $svg_icon;
    }
}