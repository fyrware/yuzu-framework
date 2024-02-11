<?php

class Yz_Form_Service {

    public function register_action(string $action, callable $callback): null | bool {
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

    public function register_async_form(string $action, callable $callback): null | bool {
        return add_action('wp_ajax_' . $action, function() use($action, $callback) {
            $callback($_POST);
            wp_die();
        });
    }

    public function redirect(?string $location = null): bool {
        if (isset($location) && str_starts_with($location, '#')) {
            return wp_safe_redirect(wp_get_referer() . $location);
        } else if (isset($location)) {
            return wp_safe_redirect($location);
        } else {
            return wp_safe_redirect(wp_get_referer());
        }
    }
}

/** @deprecated */
class Yz_Forms_Service {

    /** @deprecated */
    public static function register_form(string $action, callable $callback): null | bool {
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
    public static function register_async_form(string $action, callable $callback): null | bool {
        return add_action('wp_ajax_' . $action, function() use($action, $callback) {
            $callback($_POST);
            wp_die();
        });
    }

    /** @deprecated */
    public static function redirect(?string $location = null): void {
        wp_safe_redirect($location ?? wp_get_referer());
    }
}