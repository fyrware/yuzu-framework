<?php

class Yz_Form {

    private const VALID_METHODS = [
        'dialog',
        'get',
        'patch',
        'post',
        'put'
    ];

    /** @deprecated */
    public static function add_handler(string $action, callable $callback): null | bool {
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

    /** @deprecated */
    public static function add_async_handler(string $action, callable $callback): null | bool {
        return add_action('wp_ajax_' . $action, function() use($action, $callback) {
//            $nonce = $_POST['nonce'];
//
//            if (!wp_verify_nonce($nonce, $action)) {
//                wp_die('Invalid nonce specified');
//            }


            $callback($_POST);

            wp_die();

//            if (isset($_POST['redirect'])) {
//                wp_safe_redirect(esc_url($_POST['redirect']));
//            }
        });
    }

    public static function render(array $props = []): void {
        global $yz;

        $action     = $yz->tools->get_value($props, 'action');
        $method     = $yz->tools->get_value($props, 'method', 'post');
        $redirect   = $yz->tools->get_value($props, 'redirect');
        $children   = $yz->tools->get_value($props, 'children');
        $attributes = $yz->tools->get_value($props, 'attr', []);
        $data_set   = $yz->tools->get_value($props, 'data', []);
        $class      = $yz->tools->get_value($props, 'class');

        assert(in_array(strtolower($method), Yz_Form::VALID_METHODS), 'Invalid form method');

        $classes = [
            'yuzu',
            'form'
        ];

        if ($class) {
            $classes[] = $class;
        }

        if ($method) {
            $attributes['method'] = strtolower($method);
        }

        $attributes['action'] = esc_url(admin_url('admin-post.php'));

        $yz->html->element('form', [
            'class'    => $classes,
            'data'     => $data_set,
            'attr'     => $attributes,
            'children' => function() use($yz, $action, $redirect, $children) {
                if ($action) {
                    $yz->html->input([
                        'hidden' => true,
                        'name'   => 'action',
                        'value'  => $action
                    ]);
                    wp_nonce_field($action, 'nonce');
                }
                if (!is_null($redirect)) {
                    $yz->html->input([
                        'hidden' => true,
                        'name'   => 'redirect',
                        'value'  => $redirect
                    ]);
                }
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }
}