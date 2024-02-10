<?php

class Yz_Tab_Group {

    public static function render(array $props): void {
        global $yz;

        $yz->html->element('div', [
            'children' => function() use($yz, $props) {
                $id    = $yz->tools->get_value($props, 'id');
                $class = $yz->tools->get_value($props, 'class');
                $tabs  = $yz->tools->get_value($props, 'tabs', []);

                assert(is_array($tabs), 'Tabs must be an array');

                $current_tab = $tabs[0];

                foreach ($tabs as $tab) {
                    $tab_param = $yz->tools->get_value($_GET, 'tab', '');
                    $tab_label = $yz->tools->get_value($tab, 'label');
                    $tab_slug  = $yz->tools->get_value($tab, 'slug', $yz->tools->format_field_name($tab_label));

                    if ($tab_param === $tab_slug) {
                        $current_tab = $tab;
                        break;
                    }
                }

                $current_tab_label = $yz->tools->get_value($current_tab, 'label');
                $current_tab_slug  = $yz->tools->get_value($current_tab, 'slug', $yz->tools->format_field_name($current_tab_label));
                $current_tab_href  = $yz->tools->get_value($current_tab, 'href', '#' . $current_tab_slug);

                $classes = [
                    'yuzu',
                    'tab-group',
                ];

                if ($class) {
                    $classes[] = $class;
                }

                $yz->html->element('nav', [
                    'id' => $id,
                    'class' => $classes,
                    'children' => function() use($yz, $current_tab_slug, $tabs) {
                        foreach ($tabs as $tab) {
                            $tab_id         = $yz->tools->get_value($tab, 'id');
                            $tab_class      = $yz->tools->get_value($tab, 'class');
                            $tab_label      = $yz->tools->get_value($tab, 'label');
                            $tab_slug       = $yz->tools->get_value($tab, 'slug', $yz->tools->format_field_name($tab_label));
                            $tab_href       = $yz->tools->get_value($tab, 'href', '#' . $tab_slug);
                            $tab_icon       = $yz->tools->get_value($tab, 'icon');
                            $tab_icon_style = $yz->tools->get_value($tab, 'icon_appearance', 'duotone');

                            $tab_classes = [
                                'yuzu',
                                'tab',
                            ];

                            if ($current_tab_slug === $tab_slug) {
                                $tab_classes[] = 'tab-active';
                            }

                            if ($tab_class) {
                                $tab_classes[] = $tab_class;
                            }

                            $yz->html->element('a', [
                                'id'    => $tab_id,
                                'class' => $tab_classes,
                                'attr'  => [
                                    'href' => $tab_href
                                ],
                                'children' => function() use($yz, $tab_icon, $tab_icon_style, $tab_label) {
                                    if ($tab_icon) {
                                        $yz->html->icon($tab_icon, ['appearance' => $tab_icon_style]);
                                    }
                                    $yz->html->text($tab_label);
                                }
                            ]);
                        }
                    }
                ]);

                if (str_starts_with($current_tab_href, '#')) {
                    $yz->html->element('section', [
                        'children' => function () use ($yz, $tabs, $current_tab_slug) {
                            foreach ($tabs as $tab) {
                                $tab_label = $yz->tools->get_value($tab, 'label');
                                $tab_slug  = $yz->tools->get_value($tab, 'slug', $yz->tools->format_field_name($tab_label));

                                $tab_content_classes = [
                                    'yuzu',
                                    'tab-content',
                                ];

                                if ($current_tab_slug === $tab_slug) {
                                    $tab_content_classes[] = 'tab-content-active';
                                }

                                $yz->html->element('section', [
                                    'class' => $tab_content_classes,
                                    'data'  => [
                                        'hash' => '#' . $tab_slug
                                    ],
                                    'children' => function() use($yz, $tab) {
                                        $tab_children = $yz->tools->get_value($tab, 'children');

                                        if (is_callable($tab_children)) {
                                            $tab_children();
                                        }
                                    }
                                ]);
                            }
                        }
                    ]);
                } else {
                    $current_tab_content_classes = [
                        'yuzu',
                        'tab-content',
                        'tab-content-active'
                    ];

                    $yz->html->element('section', [
                        'class' => $current_tab_content_classes,
                        'data'  => [
                            'hash' => '#' . $current_tab_href
                        ],
                        'children' => function() use($yz, $current_tab) {
                            $current_tab_children = $yz->tools->get_value($current_tab, 'children');

                            if (is_callable($current_tab_children)) {
                                $current_tab_children();
                            }
                        }
                    ]);
                }
            }
        ]);
    }

    public static function render_style() { ?>
        <style data-yz-element="tab-group">
            .yuzu.tab-group {
                display:       flex;
                align-items:   end;
                gap:           8px;
                padding:       0 8px;
                margin:        0 0 -1px 0;
            }

            .yuzu.tab {
                display:       flex;
                align-items:   center;
                gap:           4px;
                border-radius: 4px 4px 0 0;
                border: 1px solid #c3c4c7;
                padding: 5px 10px;
                font-size: 14px;
                line-height: 1.71428571;
                font-weight: 600;
                background: #dcdcde;
                color: #50575e;
                text-decoration: none;
                white-space: nowrap;
            }

            .yuzu.tab svg {
                width: 20px;
            }

            .yuzu.tab.tab-active {
                background: #f0f0f1;
                border-bottom: 1px solid #f0f0f1;
                color: #000;
            }

            .yuzu.tab-content {
                border:        1px solid #c3c4c7;
                border-radius: 4px;
                padding:       20px;
            }

            .yuzu.tab-content[data-hash] {
                display: none;
            }

            .yuzu.tab-content.tab-content-active[data-hash] {
                display: block;
            }

            .yuzu.tab-group-divider-vertical {
                border-left: 1px solid #c3c4c7;
                margin:      -20px 0;
                height:      calc(100% + 40px);
            }

            .yuzu.tab-group-divider-horizontal {
                border-top: 1px solid #c3c4c7;
                margin:     0 -20px;
                width:      calc(100% + 40px);
            }
        </style>
    <?php }

    public static function render_script() { ?>
        <script>
            yz.ready().observe(() => {
                function syncTabGroup() {
                    yz('.yuzu.tab-group').forEach((tabGroup) => {
                        const activeTab = yz('.yuzu.tab.tab-active', tabGroup).item();
                        const nextTab   = yz(`.yuzu.tab[href="${window.location.hash}"]`, tabGroup).item();

                        if (nextTab) {

                            if (activeTab) {
                                activeTab.classList.remove('tab-active');
                            }

                            nextTab.classList.add('tab-active');
                        }

                        const activeTabContent = yz('.yuzu.tab-content.tab-content-active').item();
                        const nextTabContent   = yz(`.yuzu.tab-content[data-hash="${window.location.hash}"]`).item();

                        if (nextTabContent) {

                            if (activeTabContent) {
                                activeTabContent.classList.remove('tab-content-active');
                            }

                            nextTabContent.classList.add('tab-content-active');
                        }
                    });
                }

                window.requestAnimationFrame(syncTabGroup);
                window.addEventListener('hashchange', syncTabGroup);
            });
        </script>
    <?php }
}