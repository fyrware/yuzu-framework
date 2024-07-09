<?php

require_once __DIR__ . '/services/asset-service.php';
require_once __DIR__ . '/services/calendar-service.php';
require_once __DIR__ . '/services/database-service.php';
require_once __DIR__ . '/services/forms-service.php';
require_once __DIR__ . '/services/forum-service.php';
require_once __DIR__ . '/services/geolocation-service.php';
require_once __DIR__ . '/services/html-service.php';
require_once __DIR__ . '/services/pages-service.php';
require_once __DIR__ . '/services/posts-service.php';
require_once __DIR__ . '/services/roles-service.php';
require_once __DIR__ . '/services/settings-service.php';
require_once __DIR__ . '/services/shop-service.php';
require_once __DIR__ . '/services/theme-service.php';
require_once __DIR__ . '/services/tools-service.php';
require_once __DIR__ . '/services/uploads-service.php';

require_once __DIR__ . '/elements/avatar.php';
require_once __DIR__ . '/elements/box.php';
require_once __DIR__ . '/elements/button.php';
require_once __DIR__ . '/elements/card.php';
require_once __DIR__ . '/elements/code-block.php';
require_once __DIR__ . '/elements/dialog.php';
require_once __DIR__ . '/elements/divider.php';
require_once __DIR__ . '/elements/empty-state.php';
require_once __DIR__ . '/elements/flex-layout.php';
require_once __DIR__ . '/elements/form.php';
require_once __DIR__ . '/elements/grid-layout.php';
require_once __DIR__ . '/elements/icon.php';
require_once __DIR__ . '/elements/image.php';
require_once __DIR__ . '/elements/input.php';
require_once __DIR__ . '/elements/notice.php';
require_once __DIR__ . '/elements/portal.php';
require_once __DIR__ . '/elements/select.php';
require_once __DIR__ . '/elements/settings-table.php';
require_once __DIR__ . '/elements/tab-group.php';
require_once __DIR__ . '/elements/table.php';
require_once __DIR__ . '/elements/text.php';
require_once __DIR__ . '/elements/textarea.php';
require_once __DIR__ . '/elements/title.php';

require_once __DIR__ . '/elements/patterns/calendar.php';
require_once __DIR__ . '/elements/patterns/rich-text-editor.php';
require_once __DIR__ . '/elements/patterns/stat-card.php';

require_once __DIR__ . '/elements/pickers/icon-picker.php';
require_once __DIR__ . '/elements/pickers/media-picker.php';
require_once __DIR__ . '/elements/pickers/upload-picker.php';
require_once __DIR__ . '/elements/pickers/table-combo-picker.php';

class Yz {
    private const BUTTON_DEPENDENCIES             = 'yz_button_dependencies';
    private const CALENDAR_DEPENDENCIES           = 'yz_calendar_dependencies';
    private const CARD_DEPENDENCIES               = 'yz_card_dependencies';
    private const CODE_BLOCK_DEPENDENCIES         = 'yz_code_block_dependencies';
    private const DIALOG_DEPENDENCIES             = 'yz_dialog_dependencies';
    private const EMPTY_STATE_DEPENDENCIES        = 'yz_empty_state_dependencies';
    private const FLEX_LAYOUT_DEPENDENCIES        = 'yz_flex_layout_dependencies';
    private const GRID_LAYOUT_DEPENDENCIES        = 'yz_grid_layout_dependencies';
    private const INPUT_DEPENDENCIES              = 'yz_input_dependencies';
    private const MEDIA_PICKER_DEPENDENCIES       = 'yz_media_picker_dependencies';
    private const NOTICE_DEPENDENCIES             = 'yz_notice_dependencies';
    private const RICH_TEXT_EDITOR_DEPENDENCIES   = 'yz_rich_text_editor_dependencies';
    private const STAT_CARD_DEPENDENCIES          = 'yz_stat_card_dependencies';
    private const TAB_GROUP_DEPENDENCIES          = 'yz_tab_group_dependencies';
    private const TABLE_DEPENDENCIES              = 'yz_table_dependencies';
    private const TABLE_COMBO_PICKER_DEPENDENCIES = 'yz_table_picker_combo_dependencies';
    private const TEXT_AREA_DEPENDENCIES          = 'yz_text_area_dependencies';
    private const TITLE_DEPENDENCIES              = 'yz_title_dependencies';
    private const UPLOAD_PICKER_DEPENDENCIES      = 'yz_upload_picker_dependencies';

