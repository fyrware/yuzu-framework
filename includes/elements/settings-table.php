<?php

class Yz_Settings_Table {

    public static function render(array $props): void {
        global $yz;

        $id          = $yz->tools->key_or_default($props, 'id');
        $class       = $yz->tools->key_or_default($props, 'class');
        $settings    = $yz->tools->key_or_default($props, 'settings', []);
        $title       = $yz->tools->key_or_default($props, 'title');
        $description = $yz->tools->key_or_default($props, 'description');

        $classes = [
            'yz',
            'form-table',
            'settings-table'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->flex_layout([
            'direction' => 'column',
            'class'     => 'settings-table-container',
            'children'  => function() use($yz, $id, $classes, $settings, $title, $description) {

                if ($title) {
                    $yz->html->title($title, [ 'level' => 3 ]);
                }

                if ($description) {
                    $yz->html->text($description, [ 'variant' => 'em' ]);
                }

                $yz->html->element('table', [
                    'id'       => $id,
                    'class'    => $classes,
                    'children' => function() use($yz, $settings) {
                        foreach ($settings as $option => $field) {
                            $yz->html->element('tr', [
                                'children' => function() use($yz, $option, $field) {
                                    $yz->html->element('th', [
                                        'attr' => [ 'scope' => 'row' ],
                                        'children' => function() use($yz, $option, $field) {
                                            $yz->html->text($option, [
                                                'variant' => 'label',
                                                'attr' => [ 'for' => $field['id'] ]
                                            ]);
                                        }
                                    ]);
                                    $yz->html->element('td', [
                                        'children' => function() use($yz, $field) {
                                            $id          = $yz->tools->key_or_default($field, 'id');
                                            $name        = $yz->tools->key_or_default($field, 'name', $id);
                                            $type        = $yz->tools->key_or_default($field, 'type', 'text');
                                            $label       = $yz->tools->key_or_default($field, 'label');
                                            $value       = $yz->tools->key_or_default($field, 'value');
                                            $checked     = $yz->tools->key_or_default($field, 'checked', false);
                                            $placeholder = $yz->tools->key_or_default($field, 'placeholder');
                                            $description = $yz->tools->key_or_default($field, 'description');

                                            $yz->html->input([
                                                'id'    => $id,
                                                'name'  => $name,
                                                'type'  => $type,
                                                'label' => $label,
                                                'value' => $value,
                                                'checked' => $checked,
                                                'placeholder' => $placeholder,
                                                'width' => 320
                                            ]);

                                            if ($description) {
                                                $yz->html->element('p', [
                                                    'class' => 'description',
                                                    'children' => function() use($yz, $description) {
                                                        $yz->html->text($description);
                                                    }
                                                ]);
                                            }
                                        }
                                    ]);
                                }
                            ]);
                        }
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="settings-table">
            .yz.settings-table-container .yz.title {
                font-size: 19px;
                margin-bottom: 0;
            }

            .yz.settings-table-container em {
                font-size: 14px;
                margin: 10px 0;
            }

            .yz.settings-table {
                margin-top: 0;
            }
        </style>
    <?php }
}