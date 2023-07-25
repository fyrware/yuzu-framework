<?php

function yz_add_menu_separator(float $position): void {
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
            function() use ($page_settings) { ?>
                <section class="wrap">
                    <?php $page_settings['content']($page_settings) ?>
                    <?php yz_portal('default') ?>
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
            function() use ($page_settings) { ?>
                <section class="wrap">
                    <?php $page_settings['content']($page_settings) ?>
                    <?php yz_portal('default') ?>
                </section>
            <?php },
            $page_settings['icon'],
            $page_settings['position']
        );
    }

    return $page;
}