    public static Yz $instance;

    public Yz_Asset_Service $assets;
    public Yz_Calendar_Service $calendar;
    public Yz_Database_Service $database;
    public Yz_Form_Service $forms;
    public Yz_Forum_Service $forums;
    public Yz_Geolocation_Service $geolocation;
    public Yz_Html_Service $html;
    public Yz_Pages_Service $pages;
    public Yz_Posts_Service $posts;
    public Yz_Roles_Service $roles;
    public Yz_Settings_Service $settings;
    public Yz_Shop_Service $shop;
    public Yz_Theme_Service $theme;
    public Yz_Tools_Service $tools;
    public Yz_Uploads_Service $uploads;

    public function __construct() {
        $this->assets      = new Yz_Asset_Service();
        $this->calendar    = new Yz_Calendar_Service();
        $this->database    = new Yz_Database_Service();
        $this->forms       = new Yz_Form_Service();
        $this->forums      = new Yz_Forum_Service();
        $this->geolocation = new Yz_Geolocation_Service();
        $this->html        = new Yz_Html_Service();
        $this->pages       = new Yz_Pages_Service();
        $this->posts       = new Yz_Posts_Service();
        $this->roles       = new Yz_Roles_Service();
        $this->settings    = new Yz_Settings_Service();
        $this->shop        = new Yz_Shop_Service();
        $this->theme       = new Yz_Theme_Service();
        $this->tools       = new Yz_Tools_Service();
        $this->uploads     = new Yz_Uploads_Service();
    }

