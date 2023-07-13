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

/**
 *  Return true if on a page which uses Yuzu framework
 * @return bool
 */
function is_yuzu(): bool {
    return true;
}

require_once plugin_dir_path(__FILE__) . 'admin/page.php';
require_once plugin_dir_path(__FILE__) . 'admin/table.php';

add_action('init', function() {
    require_once plugin_dir_path(__FILE__) . 'yuzu-icon.php';

    if (is_admin()) {
        require_once plugin_dir_path(__FILE__) . 'admin/elements/button.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/button-group.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/flex-layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/grid-layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/icon.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/meta-box.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/tab-group.php';

        require_once plugin_dir_path(__FILE__) . 'admin/input.php';
        require_once plugin_dir_path(__FILE__) . 'admin/layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/menu.php';
    }
}, 0);

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
    wp_enqueue_style('yuzu-admin-css', plugin_dir_url(__FILE__) . 'yuzu-admin.css');
});
