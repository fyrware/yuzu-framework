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
        'form'
    ];

    if ($class) {
        $classes[] = $class;
    }

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