<?php

class Yz_Tab_Group {

    public static function render(array $props): void {
        Yz::Element('div', [
            'children' => function() use($props) {
                $id           = Yz_Array::value_or($props, 'id');
                $class        = Yz_Array::value_or($props, 'class');
                $tabs         = Yz_Array::value_or($props, 'tabs', []);

                assert(is_array($tabs), 'Tabs must be an array');

                $current_tab = $tabs[0];

                foreach ($tabs as $tab) {
                    $tab_param = Yz_Array::value_or($_GET, 'tab', '');
                    $tab_label = Yz_Array::value_or($tab, 'label');
                    $tab_slug  = Yz_Array::value_or($tab, 'slug', Yz_String::format_field_name($tab_label));

                    if ($tab_param === $tab_slug) {
                        $current_tab = $tab;
                        break;
                    }
                }

                $current_tab_label = Yz_Array::value_or($current_tab, 'label');
                $current_tab_slug  = Yz_Array::value_or($current_tab, 'slug', Yz_String::format_field_name($current_tab_label));
                $current_tab_href  = Yz_Array::value_or($current_tab, 'href', '#' . $current_tab_slug);

                $classes = [
                    'yuzu',
                    'tab-group',
                ];

                if ($class) {
                    $classes[] = $class;
                }

                Yz::Element('nav', [
                    'id'       => $id,
                    'class'    => Yz_Array::join($classes),
                    'children' => function() use($current_tab_slug, $tabs) {
                        foreach ($tabs as $tab) {
                            $tab_id         = Yz_Array::value_or($tab, 'id');
                            $tab_class      = Yz_Array::value_or($tab, 'class');
                            $tab_label      = Yz_Array::value_or($tab, 'label');
                            $tab_slug       = Yz_Array::value_or($tab, 'slug', Yz_String::format_field_name($tab_label));
                            $tab_href       = Yz_Array::value_or($tab, 'href', '#' . $tab_slug);
                            $tab_icon       = Yz_Array::value_or($tab, 'icon');
                            $tab_icon_style = Yz_Array::value_or($tab, 'icon_appearance', 'duotone');

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

                            Yz::Element('a', [
                                'id'    => $tab_id,
                                'class' => Yz_Array::join($tab_classes),
                                'attr'  => [
                                    'href' => $tab_href
                                ],
                                'children' => function() use($tab_icon, $tab_icon_style, $tab_label) {
                                    if ($tab_icon) {
                                        Yz::Icon($tab_icon, ['appearance' => $tab_icon_style]);
                                    }
                                    Yz::Text($tab_label);
                                }
                            ]);
                        }
                    }
                ]);

                if (str_starts_with($current_tab_href, '#')) {
                    Yz::Element('section', [
                        'children' => function() use($tabs, $current_tab_slug) {
                            foreach ($tabs as $tab) {
                                $tab_label = Yz_Array::value_or($tab, 'label');
                                $tab_slug  = Yz_Array::value_or($tab, 'slug', Yz_String::format_field_name($tab_label));

                                $tab_content_classes = [
                                    'yuzu',
                                    'tab-content',
                                ];

                                if ($current_tab_slug === $tab_slug) {
                                    $tab_content_classes[] = 'tab-content-active';
                                }

                                Yz::Element('section', [
                                    'class' => Yz_Array::join($tab_content_classes),
                                    'data'  => [
                                        'hash' => '#' . $tab_slug
                                    ],
                                    'children' => function() use($tab) {
                                        $tab_children = Yz_Array::value_or($tab, 'children');

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

                    Yz::Element('section', [
                        'class' => Yz_Array::join($current_tab_content_classes),
                        'data'  => [
                            'hash' => '#' . $current_tab_href
                        ],
                        'children' => function() use($current_tab) {
                            $current_tab_children = Yz_Array::value_or($current_tab, 'children');

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
        <style>
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
            yz.ready().then(() => {
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