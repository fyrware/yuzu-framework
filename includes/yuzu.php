<?php

require_once __DIR__ . '/elements/avatar.php';
require_once __DIR__ . '/elements/box.php';
require_once __DIR__ . '/elements/button.php';
require_once __DIR__ . '/elements/card.php';
require_once __DIR__ . '/elements/dialog.php';
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
require_once __DIR__ . '/elements/tab-group.php';
require_once __DIR__ . '/elements/table.php';
require_once __DIR__ . '/elements/text.php';
require_once __DIR__ . '/elements/textarea.php';
require_once __DIR__ . '/elements/title.php';

require_once __DIR__ . '/elements/patterns/calendar.php';
require_once __DIR__ . '/elements/patterns/stat-card.php';

require_once __DIR__ . '/elements/pickers/media-picker.php';
require_once __DIR__ . '/elements/pickers/table-combo-picker.php';

class Yz {
    private const BUTTON_DEPENDENCIES      = 'button_dependencies';
    private const CALENDAR_DEPENDENCIES    = 'calendar_dependencies';
    private const CARD_DEPENDENCIES        = 'card_dependencies';
    private const DIALOG_DEPENDENCIES      = 'dialog_dependencies';
    private const EMPTY_STATE_DEPENDENCIES = 'empty_state_dependencies';
    private const FLEX_LAYOUT_DEPENDENCIES = 'flex_layout_dependencies';
    private const GRID_LAYOUT_DEPENDENCIES = 'grid_layout_dependencies';
    private const INPUT_DEPENDENCIES       = 'input_dependencies';
    private const NOTICE_DEPENDENCIES      = 'notice_dependencies';
    private const STAT_CARD_DEPENDENCIES   = 'stat_card_dependencies';
    private const TAB_GROUP_DEPENDENCIES   = 'tab_group_dependencies';
    private const TABLE_DEPENDENCIES       = 'table_dependencies';
    private const TEXT_AREA_DEPENDENCIES   = 'text_area_dependencies';
    private const TITLE_DEPENDENCIES       = 'title_dependencies';

    private const MEDIA_PICKER_DEPENDENCIES       = 'media_picker_dependencies';
    private const TABLE_COMBO_PICKER_DEPENDENCIES = 'table_picker_combo_dependencies';

    /**
     * Render a generic markup element
     * @param string $tag
     * @param array $props
     * @return void
     */
    public static function Element(string $tag, array $props = []): void {
        echo Yz_Markup::create_element($tag, $props);
    }

    /**
     * Render a avatar element
     * @param array $props
     * @return void
     */
    public static function Avatar(array $props = []): void {
        Yz_Avatar::render($props);
    }

    /**
     * Render a box element
     * @param array $props
     * @return void
     */
    public static function Box(array $props = []): void {
        Yz_Box::render($props);
    }

    /**
     * Render a button element
     * @param array $props
     * @return void
     */
    public static function Button(array $props = []): void {
        Yz_Button::render($props);

        Yz_Cache::do_once(static::BUTTON_DEPENDENCIES, function() {
            Yz_Button::render_style();
        });
    }

    /**
     * Render a card element
     * @param array $props
     * @return void
     */
    public static function Card(array $props = []): void {
        Yz_Card::render($props);

        Yz_Cache::do_once(static::CARD_DEPENDENCIES, function() {
            Yz_Card::render_style();
        });
    }

    public static function Calendar(array $props = []): void {
        Yz_Calendar::render($props);

        Yz_Cache::do_once(static::CALENDAR_DEPENDENCIES, function() {
            Yz_Calendar::render_style();
            Yz_Calendar::render_script();
        });
    }

    /**
     * Render a checkbox input element
     * @param array $props
     * @return void
     */
    public static function Checkbox(array $props = []): void {
        Yz::Input(Yz_Array::merge($props, [ 'type' => 'checkbox' ]));
    }

    /**
     * Render a dialog element
     * @param array $props
     * @return void
     */
    public static function Dialog(array $props = []): void {
        Yz_Dialog::render($props);

        Yz_Cache::do_once(static::DIALOG_DEPENDENCIES, function() {
            Yz_Dialog::render_style();
            Yz_Dialog::render_script();
        });
    }

    /**
     * Render an empty state element
     * @param array $props
     * @return void
     */
    public static function Empty_State(array $props = []): void {
        Yz_Empty_State::render($props);

        Yz_Cache::do_once(static::EMPTY_STATE_DEPENDENCIES, function() {
            Yz_Empty_State::render_style();
        });
    }

