<?php
/**
 * Plugin Name: Yuzu Framework
 * Plugin URI: https://fyrware.com/
 * Description: Quickly and elegantly build WordPress plugins
 * Author: Fyrware
 * Version: 0.0.1
 * Author URI: https://fyrware.com
 * Text Domain: yuzu
 */

/**
 * I am hoping and praying that this constant never becomes useful.
 */
const YUZU_VERSION = '0.0.1';

function is_yuzu() {
    return true;
}

function yuzu_admin_enqueue_media() {
    wp_enqueue_media();
    wp_enqueue_global_styles_css_custom_properties();
}

add_action('admin_enqueue_scripts', 'yuzu_admin_enqueue_media');

function yuzu_init() {
    require_once plugin_dir_path(__FILE__) . 'yuzu-icon.php';

    if (is_admin()) {
        require_once plugin_dir_path(__FILE__) . 'admin/input.php';
        require_once plugin_dir_path(__FILE__) . 'admin/layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/menu.php';
    }
}

add_action('init', 'yuzu_init', 0);
