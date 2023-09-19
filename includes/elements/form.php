<?php

class Yz_Form {

    private const VALID_METHODS = [
        'dialog',
        'get',
        'patch',
        'post',
        'put'
    ];

    public static function add_handler(string $action, callable $callback): null|bool {
        return add_action('admin_post_' . $action, function() use($action, $callback) {
            $nonce = $_POST['nonce'];

            if (!wp_verify_nonce($nonce, $action)) {
                wp_die('Invalid nonce specified');
            }

            $callback($_POST);

            if (isset($_POST['redirect'])) {
                wp_safe_redirect(esc_url($_POST['redirect']));
            }
        });
    }

    public static function render(array $props = []): void {
        $action     = Yz_Array::value_or($props, 'action');
        $method     = Yz_Array::value_or($props, 'method', 'post');
        $children   = Yz_Array::value_or($props, 'children');
        $attributes = Yz_Array::value_or($props, 'attr', []);
        $data_set   = Yz_Array::value_or($props, 'data', []);

        assert(in_array($method, Yz_Form::VALID_METHODS), 'Invalid form method');

        $classes = [
            'yuzu',
            'form'
        ];

        if ($action) {
            $attributes['action'] = $action;
        }

        if ($method) {
            $attributes['method'] = $method;
        }

        Yz::Element('form', [
            'class'    => Yz_Array::join($classes),
            'data'     => $data_set,
            'attr'     => $attributes,
            'children' => function() use($children) {
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }
}