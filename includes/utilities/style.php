<?php

use \Mexitek\PHPColors\Color;

class Yz_Style {

    private const SCSS_VARIABLE_PATTERN = '/\$(.*?): (.*?)(?: !default)?;/';
    private const CSS_VARIABLE_PREFIX   = '--yz-';

    public static function convert_string_to_color(string $color): Color {
        if (str_starts_with($color, '#')) {
            return new Color($color);
        } else if (str_starts_with($color, 'rgb')) {
            return new Color(Color::rgbToHex($color));
        } else if (str_starts_with($color, 'hsl')) {
            return new Color(Color::hslToHex($color));
        } else {
            return new Color(Color::nameToHex($color));
        }
    }

    public static function convert_scss_variable_to_css(string $variable): string {
        return str_starts_with($variable, '$')
            ? 'var(' . static::CSS_VARIABLE_PREFIX . substr($variable, 1) . ')'
            : $variable;
    }

    public static function parse_css_variable_name(string $css_variable): string {
        return preg_replace('/var\((.+?)\)/', '$1', $css_variable);
    }

    public static function load_admin_style_variables(): string {
        $admin_color = get_user_option('admin_color');
        $admin_dir   = str_replace(get_bloginfo('url') . '/', ABSPATH, get_admin_url());

        $base_variables_map  = [];
        $base_variables_scss = file_get_contents($admin_dir . 'css/colors/_variables.scss');

        if ($base_variables_scss) {
            preg_match_all(static::SCSS_VARIABLE_PATTERN, $base_variables_scss, $base_variables_map);
            $base_variables_map = array_combine($base_variables_map[1], $base_variables_map[2]);
        }

        if ($admin_color !== 'fresh') {
            $color_scheme_scss = file_get_contents($admin_dir . 'css/colors/' . $admin_color . '/colors.scss');

            if ($color_scheme_scss) {
                preg_match_all(static::SCSS_VARIABLE_PATTERN, $color_scheme_scss, $color_scheme_variables);
                $color_scheme_variables = array_combine($color_scheme_variables[1], $color_scheme_variables[2]);
                $base_variables_map         = array_merge($base_variables_map, $color_scheme_variables);
            }
        }

        $css_variables = [];
        foreach ($base_variables_map as $key => $value) {
            $css_variables[static::CSS_VARIABLE_PREFIX . $key] = static::convert_scss_variable_to_css($value);

            $lighten_matches = [];
            if (preg_match('/lighten\(\s?(.+?),(.+?)%\s?\)/', $value, $lighten_matches)) {
                $color_to_lighten   = static::convert_scss_variable_to_css($lighten_matches[1]);
                $lighten_percentage = intval($lighten_matches[2]);

                while (str_starts_with($color_to_lighten, 'var')) {
                    $color_to_lighten = $css_variables[static::parse_css_variable_name($color_to_lighten)];
                }

                $color_to_lighten_obj = static::convert_string_to_color($color_to_lighten);

                $css_variables[static::CSS_VARIABLE_PREFIX . $key] = '#' . $color_to_lighten_obj->lighten($lighten_percentage);
            }

            $darken_matches = [];
            if (preg_match('/darken\(\s?(.+?),(.+?)%\s?\)/', $value, $darken_matches)) {
                $color_to_darken   = static::convert_scss_variable_to_css($darken_matches[1]);
                $darken_percentage = intval($darken_matches[2]);

                while (str_starts_with($color_to_darken, 'var')) {
                    $color_to_darken = $css_variables[static::parse_css_variable_name($color_to_darken)];
                }

                $color_to_darken_obj = static::convert_string_to_color($color_to_darken);

                $css_variables[static::CSS_VARIABLE_PREFIX . $key] = '#' . $color_to_darken_obj->darken($darken_percentage);
            }

            $mix_matches = [];
            if (preg_match('/mix\(\s?(.+?),\s?(.+?),\s?(.+?)%\s?\)/', $value, $mix_matches)) {
                $base_color     = static::convert_scss_variable_to_css($mix_matches[1]);
                $color_to_mix   = static::convert_scss_variable_to_css($mix_matches[2]);
                $mix_percentage = intval($mix_matches[3]);

                while (str_starts_with($base_color, 'var')) {
                    $base_color = $css_variables[static::parse_css_variable_name($base_color)];
                }

                while (str_starts_with($color_to_mix, 'var')) {
                    $color_to_mix = $css_variables[static::parse_css_variable_name($color_to_mix)];
                }

                $base_color_obj   = static::convert_string_to_color($base_color);
                $color_to_mix_obj = static::convert_string_to_color($color_to_mix);

                $css_variables[static::CSS_VARIABLE_PREFIX . $key] = '#' . $base_color_obj->mix($color_to_mix_obj->getHex(), $mix_percentage);
            }
        }

        return Yz_Array::join_key_value($css_variables);
    }
}
