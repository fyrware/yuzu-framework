<?php

function yz_get_current_tab_page(): string {
    return $_GET['page'];
}

function yz_get_current_tab(array $tabs): array {
    $current_tab_slug = $_GET['tab'] ?? $tabs[0]['slug'];

    foreach ($tabs as $tab) {
        if ($tab['slug'] === $current_tab_slug) {
            return $tab;
        }
    }

    return $tabs[0];
}

function yz_tab_group(array $props): void {
    $id           = yz_prop($props, 'id', '');
    $class        = yz_prop($props, 'class', '');
    $tabs         = yz_prop($props, 'tabs', []);
    $current_tab  = yz_get_current_tab($tabs);
    $current_page = yz_get_current_tab_page();

    assert(is_array($tabs), 'Tabs must be an array');

    $classes = [
        'yuzu',
        'tab-group',
        'nav-tab-wrapper'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element('nav', [
        'id'       => $id,
        'class'    => yz_join($classes),
        'children' => function() use ($tabs, $current_tab, $current_page) {
            foreach ($tabs as $tab) {
                $tab_id    = yz_prop($tab, 'id', '');
                $tab_slug  = yz_prop($tab, 'slug', '');
                $tab_class = yz_prop($tab, 'class', '');
                $tab_icon  = yz_prop($tab, 'icon', '');
                $tab_label = yz_prop($tab, 'label', '');
                $tab_href  = yz_prop($tab, 'href', '?page=' . $current_page . '&tab=' . $tab_slug);

                $tab_classes = [
                    'yuzu',
                    'tab',
                    'nav-tab'
                ];

                if ($current_tab['slug'] === $tab_slug) {
                    $tab_classes[] = 'nav-tab-active';
                }

                if ($tab_class) {
                    $tab_classes[] = $tab_class;
                }

                yz_element('a', [
                    'id'         => $tab_id,
                    'class'      => yz_join($tab_classes),
                    'attributes' => ['href' => $tab_href],
                    'children'   => function() use ($tab_icon, $tab_label) {
                        echo $tab_icon;
                        echo $tab_label;
                    }
                ]);
            }
        }
    ]);
    yz_element('main', [
        'class'    => 'yuzu tab-content nav-tab-content',
        'children' => $current_tab['children']
    ]);
}

function yz_tab_group_divider(array $props = []): void {
    $id         = yz_prop($props, 'id', '');
    $class_name = yz_prop($props, 'class', '');
    $variant    = yz_prop($props, 'variant', 'vertical');

    $class_names = [
        'yuzu',
        'tab-group-divider'
    ];

    if ($variant) {
        $class_names[] = 'tab-group-divider-' . $variant;
    }

    if ($class_name) {
        $class_names[] = $class_name;
    }

    yz_element([
        'id'         => $id,
        'class' => trim(implode(' ', $class_names)),
    ]);
}