<?php

class Yz_Rich_Text_Editor {

    public static function render(array $props): void {
        global $yz;

        $id                = $yz->tools->key_or_default($props, 'id');
        $class             = $yz->tools->key_or_default($props, 'class');
        $name              = $yz->tools->key_or_default($props, 'name', $id);
        $value             = $yz->tools->key_or_default($props, 'value', '');
        $rows              = $yz->tools->key_or_default($props, 'rows', 5);
        $enable_media      = $yz->tools->key_or_default($props, 'enable_media', false);
        $enable_raw_editor = $yz->tools->key_or_default($props, 'enable_raw_editor', false);

        $classes = [
            'rich-text-editor'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('div', [
            'class' => $classes,
            'children' => function() use($id, $name, $value, $rows, $enable_media, $enable_raw_editor) {
                wp_editor($value, $id, [
                    'quicktags'     => $enable_raw_editor,
                    'media_buttons' => $enable_media,
                    'textarea_rows' => $rows,
                    'textarea_name' => $name,
                    'editor_class'  => 'yz rich-text-editor-textarea'
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="rich-text-editor">
            .yz.rich-text-editor .wp-editor-tabs {
                margin-right: 8px;
            }

            .yz.rich-text-editor .wp-editor-tabs button {
                background: #dcdcde;
                color: #50575e;
                border-color: var(--yz-input-border-color);
                border-radius: 4px 4px 0 0;
                font-weight: 700;
            }

            .yz.rich-text-editor .tmce-active .switch-tmce {
                border-bottom-color: transparent;
                background: var(--yz-body-background-color);
                color: black;
            }

            .yz.rich-text-editor .mce-top-part::before {
                -webkit-box-shadow: none;
                box-shadow: none;
            }

            .yz.rich-text-editor .mce-toolbar-grp {
                background: var(--yz-body-background-color);
                border-radius: 4px 4px 0 0 !important;
                border-bottom: 1px solid var(--yz-input-border-color);
            }

            .yz.rich-text-editor .wp-editor-container {
                border-radius: 4px 4px 4px 4px;
                border: 1px solid var(--yz-input-border-color);
            }

            .yz.rich-text-editor .mce-panel {
                border-radius: inherit;
            }

            .yz.rich-text-editor .mce-panel.mce-statusbar {
                border-top: 1px solid var(--yz-input-border-color);
                border-radius: 0 0 4px 4px;
            }
        </style>
    <?php }
}