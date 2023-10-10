<?php

class Yz_Style {

    private const SCSS_VARIABLE_PATTERN = '/\$(.*?): (.*?)(?: !default)?;/';

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
        }

        return Yz_Array::join_key_value($wp_variables);
    }
}
