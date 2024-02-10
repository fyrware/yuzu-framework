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

require_once plugin_dir_path(__FILE__) . 'colors/src/Mexitek/PHPColors/Color.php';

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

    wp_enqueue_style('highlightjs', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs.min.css');
    wp_enqueue_script('highlightjs', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js');
    wp_enqueue_script('highlightjs-php', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js');
    wp_enqueue_script('highlightjs-line-numbers', 'https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js');
});

add_action('admin_head', function() {
    $start_time = microtime(true); ?>
    <style>
        :root {
            <?= Yz_Style::load_admin_style_variables(); ?>
            --yz-scss-conversion-duration: <?= (microtime(true) - $start_time) * 1000; ?>ms;
            --yz-body-background-color: #f0f0f1;
            --yz-section-border-color: #c3c4c7;
            --yz-input-border-color: #8c8f94;
            --yz-info-color: #178aff;
            --yz-success-color: #00b75d;
            --yz-warning-color: #f88f05;
            --yz-danger-color: #e63a2b;

            --yz-transparent-bg-first: #ffffff;
            --yz-transparent-bg-second: #f0f0f1;
            --yz-transparent-bg-image:
                repeating-linear-gradient(
                    45deg,
                    var(--yz-transparent-bg-second) 25%,
                    transparent 25%,
                    transparent 75%,
                    var(--yz-transparent-bg-second) 75%,
                    var(--yz-transparent-bg-second)
                ),
                repeating-linear-gradient(
                    45deg,
                    var(--yz-transparent-bg-second) 25%,
                    var(--yz-transparent-bg-first) 25%,
                    var(--yz-transparent-bg-first) 75%,
                    var(--yz-transparent-bg-second) 75%,
                    var(--yz-transparent-bg-second)
                );
            --yz-transparent-bg-position: 0 0, 8px 8px;
            --yz-transparent-bg-size: 16px 16px;
        }

        #adminmenu li.wp-menu-separator {
            height: 1px;
            margin: 10px 0;
            background-color: var(--yz-menu-submenu-background-alt);
        }
    </style>
<?php });

add_action('init', function() {
    global $yz;

    $yz->forms->register_async_form('yz_read_uploads_directory', function() use($yz) {
        $path = $yz->tools->get_value($_GET, 'path');

        if ($path === 'undefined') {
            $path = null;
        }

        $scanned_directory = Yz_Uploads_Service::scan_uploads_dir($path);

        wp_send_json_success($scanned_directory->to_array());
    });
});














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
