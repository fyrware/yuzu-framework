<?php

/**
 * Add admin menu separator
 * (**NOTE**: This function should be used within the `'admin_menu'` action hook)
 * @param int $position
 * @return void
 */
function yuzu_add_admin_menu_separator(float $position): void {
    global $menu;

    $index = 0;
    foreach($menu as $offset => $section) {

        if (str_starts_with($section[2], 'separator')) {
            $index++;
        }

        if ($offset >= $position) {
            $menu[$position] = [
                '',
                'read',
                'separator' . $index,
                '',
                'wp-menu-separator'
            ];
            break;
        }
    }

    ksort($menu);
}

/**
 * Add admin menu page with automatic base64 encoding of SVG icons
 * (**NOTE**: This function should be used within the `'admin_menu'` action hook)
 * @param array $admin_page_options
 * @return string
 */
function yuzu_add_admin_menu_page(array $admin_page_options): string {
    $admin_page = new Yuzu_Admin_Page($admin_page_options);

    if ($admin_page->get_option('add_separator')) {
        yuzu_add_admin_menu_separator($admin_page->get_option('position'));
    }

    $page_ref = add_menu_page(
        $admin_page->get_option('title'),
        $admin_page->get_option('menu_title'),
        $admin_page->get_option('capability'),
        $admin_page->get_option('menu_slug'),
        $admin_page->get_option('content'),
        yuzu_encode_admin_menu_icon($admin_page->get_option('icon')),
        $admin_page->get_option('position')
    );

    add_action('load-' . $page_ref, function() use ($admin_page) {

    });

    return $page_ref;
}

/**
 * Encode and SVG icon as a base64 data-url
 * @param string $icon_svg
 * @return string
 */
function yuzu_encode_admin_menu_icon(string $icon_svg): string {
    return 'data:image/svg+xml;base64,' . base64_encode($icon_svg);
}
