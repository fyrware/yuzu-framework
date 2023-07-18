<?php

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
            fn() => $page_settings['content']($page_settings),
            $page_settings['position']
        );
    } else {
        $page = add_menu_page(
            $page_settings['title'],
            $page_settings['title'],
            $page_settings['capability'],
            $page_settings['slug'],
            fn() => $page_settings['content']($page_settings),
            $page_settings['icon'],
            $page_settings['position']
        );
    }

    return $page;
}
