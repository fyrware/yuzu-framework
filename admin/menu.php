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
 * @param string $page_title
 * @param string $menu_title
 * @param string $capability
 * @param string $menu_slug
 * @param callable|null $function
 * @param string $icon_url
 * @param int|null $position
 * @return string
 */
function yuzu_add_admin_menu_page(
    string $page_title,
    string $menu_title,
    string $capability,
    string $menu_slug,
    callable $function = null,
    string $icon_url = '',
    float $position = null
): string {
    return add_menu_page(
        $page_title,
        $menu_title,
        $capability,
        $menu_slug,
        $function,
        str_starts_with($icon_url, '<svg') ? yuzu_admin_encode_icon($icon_url) : $icon_url,
        $position
    );
}

/**
 * Encode and SVG icon as a base64 data-url
 * @param string $icon_svg
 * @return string
 */
function yuzu_admin_encode_icon(string $icon_svg): string {
    return 'data:image/svg+xml;base64,' . base64_encode($icon_svg);
}
