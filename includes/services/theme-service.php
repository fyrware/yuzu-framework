<?php

class Yz_Theme_Service {

    private array $css_variables = [];

    public function add_support(string $feature): void {
        add_theme_support($feature);
    }

    public function add_menu(string $location, string $description): void {
        register_nav_menu($location, $description);
    }

    public function add_css_variable(string $name, string $value, bool $important = false): void {

        if (empty($this->css_variables)) {
            add_action('wp_head', function() {
                $css_variables = '';

                foreach ($this->css_variables as $name => $variable) {
                    $css_variables .= '--' . $name . ': ' . $variable['value'] . ($variable['important'] ? ' !important' : '') . '; ';
                } ?>

                <style id="yz-theme-css-variables">
                    :root {
                        <?= $css_variables; ?>
                    }
                </style>
            <?php });
        }

        $this->css_variables[$name] = [
            'value'     => $value,
            'important' => $important
        ];
    }
}