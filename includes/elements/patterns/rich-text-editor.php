<?php

class Yz_Rich_Text_Editor {

    public static function render(array $props): void {
        global $yz;

        $id                = $yz->tools->get_value($props, 'id');
        $class             = $yz->tools->get_value($props, 'class');
        $name              = $yz->tools->get_value($props, 'name', $id);
        $value             = $yz->tools->get_value($props, 'value', '');
        $label             = $yz->tools->get_value($props, 'label');
        $rows              = $yz->tools->get_value($props, 'rows', 5);
        $height            = $yz->tools->get_value($props, 'height');
        $enable_media      = $yz->tools->get_value($props, 'enable_media', false);
        $enable_raw_editor = $yz->tools->get_value($props, 'enable_raw_editor', false);

        $classes = [
            'rich-text-editor'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('div', [
            'class' => $classes,
            'children' => function() use($id, $name, $value, $label, $rows, $height, $enable_media, $enable_raw_editor) {
                if ($label) {
                    yz()->html->text($label, [
                        'class' => 'text-area-label',
                        'variant' => 'label'
                    ]);
                    yz()->html->element('div', [
                        'style' => 'height: 5px'
                    ]);
                }
//                wp_editor($value, $id, [
//                    'quicktags'     => $enable_raw_editor,
//                    'media_buttons' => $enable_media,
//                    'textarea_rows' => $rows,
//                    'editor_height' => $height,
//                    'textarea_name' => $name,
//                    'editor_class'  => 'yz rich-text-editor-textarea'
//                ]);
                yz()->html->element('div', [
                    'class' => 'rich-text-editor-placeholder',
                    'style' => [
                        'height' => $height . 'px'
                    ],
                    'children' => function() use($value) {
                        echo $value;
                    }
                ]);
                yz()->html->text_area([
                    'id' => $id,
                    'name' => $name,
                    'value' => $value,
                    'hidden' => true
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
        <style data-yz-element="rich-text-editor">
            .ql-toolbar {
                border: 1px solid #8c8f94 !important;
                border-radius: 4px 4px 0 0 !important;
            }

            .yz.rich-text-editor-placeholder {
                background: white;
                border: 1px solid #8c8f94;
                border-radius: 0 0 4px 4px !important;
                min-height: 280px !important;

                div.ql-editor {
                    /*resize: vertical;*/
                }

                p {
                    font-size: 14px !important;
                }
            }

            /*.yz.rich-text-editor .wp-editor-tabs {*/
            /*    margin-right: 8px;*/
            /*}*/

            /*.yz.rich-text-editor .wp-editor-tabs button {*/
            /*    background: #dcdcde;*/
            /*    color: #50575e;*/
            /*    border-color: var(--yz-input-border-color);*/
            /*    border-radius: 4px 4px 0 0;*/
            /*    font-weight: 700;*/
            /*}*/

            /*.yz.rich-text-editor .tmce-active .switch-tmce {*/
            /*    border-bottom-color: transparent;*/
            /*    background: var(--yz-body-background-color);*/
            /*    color: black;*/
            /*}*/

            /*.yz.rich-text-editor .mce-top-part::before {*/
            /*    -webkit-box-shadow: none;*/
            /*    box-shadow: none;*/
            /*}*/

            /*.yz.rich-text-editor .mce-toolbar-grp {*/
            /*    background: var(--yz-body-background-color);*/
            /*    border-radius: 4px 4px 0 0 !important;*/
            /*    border-bottom: 1px solid var(--yz-input-border-color);*/
            /*}*/

            /*.yz.rich-text-editor .wp-editor-container {*/
            /*    border-radius: 4px 4px 4px 4px;*/
            /*    border: 1px solid var(--yz-input-border-color);*/
            /*}*/

            /*.yz.rich-text-editor .mce-panel {*/
            /*    border-radius: inherit;*/
            /*}*/

            /*.yz.rich-text-editor .mce-panel.mce-statusbar {*/
            /*    border-top: 1px solid var(--yz-input-border-color);*/
            /*    border-radius: 0 0 4px 4px;*/
            /*}*/
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
        <script>
            yz('div.rich-text-editor-placeholder').forEach((placeholder) => {
                const textarea = placeholder.next().select('textarea');
                const quill = new Quill(placeholder.item(), {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['link'],
                            [{ 'size': ['small', false, 'large', 'huge'] }],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                            [{ 'align': [] }],
                            ['clean']
                        ]
                    }
                });

                quill.on('text-change', (delta, oldDelta, source) => {
                    textarea.value(quill.getSemanticHTML());
                });

                textarea.spy('change').observe(() => {
                    if (quill.getSemanticHTML() !== textarea.value()) {
                        quill.setText('');
                        quill.clipboard.dangerouslyPasteHTML(0, textarea.value());
                    }
                });
            });
        </script>
    <?php }
}