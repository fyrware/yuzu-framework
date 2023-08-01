<?php

const YUZU_FORM_VALID_METHODS = [
    'get',
    'post',
    'dialog'
];

function yz_form(array $props): void {
    $id       = yz_prop($props, 'id', '');
    $class    = yz_prop($props, 'class', '');
    $method   = yz_prop($props, 'method', 'post');
    $action   = yz_prop($props, 'action', '');
    $children = yz_prop($props, 'children');
    $fields   = yz_prop($props, 'fields', []);

    assert(is_array($fields), 'Fields must be an array');
    assert(in_array($method, YUZU_FORM_VALID_METHODS), 'Invalid form method');

    $classes = [
        'yuzu',
        'form',
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element([
        'class'    => 'form-wrap',
        'children' => function() use($id, $classes, $method, $action, $children, $fields) {
            yz_element('form', [
                'id'         => $id,
                'class'      => yz_join($classes),
                'attributes' => [
                    'method' => $method,
                    'action' => esc_url(admin_url('admin-post.php'))
                ],
                'children' => function() use($action, $children, $fields) {
                    if ($action) {
                        yz_input(['hidden' => true, 'name' => 'action', 'value' => $action]);
                        wp_nonce_field($action, 'nonce');
                    }
                    if ($children) {
                        $children();
                    }
                    foreach ($fields as $field) {
                        $field_for      = yz_prop($field, 'for', '');
                        $field_label    = yz_prop($field, 'label', '');
                        $field_children = yz_prop($field, 'children');

                        yz_element([
                            'class'    => 'form-group',
                            'children' => function() use($field_for, $field_label, $field_children) {
                                yz_element([
                                    'tag'        => 'label',
                                    'attributes' => ['for' => $field_for],
                                    'children'   => function() use($field_label) {
                                        echo $field_label;
                                    }
                                ]);
                                yz_element(['children' => $field_children]);
                            }
                        ]);
                    }
                }
            ]);
        }
    ]);
}

function yz_form_field(array $props): void {
    $id          = yz_prop($props, 'id', '');
    $name        = yz_prop($props, 'name', $id);
    $class       = yz_prop($props, 'class', '');
    $required    = yz_prop($props, 'required', false);
    $description = yz_prop($props, 'description', '');
    $form_entity = yz_prop($props, 'form_entity', 'form');
    $label       = yz_prop($props, 'label', '');
    $type        = yz_prop($props, 'type', 'text');
    $value       = yz_prop($props, 'value', '');

    $classes = [
        'form-field',
    ];

    if ($name) {
        $classes[] = $form_entity . '-' . $name . '-wrap';
    }

    if ($required) {
        $classes[] = 'form-required';
    }

    if ($class) {
        $classes[] = $class;
    }

    yz_flex_layout([
        'as'        => 'div',
        'direction' => 'column',
        'class'     => yz_join($classes),
        'children'  => function() use($id, $name, $required, $form_entity, $description, $label, $type, $value) {
            if ($label) {
                yz_element('label', [
                    'attributes' => [
                        'for' => $id
                    ],
                    'children' => function() use($label) {
                        yz_text($label);
                    }
                ]);
            }
            if ($type === 'color') {
                yz_color_picker([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            } else if ($type === 'icon') {
                yz_icon_picker([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            } else if ($type === 'media') {
                yz_media_picker([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            } else if ($type === 'select') {
                yz_select([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            } else if ($type === 'textarea') {
                yz_textarea([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            } else {
                yz_input([
                    'id'       => $id,
                    'name'     => $name,
                    'required' => $required,
                    'value'    => $value,
                    'aria'     => [
                        'describedby' => $id . '-description'
                    ]
                ]);
            }
            if ($description) {
                yz_paragraph([
                    'id' => $id . '-description',
                    'children' => function() use($description) {
                        yz_text($description);
                    }
                ]);
            }
        }
    ]);
}