    public function load_scripts(): void {
        wp_enqueue_script('yuzu-framework-observable-js', plugin_dir_url(__FILE__) . 'scripts/observable.js');
        wp_enqueue_script('yuzu-framework-action-service-js', plugin_dir_url(__FILE__) . 'scripts/services/action-service.js');
        wp_enqueue_script('yuzu-framework-ajax-service-js', plugin_dir_url(__FILE__) . 'scripts/services/ajax-service.js');
        wp_enqueue_script('yuzu-framework-cookie-service-js', plugin_dir_url(__FILE__) . 'scripts/services/cookie-service.js');
        wp_enqueue_script('yuzu-framework-icon-service-js', plugin_dir_url(__FILE__) . 'scripts/services/icon-service.js');
        wp_enqueue_script('yuzu-framework-notification-service-js', plugin_dir_url(__FILE__) . 'scripts/services/notification-service.js');
        wp_enqueue_script('yuzu-framework-ys-js', plugin_dir_url(__FILE__) . 'scripts/yz.js');
        wp_enqueue_script('yuzu-framework-template-service-js', plugin_dir_url(__FILE__) . 'scripts/services/template-service.js');
        wp_enqueue_script('yuzu-framework-plugin-js', plugin_dir_url(__FILE__) . '../yz-plugin.js');
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /** @deprecated */
    private static array $action_queue = [];

    /** @deprecated */
    public static function use_once(string $action, callable $callback): void {
        if (!has_action($action)) {
            static::$action_queue[] = $action;
            add_action($action, $callback);
        }
    }

    /** @deprecated */
    public static function use_admin_menu_separator(float $position): void {
        Yz_Page::add_menu_separator($position);
    }

    /** @deprecated */
    public static function use_admin_page(array $props = []): string {
        return Yz_Page::add_page($props, static::$action_queue);
    }

    /** @deprecated */
    public static function use_post_type(string $name, array $props = []): WP_Post_Type {
        return Yz_Post::add_post_type($name, $props);
    }

    /** @deprecated */
    public static function use_post(array $post_options): int {
        return Yz_Posts_Service::write_post($post_options);
    }

    /** @deprecated */
    public static function use_prop(array $props, string $key, $default = null): mixed {
        return Yz_Array::value_or($props, $key, $default);
    }

    /** @deprecated */
    public static function use_form(string $action, callable $handler): bool | null {
        return Yz_Forms_Service::register_form($action, $handler);
    }

    /** @deprecated */
    public static function use_redirect(?string $location = null): void {
        Yz_Forms_Service::redirect($location);
    }

    /** @deprecated */
    public static function use_async_form(string $action, callable $handler): bool | null {
        return Yz_Forms_Service::register_async_form($action, $handler);
    }

    /** @deprecated */
    public static function use_plugin(string $name): bool {
        return Yz_Plugin::is_active($name);
    }

    /** @deprecated */
    public static function use_console(mixed ...$args): void {
        Yz_Script::console_log(...$args);
    }

    /**
     * Render a generic markup element
     * @param string $tag
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Element(string $tag, array $props = []): void {
        echo Yz_Markup::create_element($tag, $props);
    }

    /**
     * Render a avatar element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Avatar(array $props = []): void {
        Yz_Avatar::render($props);
    }

    /**
     * Render a box element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Box(array $props = []): void {
        Yz_Box::render($props);
    }

    /**
     * Render a button element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Button(array $props = []): void {
        Yz_Button::render($props);

        static::use_once(static::BUTTON_DEPENDENCIES, function() {
            Yz_Button::render_style();
        });
    }

    /**
     * Render a card element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Card(array $props = []): void {
        Yz_Card::render($props);

        static::use_once(static::CARD_DEPENDENCIES, function() {
            Yz_Card::render_style();
        });
    }

    /** @deprecated */
    public static function Calendar(array $props = []): void {
        Yz_Calendar::render($props);

        static::use_once(static::CALENDAR_DEPENDENCIES, function() {
            Yz_Calendar::render_style();
            Yz_Calendar::render_script();
        });
    }

    /**
     * Render a checkbox input element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Checkbox(array $props = []): void {
        Yz::Input(Yz_Array::merge($props, [ 'type' => 'checkbox' ]));
    }

    /**
     * Render a code block element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Code_Block(array $props = []): void {
        Yz_Code_Block::render($props);

        static::use_once(static::CODE_BLOCK_DEPENDENCIES, function() {
            Yz_Code_Block::render_style();
            Yz_Code_Block::render_script();
        });
    }

    /**
     * Render a dialog element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Dialog(array $props = []): void {
        Yz_Dialog::render($props);

        static::use_once(static::DIALOG_DEPENDENCIES, function() {
            Yz_Dialog::render_style();
            Yz_Dialog::render_script();
        });
    }

    /**
     * Render an empty state element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Empty_State(array $props = []): void {
        Yz_Empty_State::render($props);

        static::use_once(static::EMPTY_STATE_DEPENDENCIES, function() {
            Yz_Empty_State::render_style();
        });
    }

    /**
     * Render a flex layout element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Flex_Layout(array $props = []): void {
        Yz_Flex_Layout::render($props);

        static::use_once(static::FLEX_LAYOUT_DEPENDENCIES, function() {
            Yz_Flex_Layout::render_style();
        });
    }

    /**
     * Render a form element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Form(array $props = []): void {
        Yz_Form::render($props);
    }

    /**
     * Render a grid layout element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Grid_Layout(array $props = []): void {
        Yz_Grid_Layout::render($props);

        static::use_once(static::GRID_LAYOUT_DEPENDENCIES, function() {
            Yz_Grid_Layout::render_style();
        });
    }

    /**
     * Render an icon element
     * @param string $glyph
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Icon(string $glyph, array $props = []): void {
        Yz_Icon::render($glyph, $props);
    }

    /**
     * Render an image element
     * @param string $src
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Image(string $src, array $props = []): void {
        Yz_Image::render($src, $props);
    }

    /**
     * Render an input element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Input(array $props = []): void {
        Yz_Input::render($props);

        static::use_once(static::INPUT_DEPENDENCIES, function() {
            Yz_Input::render_style();
        });
    }

    /**
     * Render a media picker element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Media_Picker(array $props = []): void {
        Yz_Media_Picker::render($props);

        static::use_once(static::MEDIA_PICKER_DEPENDENCIES, function() {
            Yz_Media_Picker::render_style();
            Yz_Media_Picker::render_script();
        });
    }

    /**
     * Render a notice element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Notice(array $props = []): void {
        Yz_Notice::render($props);

        static::use_once(static::NOTICE_DEPENDENCIES, function() {
            Yz_Notice::render_style();
        });
    }

    /**
     * Render a portal element
     * @param string $name
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Portal(string $name = 'default_portal', array $props = []): void {
        Yz_Portal::render($name, $props);
    }

    /**
     * Render a portal injection element
     * @param string $name
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Portal_Injection(string $name, array $props = []): void {
        $children = Yz_Array::value_or($props, 'children');

        if (is_callable($children)) {
            Yz_Portal::inject($name, $children);
        }
    }

    /**
     * Render a radio input element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Radio(array $props = []): void {
        Yz::Input(Yz_Array::merge($props, [ 'type' => 'radio' ]));
    }

    /**
     * Render a rich text editor element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Rich_Text_Editor(array $props): void {
        Yz_Rich_Text_Editor::render($props);

        static::use_once(static::RICH_TEXT_EDITOR_DEPENDENCIES, function() {
            Yz_Rich_Text_Editor::render_style();
        });
    }

    /**
     * Render a select element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Select(array $props = []): void {
        Yz_Select::render($props);
    }

    /** @deprecated */
    public static function Stat_Card(array $props = []): void {
        Yz_Stat_Card::render($props);

        static::use_once(static::STAT_CARD_DEPENDENCIES, function() {
            Yz_Stat_Card::render_style();
        });
    }

    /**
     * Render a tab group element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Tab_Group(array $props = []): void {
        Yz_Tab_Group::render($props);

        static::use_once(static::TAB_GROUP_DEPENDENCIES, function() {
            Yz_Tab_Group::render_style();
            Yz_Tab_Group::render_script();
        });
    }

    /**
     * Render a table element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Table(array $props = []): void {
        Yz_Table::render($props);

        static::use_once(static::TABLE_DEPENDENCIES, function() {
            Yz_Table::render_style();
        });
    }

    /**
     * Render a table combo picker element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Table_Combo_Picker(array $props = []): void {
        Yz_Table_Combo_Picker::render($props);

        static::use_once(static::TABLE_COMBO_PICKER_DEPENDENCIES, function() {
            Yz_Table_Combo_Picker::render_style();
            Yz_Table_Combo_Picker::render_script();
        });
    }

    /**
     * Render a text element
     * @param string $text
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Text(string $text, array $props = []): void {
        Yz_Text::render($text, $props);
    }

    /**
     * Render a text area element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Text_Area(array $props = []): void {
        Yz_Text_Area::render($props);

        static::use_once(static::TEXT_AREA_DEPENDENCIES, function() {
            Yz_Text_Area::render_style();
        });
    }

    /**
     * Render a title element
     * @param string $text
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Title(string $text, array $props = []): void {
        Yz_Title::render($text, $props);

        static::use_once(static::TITLE_DEPENDENCIES, function() {
            Yz_Title::render_style();
        });
    }

    /**
     * Render an upload picker element
     * @param array $props
     * @return void
     */
    /** @deprecated */
    public static function Upload_Picker(array $props = []): void{
        Yz_Upload_Picker::render($props);

        static::use_once(static::UPLOAD_PICKER_DEPENDENCIES, function () {
            Yz_Upload_Picker::render_style();
            Yz_Upload_Picker::render_script();
        });
    }

    /** @deprecated */
    public static function add_page(array $props = []): string {
        return Yz_Page::add_page($props);
    }

    /** @deprecated */
    public static function add_post_type(string $name, array $props = []): WP_Post_Type {
        return Yz_Post::add_post_type($name, $props);
    }

    /** @deprecated */
    public static function add_form_handler(string $action, callable $handler): bool | null {
        return Yz_Form::add_handler($action, $handler);
    }
}

$yz = Yz::$instance = new Yz();

function yz(): Yz {
    global $yz;
    return $yz;
}
