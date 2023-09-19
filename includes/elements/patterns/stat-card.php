<?php

class Yz_Stat_Card {

    public static function render(array $props): void {
        $id          = Yz_Array::value_or($props, 'id');
        $class       = Yz_Array::value_or($props, 'class');
        $icon        = Yz_Array::value_or($props, 'icon', 'question');
        $value       = Yz_Array::value_or($props, 'value', '0');
        $description = Yz_Array::value_or($props, 'description', '');
        $children    = Yz_Array::value_or($props, 'children');

        $classes = [
            'stat-card'
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Card([
            'id' => $id,
            'class' => Yz_Array::join($classes),
            'alignment' => 'center',
            'children' => function() use($icon, $value, $description, $children) {
                Yz::Flex_Layout([
                    'alignment' => 'center',
                    'gap' => 10,
                    'children' => function() use($icon, $value) {
                        Yz::Icon($icon, [
                            'appearance' => 'duotone',
                            'class' => 'stat-card-icon'
                        ]);
                        Yz::Text($value, [ 'class' => 'stat-card-value' ]);
                    }
                ]);

                Yz::Text($description);

                if (is_callable($children)) {
                    Yz::Element('br');
                    $children();
                }
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style>
            .yuzu.stat-card {
                align-items: center;
            }

            .yuzu.stat-card .stat-card-icon {
                width: 40px;
                height: 40px;
            }

            .yuzu.stat-card .stat-card-value {
                font-size: 24px;
                font-weight: 800;
            }
        </style>
    <?php }
}