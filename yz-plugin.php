<?php
/**
 * Plugin Name: Yz Plugin Framework
 * Plugin URI: https://fyrware.com/
 * Description: Quickly and elegantly build WordPress plugins
 * Author: Fyrware
 * Version: 0.0.1
 * Author URI: https://fyrware.com
 * Text Domain: yuzu
 */

require_once plugin_dir_path(__FILE__) . 'includes/utilities/array.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/buffer.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/cache.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/markup.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/page.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/plugin.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/post.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/schema.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/script.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/string.php';
require_once plugin_dir_path(__FILE__) . 'includes/utilities/style.php';
require_once plugin_dir_path(__FILE__) . 'includes/yuzu.php';

CONST YZ = 'yz';

class Yz_Plugin_Framework {
    public const TEXT_DOMAIN  = YZ;
    public const READY_ACTION = YZ . '_ready';
}

add_action('plugins_loaded', function() {
    do_action(Yz_Plugin_Framework::READY_ACTION);
}, 0);

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('d3js',  'https://cdn.jsdelivr.net/npm/d3@7');
    wp_enqueue_script('dayjs', 'https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js');
});

add_action('admin_head', function() { ?>
    <style>
        :root {
            <?= Yz_Style::load_admin_style_variables(); ?>
            --yz-highlight-color-alt: #38b56d;
        }
    </style>
<?php });














/**
 *  Return true if on a page which uses Yuzu framework
 * @return bool
 */
function is_yuzu(): bool {
    return true;
}

add_action('plugins_loaded', function() {
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/action.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/array.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/capture.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/debug.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/format.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/html.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/logic.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/misc.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/page.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/post-type.php';
    require_once plugin_dir_path(__FILE__) . 'admin/utilities/svg.php';

    if (is_admin() || is_login()) {
        require_once plugin_dir_path(__FILE__) . 'admin/elements/avatar.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/button.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/button-group.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/card.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/color-picker.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/dialog.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/empty-state.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/flex-layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/form.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/grid-layout.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/icon.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/icon-picker.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/image.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/input.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/media-picker.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/meta-box.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/notice.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/paragraph.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/portal.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/select.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/settings-table.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/tab-group.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/table.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/text.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/textarea.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/title.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/toolbar.php';
        require_once plugin_dir_path(__FILE__) . 'admin/elements/patterns/stat-card.php';
    }
}, -1);

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('yuzu-util-debounce-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/debounce.js');
    wp_enqueue_script('yuzu-util-dialog-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/dialog.js');
    wp_enqueue_script('yuzu-util-element-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/element.js');
    wp_enqueue_script('yuzu-util-icon-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/icon.js');
    wp_enqueue_script('yuzu-util-picker-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/picker.js');
    wp_enqueue_script('yuzu-util-ready-js', plugin_dir_url(__FILE__) . 'admin/scripts/utilities/ready.js');

    wp_enqueue_style('yuzu-framework-css', plugin_dir_url(__FILE__) . 'yz-plugin.css');
    wp_enqueue_script('yuzu-framework-js', plugin_dir_url(__FILE__) . 'yz-plugin.js');
});
