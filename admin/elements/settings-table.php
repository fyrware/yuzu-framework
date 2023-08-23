<?php

function yz_settings_table(array $props): void {
    $id     = yz_prop($props, 'id', '');
    $class  = yz_prop($props, 'class', '');
    $style  = yz_prop($props, 'style', '');
    $fields = yz_prop($props, 'fields', []);

    $classes = [
        'yuzu',
        'settings-table',
        'form-table'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'tag'      => 'table',
        'id'       => $id,
        'class'    => trim(implode(' ', $classes)),
        'style'    => $style,
        'children' => function() use($fields) {
            foreach ($fields as $field) {
                $field_label   = yz_prop($field, 'label', '');
                $field_content = yz_prop($field, 'children');
                $field_for     = yz_prop($field, 'for', '');

                yz_element('tr', [
                    'children' => function() use($field_label, $field_content, $field_for) {
                        yz_element('th', [
                            'attributes' => ['scope' => 'row'],
                            'children'    => function() use($field_label, $field_for) {
                                yz_element('label', [
                                    'attributes' => [
                                        'for' => $field_for
                                    ],
                                    'children' => function() use($field_label) {
                                        yz_text($field_label, ['variant'    => 'strong']);
                                    }
                                ]);
                            }
                        ]);
                        yz_element('td', [
                            'children' => $field_content
                        ]);
                    }
                ]);
            }
        }
    ]);
}