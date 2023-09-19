<?php

class Yz_Empty_State {

    public static function render(array $props): void {
        $id = Yz_Array::value_or($props, 'id');
        $class = Yz_Array::value_or($props, 'class');
        $title = Yz_Array::value_or($props, 'title');
        $description = Yz_Array::value_or($props, 'description');
        $icon = Yz_Array::value_or($props, 'icon');
        $actions = Yz_Array::value_or($props, 'actions', []);

        $classes = [
            'yuzu',
            'empty-state'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Flex_Layout([
            'id' => $id,
            'class' => implode(' ', $classes),
            'gap' => 20,
            'direction' => 'column',
            'alignment' => 'center',
            'justification' => 'center',
            'items' => [
                ['class' => 'empty-state-icon', 'children' => function() use ($icon) {
                    echo $icon;
                }],
                ['children' => function() use ($title, $description, $actions) {
                    Yz::Flex_Layout([
                        'class' => 'empty-state-content',
                        'direction' => 'column',
                        'alignment' => 'center',
                        'justification' => 'center',
                        'gap' => 20,
                        'items' => [
                            ['children' => function() use ($title) {
                                Yz::Title($title, [
                                    'class' => 'empty-state-title',
                                    'level' => 2,
                                ]);
                            }],
                            ['children' => function() use ($description) {
                                Yz::Element('p', [
                                    'class' => 'empty-state-description',
                                    'children' => function() use($description) {
                                        Yz::Text($description);
                                    }
                                ]);
                            }],
                            ['children' => function() use ($actions) {
                                Yz::Flex_Layout([
                                    'class' => 'empty-state-actions',
                                    'direction' => 'row',
                                    'alignment' => 'center',
                                    'justification' => 'center',
                                    'gap' => 20,
                                    'items' => array_map(function($action) {
                                        return [
                                            'children' => function() use ($action) {
                                                Yz::Button($action);
                                            }
                                        ];
                                    }, $actions)
                                ]);
                            }]
                        ]
                    ]);
                }]
            ]
        ]);
    }

    public static function render_style(): void { ?>
        <style>
            .yuzu.empty-state {
                padding: 80px 0;
            }
            .yuzu.empty-state .yuzu.empty-state-icon svg {
                width:   112px;
                height:  112px;
                opacity: 0.5;
            }
            .yuzu.empty-state h2,
            .yuzu.empty-state p {
                opacity: 0.5;
            }
            .yuzu.empty-state .yuzu.empty-state-title {
                margin:    0;
                font-size: 1.75em;
            }
        </style>
    <?php }
}
