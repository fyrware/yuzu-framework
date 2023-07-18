<?php

function yz_intercept_form(string $action, callable $callback): null|bool {
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