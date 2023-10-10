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
            'empty-state'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Flex_Layout([
            'id' => $id,
            'class' => Yz_Array::join($classes),
            'gap' => 20,
            'direction' => 'column',
            'alignment' => 'center',
            'justification' => 'center',
            'children' => function() use($icon, $title, $description, $actions) {
                if ($icon) Yz::Icon($icon, [ 'appearance' => 'duotone' ]);
                Yz::Flex_Layout([
                    'class' => 'empty-state-content',
                    'direction' => 'column',
                    'alignment' => 'center',
                    'justification' => 'center',
                    'gap' => 10,
                    'children' => function() use($title, $description, $actions) {
                        Yz::Title($title, [
                            'class' => 'empty-state-title',
                            'level' => 2,
                        ]);
                        Yz::Element('p', [
                            'class' => 'empty-state-description',
                            'children' => function() use($description) {
                                Yz::Text($description);
                            }
                        ]);
                        Yz::Flex_Layout([
                            'class' => 'empty-state-actions',
                            'direction' => 'row',
                            'alignment' => 'center',
                            'justification' => 'center',
                            'gap' => 20,
                            'children' => function() use($actions) {
                                foreach ($actions as $action) {
                                    Yz::Button($action);
                                }
                            }
                        ]);
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style>
            .yuzu.empty-state {
                padding: 80px 0;
            }
            .yuzu.empty-state > svg {
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
