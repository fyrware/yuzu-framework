<?php

class Yz_Tab_Group {

    public static function render(array $props): void {
        global $yz;

        $yz->html->element('div', [
            'style' => [
                'display' => 'flex',
                'flex_direction' => 'column',
                'flex_grow' => 1
            ],
            'children' => function() use($yz, $props) {
                $id     = $yz->tools->get_value($props, 'id');
                $class  = $yz->tools->get_value($props, 'class');
                $tabs   = $yz->tools->get_value($props, 'tabs', []);
                $nolink = $yz->tools->get_value($props, 'nolink', false);
                $nopad  = $yz->tools->get_value($props, 'nopad', false);

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

                if ($nolink) {
                    $classes[] = 'nolink';
                }

                if ($nopad) {
                    $classes[] = 'nopad';
                }

                $yz->html->element('nav', [
                    'id' => $id,
                    'class' => $classes,
                    'children' => function() use($yz, $current_tab_slug, $tabs, $nolink) {
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
                                'attr'  => $nolink ? [
                                    'data-hash' => $tab_href
                                ] : [
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
                        'style' => [
                            'display' => 'flex',
                            'flex_grow' => 1
                        ],
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
                                    'style' => [
                                        'flex_grow' => 1
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
                position:      relative;

                &.nopad + .yz.tab-content,
                &.nopad + section > .yz.tab-content {
                    padding: 0;
                }
            }

            .yuzu.tab {
                cursor:        pointer;
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
                display: flex;
                flex-direction: column;
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
                        const activeTab = yz('.yuzu.tab.tab-active', tabGroup);
                        const nextTab   = yz(`.yuzu.tab[href="${window.location.hash}"]`, tabGroup) ?? yz('.yuzu.tab', tabGroup).first();

                        if (nextTab.exists()) {
                            if (activeTab.exists()) {
                                activeTab.classes().remove('tab-active');
                            }
                            nextTab.classes().add('tab-active');
                        }

                        const activeTabContent = yz('.yuzu.tab-content.tab-content-active');
                        const nextTabContent   = yz(`.yuzu.tab-content[data-hash="${window.location.hash}"]`);

                        if (nextTabContent.exists()) {
                            if (activeTabContent.exists()) {
                                activeTabContent.classes().remove('tab-content-active');
                            }
                            nextTabContent.classes().add('tab-content-active');
                        }
                    });
                }

                window.requestAnimationFrame(syncTabGroup);
                window.addEventListener('hashchange', syncTabGroup);

                yz('.yz.tab-group').forEach((tabGroup) => {
                    if (tabGroup.classes().contains('nolink')) {
                        yz('.yz.tab', tabGroup).forEach((tab, i) => {
                            tab.spy('click').observe(() => {
                                const activeTab = yz('.yz.tab.tab-active', tabGroup);

                                if (activeTab.exists()) {
                                    activeTab.classes().remove('tab-active');
                                }

                                tab.classes().add('tab-active');

                                const activeTabContent = yz('.yz.tab-content.tab-content-active', tabGroup.parent());
                                const nextTabContent   = yz(`.yz.tab-content[data-hash="${tab.attr('data-hash')}"]`, tabGroup.parent());

                                if (nextTabContent.exists()) {
                                    if (activeTabContent.exists()) {
                                        activeTabContent.classes().remove('tab-content-active');
                                    }
                                    nextTabContent.classes().add('tab-content-active');
                                }
                            });
                        });
                    }
                });
            });
        </script>
    <?php }
}