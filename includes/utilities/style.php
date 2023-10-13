<?php

use \Mexitek\PHPColors\Color;

class Yz_Style {

    private const SCSS_VARIABLE_PATTERN = '/\$(.*?): (.*?)(?: !default)?;/';

    private static function convert_string_to_color(string $color): Color {
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

    /**
     * @throws Exception
     */
    public static function load_admin_style_variables(): string {
        $admin_color = get_user_option('admin_color');
        $admin_dir   = str_replace(get_bloginfo('url') . '/', ABSPATH, get_admin_url());

        $base_variables_scss = file_get_contents($admin_dir . 'css/colors/_variables.scss');
        $base_variables      = [];

        if ($base_variables_scss) {
            preg_match_all(static::SCSS_VARIABLE_PATTERN, $base_variables_scss, $base_variables);
            $base_variables = array_combine($base_variables[1], $base_variables[2]);
        }

        if ($admin_color !== 'fresh') {
            $color_scheme_scss = file_get_contents($admin_dir . 'css/colors/' . $admin_color . '/colors.scss');

            if ($color_scheme_scss) {
                preg_match_all(static::SCSS_VARIABLE_PATTERN, $color_scheme_scss, $color_scheme_variables);
                $color_scheme_variables = array_combine($color_scheme_variables[1], $color_scheme_variables[2]);
                $base_variables         = array_merge($base_variables, $color_scheme_variables);
            }
        }

        $wp_variables = [];

        foreach ($base_variables as $key => $value) {
            $wp_variables['--yz-' . $key] = str_starts_with($value, '$')
                ? 'var(--yz-' . substr($value, 1) . ')'
                : $value;

            $lighten_matches = [];
            if (preg_match('/lighten\(\s?(.+?),(.+?)%\s?\)/', $value, $lighten_matches)) {
                $lighten_percentage = intval($lighten_matches[2]);
                $color_to_lighten   = str_starts_with($lighten_matches[1], '$')
                    ? $wp_variables['--yz-' . substr($lighten_matches[1], 1)]
                    : $lighten_matches[1];

                while (str_starts_with($color_to_lighten, 'var')) {
                    $color_to_lighten = $wp_variables[preg_replace('/var\((.+?)\)/', '$1', $color_to_lighten)];
                }

                $wp_variables['--yz-' . $key] = '#' . (new Color($color_to_lighten))->lighten($lighten_percentage);
            }

            $darken_matches = [];
            if (preg_match('/darken\(\s?(.+?),(.+?)%\s?\)/', $value, $darken_matches)) {
                $darken_percentage = intval($darken_matches[2]);
                $color_to_darken   = str_starts_with($darken_matches[1], '$')
                    ? $wp_variables['--yz-' . substr($darken_matches[1], 1)]
                    : $darken_matches[1];

                while (str_starts_with($color_to_darken, 'var')) {
                    $color_to_darken = $wp_variables[preg_replace('/var\((.+?)\)/', '$1', $color_to_darken)];
                }

                $wp_variables['--yz-' . $key] = '#' . (new Color($color_to_darken))->darken($darken_percentage);
            }

            $mix_matches = [];
            if (preg_match('/mix\(\s?(.+?),\s?(.+?),\s?(.+?)%\s?\)/', $value, $mix_matches)) {
                $mix_percentage = intval($mix_matches[3]);
                $base_color     = str_starts_with($mix_matches[1], '$')
                    ? $wp_variables['--yz-' . substr($mix_matches[1], 1)]
                    : $mix_matches[1];
                $color_to_mix   = str_starts_with($mix_matches[2], '$')
                    ? $wp_variables['--yz-' . substr($mix_matches[2], 1)]
                    : $mix_matches[2];

                while (str_starts_with($base_color, 'var')) {
                    $base_color = $wp_variables[preg_replace('/var\((.+?)\)/', '$1', $base_color)];
                }

                while (str_starts_with($color_to_mix, 'var')) {
                    $color_to_mix = $wp_variables[preg_replace('/var\((.+?)\)/', '$1', $color_to_mix)];
                }

                $base_color_obj   = static::convert_string_to_color($base_color);
                $color_to_mix_obj = static::convert_string_to_color($color_to_mix);

                $wp_variables['--yz-' . $key] = '#' . $base_color_obj->mix($color_to_mix_obj->getHex(), $mix_percentage);
            }
        }

        return Yz_Array::join_key_value($wp_variables);
    }
}
