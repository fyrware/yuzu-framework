<?php

function yz_add_menu_separator(float $position): void {
    global $menu;

    $separator = [
        0 => '',
        1 => 'read',
        2 => 'separator' . $position,
        3 => '',
        4 => 'wp-menu-separator'
    ];

    if (isset($menu[$position])) {
        $menu = array_splice($menu, $position, 0, $separator);
    } else {
        $menu[$position] = $separator;
    }
}

function yz_add_page(array $page_settings): string {
    $page = '';

    if (isset($page_settings['icon']) && str_starts_with($page_settings['icon'], '<svg')) {
        $page_settings['icon'] = yz_encode_svg($page_settings['icon']);
    }

    if (isset($page_settings['parent'])) {
        $page = add_submenu_page(
            $page_settings['parent'],
            $page_settings['title'],
            $page_settings['title'],
            $page_settings['capability'],
            $page_settings['slug'],
            function() use($page_settings) { ?>
                <section class="wrap">
                    <?php $page_settings['children']($page_settings) ?>
                    <?php yz_portal() ?>
                </section>
            <?php },
            $page_settings['position'] ?? null
        );
    } else {
        $page = add_menu_page(
            $page_settings['title'],
            $page_settings['title'],
            $page_settings['capability'],
            $page_settings['slug'],
            function() use($page_settings) { ?>
                <section class="wrap">
                    <?php $page_settings['children']($page_settings) ?>
                    <?php yz_portal() ?>
                </section>
            <?php },
            $page_settings['icon'],
            $page_settings['position']
        );
    }

    return $page;
}
