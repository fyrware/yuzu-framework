<?php

class Yz_Html_Service {

    public array $dependency_queue = [];

    private function enqueue_dependency(string $action, callable $callback): void {
        Yz_Script::console_log('enqueue_dependency', $action);
        if (!has_action($action)) {
            $this->dependency_queue[] = $action;
            add_action($action, $callback);
        }
    }

    public function element(string $tag, array $props = []): void {
        echo Yz_Markup::create_element($tag, $props);
    }

    public function avatar(array $props = []): void {
        Yz_Avatar::render($props);

        $this->enqueue_dependency('yz_avatar_dependencies', function() {
            Yz_Avatar::render_style();
        });
    }

    public function box(array $props = []): void {
        Yz_Box::render($props);
    }

    public function button(array $props = []): void {
        Yz_Button::render($props);

        $this->enqueue_dependency('yz_button_dependencies_2', function() {
            Yz_Button::render_style();
        });
    }

    public function card(array $props = []): void {
        Yz_Card::render($props);

        $this->enqueue_dependency('yz_card_dependencies', function() {
            Yz_Card::render_style();
        });
    }

    public function calendar(array $props = []): void {
        Yz_Calendar::render($props);

        $this->enqueue_dependency('yz_calendar_dependencies', function() {
            Yz_Calendar::render_style();
            Yz_Calendar::render_script();
        });
    }

    public function checkbox(array $props = []): void {
        $this->input(Yz_Array::merge($props, [ 'type' => 'checkbox' ]));
    }

    public function code_block(array $props = []): void {
        Yz_Code_Block::render($props);

        $this->enqueue_dependency('yz_code_block_dependencies', function() {
            Yz_Code_Block::render_style();
            Yz_Code_Block::render_script();
        });
    }

    public function dialog(array $props = []): void {
        Yz_Dialog::render($props);

        $this->enqueue_dependency('yz_dialog_dependencies', function() {
            Yz_Dialog::render_style();
            Yz_Dialog::render_script();
        });
    }

    public function empty_state(array $props = []): void {
        Yz_Empty_State::render($props);

        $this->enqueue_dependency('yz_empty_state_dependencies', function() {
            Yz_Empty_State::render_style();
        });
    }

    public function flex_layout(array $props = []): void {
        Yz_Flex_Layout::render($props);

        $this->enqueue_dependency('yz_flex_layout_dependencies', function() {
            Yz_Flex_Layout::render_style();
        });
    }

    public function form(array $props = []): void {
        Yz_Form::render($props);
    }

    public function grid_layout(array $props = []): void {
        Yz_Grid_Layout::render($props);

        $this->enqueue_dependency('yz_grid_layout_dependencies', function() {
            Yz_Grid_Layout::render_style();
        });
    }

    public function icon(string $glyph, array $props = []): void {
        Yz_Icon::render($glyph, $props);
    }

    public function image(string $src, array $props = []): void {
        Yz_Image::render($src, $props);
    }

    public function input(array $props = []): void {
        Yz_Input::render($props);

        $this->enqueue_dependency('yz_input_dependencies', function() {
            Yz_Input::render_style();
        });
    }

    public function media_picker(array $props = []): void {
        Yz_Media_Picker::render($props);

        $this->enqueue_dependency('yz_media_picker_dependencies', function() {
            Yz_Media_Picker::render_style();
            Yz_Media_Picker::render_script();
        });
    }

    public function notice(array $props = []): void {
        Yz_Notice::render($props);

        $this->enqueue_dependency('yz_notice_dependencies', function() {
            Yz_Notice::render_style();
        });
    }

    public function radio(array $props = []): void {
        $this->input(Yz_Array::merge($props, [ 'type' => 'radio' ]));
    }

    public function rich_text_editor(array $props = []): void {
        Yz_Rich_Text_Editor::render($props);

        $this->enqueue_dependency('yz_rich_text_editor_dependencies', function() {
            Yz_Rich_Text_Editor::render_style();
        });
    }

    public function select(array $props = []): void {
        Yz_Select::render($props);
    }

    public function stat_card(array $props = []): void {
        Yz_Stat_Card::render($props);

        $this->enqueue_dependency('yz_stat_card_dependencies', function() {
            Yz_Stat_Card::render_style();
        });
    }

    public function tab_group(array $props = []): void {
        Yz_Tab_Group::render($props);

        $this->enqueue_dependency('yz_tab_group_dependencies', function() {
            Yz_Tab_Group::render_style();
            Yz_Tab_Group::render_script();
        });
    }

    public function table(array $props = []): void {
        Yz_Table::render($props);

        $this->enqueue_dependency('yz_table_dependencies', function() {
            Yz_Table::render_style();
        });
    }

    public function table_combo_picker(array $props = []): void {
        Yz_Table_Combo_Picker::render($props);

        $this->enqueue_dependency('yz_table_combo_picker_dependencies', function() {
            Yz_Table_Combo_Picker::render_style();
            Yz_Table_Combo_Picker::render_script();
        });
    }

    public function text(string $text, array $props = []): void {
        Yz_Text::render($text, $props);
    }

    public function text_area(array $props = []): void {
        Yz_Text_Area::render($props);

        $this->enqueue_dependency('yz_text_area_dependencies', function() {
            Yz_Text_Area::render_style();
        });
    }

    public function title(string $text, array $props = []): void {
        Yz_Title::render($text, $props);

        $this->enqueue_dependency('yz_title_dependencies', function() {
            Yz_Title::render_style();
        });
    }

    public function transport_destination(string $name, array $props = []): void {
        Yz_Portal::render($name, $props);
    }

    public function transport_source(string $name, array $props = []): void {
        $children = $props['children'] ?? [];

        if (is_callable($children)) {
            Yz_Portal::inject($name, $children);
        }
    }

    public function upload_picker(array $props = []): void {
        Yz_Upload_Picker::render($props);

        $this->enqueue_dependency('yz_upload_picker_dependencies', function() {
            Yz_Upload_Picker::render_style();
            Yz_Upload_Picker::render_script();
        });
    }
}