    /**
     * Render a flex layout element
     * @param array $props
     * @return void
     */
    public static function Flex_Layout(array $props = []): void {
        Yz_Flex_Layout::render($props);

        Yz_Cache::do_once(static::FLEX_LAYOUT_DEPENDENCIES, function() {
            Yz_Flex_Layout::render_style();
        });
    }

    /**
     * Render a form element
     * @param array $props
     * @return void
     */
    public static function Form(array $props = []): void {
        Yz_Form::render($props);
    }

    /**
     * Render a grid layout element
     * @param array $props
     * @return void
     */
    public static function Grid_Layout(array $props = []): void {
        Yz_Grid_Layout::render($props);

        Yz_Cache::do_once(static::GRID_LAYOUT_DEPENDENCIES, function() {
            Yz_Grid_Layout::render_style();
        });
    }

    /**
     * Render an icon element
     * @param string $glyph
     * @param array $props
     * @return void
     */
    public static function Icon(string $glyph, array $props = []): void {
        Yz_Icon::render($glyph, $props);
    }

    /**
     * Render an image element
     * @param string $src
     * @param array $props
     * @return void
     */
    public static function Image(string $src, array $props = []): void {
        Yz_Image::render($src, $props);
    }

    /**
     * Render an input element
     * @param array $props
     * @return void
     */
    public static function Input(array $props = []): void {
        Yz_Input::render($props);

        Yz_Cache::do_once(static::INPUT_DEPENDENCIES, function() {
            Yz_Input::render_style();
        });
    }

    /**
     * Render a media picker element
     * @param array $props
     * @return void
     */
    public static function Media_Picker(array $props = []): void {
        Yz_Media_Picker::render($props);

        Yz_Cache::do_once(static::MEDIA_PICKER_DEPENDENCIES, function() {
            Yz_Media_Picker::render_style();
            Yz_Media_Picker::render_script();
        });
    }

    /**
     * Render a notice element
     * @param array $props
     * @return void
     */
    public static function Notice(array $props = []): void {
        Yz_Notice::render($props);

        Yz_Cache::do_once(static::NOTICE_DEPENDENCIES, function() {
            Yz_Notice::render_style();
        });
    }

    /**
     * Render a portal element
     * @param string $name
     * @param array $props
     * @return void
     */
    public static function Portal(string $name = 'default_portal', array $props = []): void {
        Yz_Portal::render($name, $props);
    }

    /**
     * Render a portal injection element
     * @param string $name
     * @param array $props
     * @return void
     */
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
    public static function Radio(array $props = []): void {
        Yz::Input(Yz_Array::merge($props, [ 'type' => 'radio' ]));
    }

    /**
     * Render a select element
     * @param array $props
     * @return void
     */
    public static function Select(array $props = []): void {
        Yz_Select::render($props);
    }

    public static function Stat_Card(array $props = []): void {
        Yz_Stat_Card::render($props);

        Yz_Cache::do_once(static::STAT_CARD_DEPENDENCIES, function() {
            Yz_Stat_Card::render_style();
        });
    }

    /**
     * Render a tab group element
     * @param array $props
     * @return void
     */
    public static function Tab_Group(array $props = []): void {
        Yz_Tab_Group::render($props);

        Yz_Cache::do_once(static::TAB_GROUP_DEPENDENCIES, function() {
            Yz_Tab_Group::render_style();
            Yz_Tab_Group::render_script();
        });
    }

    /**
     * Render a table element
     * @param array $props
     * @return void
     */
    public static function Table(array $props = []): void {
        Yz_Table::render($props);

        Yz_Cache::do_once(static::TABLE_DEPENDENCIES, function() {
            Yz_Table::render_style();
        });
    }

    /**
     * Render a table combo picker element
     * @param array $props
     * @return void
     */
    public static function Table_Combo_Picker(array $props = []): void {
        Yz_Table_Combo_Picker::render($props);

        Yz_Cache::do_once(static::TABLE_COMBO_PICKER_DEPENDENCIES, function() {
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
    public static function Text(string $text, array $props = []): void {
        Yz_Text::render($text, $props);
    }

    /**
     * Render a text area element
     * @param array $props
     * @return void
     */
    public static function Text_Area(array $props = []): void {
        Yz_Text_Area::render($props);

        Yz_Cache::do_once(static::TEXT_AREA_DEPENDENCIES, function() {
            Yz_Text_Area::render_style();
        });
    }

    /**
     * Render a title element
     * @param string $text
     * @param array $props
     * @return void
     */
    public static function Title(string $text, array $props = []): void {
        Yz_Title::render($text, $props);

        Yz_Cache::do_once(static::TITLE_DEPENDENCIES, function() {
            Yz_Title::render_style();
        });
    }
}
