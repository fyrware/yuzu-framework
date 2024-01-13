<?php

class Yz_Asset_Service {

    public function get_assets_url(): string {
        return get_template_directory_uri() . '/assets';
    }

    public function get_asset_url(array | string $name): string {
        return get_template_directory_uri() . '/assets/' . (is_array($name) ? implode('/', $name) : $name);
    }

    public function add_phosphor_icons(): void {
        wp_enqueue_script('phosphor-icons', 'https://unpkg.com/@phosphor-icons/web');
    }

    public function add_theme_style(): void {
        wp_enqueue_style('theme-style', get_template_directory_uri() . '/style.css');
    }

    public function add_style(string $name): void {
        wp_enqueue_style('yz-style-' . $name, get_template_directory_uri() . '/assets/styles/' . $name . '.css');
    }

    public function add_script(string $name): void {
        wp_enqueue_script('yz-script-' . $name, get_template_directory_uri() . '/assets/scripts/' . $name . '.js');
    }
}