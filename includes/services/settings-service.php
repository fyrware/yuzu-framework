<?php

class Yz_Settings_Service {

    public function get_setting(string $option_name, string $default_value = ''): string {
        return get_option($option_name) ?: $default_value;
    }

    public function update_option(string $option, $value): void {
        update_option($option, $value);
    }

    public function register_option(array $options): void {
        $option_group  = yz()->tools->get_value($options, 'option_group');
        $option_name   = yz()->tools->get_value($options, 'option_name');
        $option_label  = yz()->tools->get_value($options, 'option_label');
        $option_value = yz()->tools->get_value($options, 'option_value', '');

        register_setting($option_group, $option_name, [
            'type' => 'string',
            'default' => $option_value,
            'show_in_rest' => true
        ]);

        add_settings_field($option_name, $option_label, function() use($option_name, $option_label, $option_value) { ?>
            <input name="<?= $option_name ?>" type="text" id="<?= $option_name ?>" class="regular-text" value="<?= $this->get_setting($option_name, $option_value) ?>">
        <?php }, 'general');
    }
}