<?php

class Yz_Stat_Card {

    public static function render(array $props): void {
        global $yz;

        $id          = $yz->tools->get_value($props, 'id');
        $class       = $yz->tools->get_value($props, 'class');
        $icon        = $yz->tools->get_value($props, 'icon', 'question');
        $value       = $yz->tools->get_value($props, 'value', '0');
        $description = $yz->tools->get_value($props, 'description', '');
        $children    = $yz->tools->get_value($props, 'children');

        $classes = [
            'stat-card'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->card([
            'id' => $id,
            'class' => $classes,
            'alignment' => 'center',
            'children' => function() use($yz, $icon, $value, $description, $children) {
                $yz->html->flex_layout([
                    'alignment' => 'center',
                    'gap' => 10,
                    'children' => function() use($yz, $icon, $value) {
                        $yz->html->icon($icon, [
                            'appearance' => 'duotone',
                            'class' => 'stat-card-icon'
                        ]);
                        $yz->html->text($value, [ 'class' => 'stat-card-value' ]);
                    }
                ]);

                $yz->html->text($description);

                if (is_callable($children)) {
                    $yz->html->element('br');
                    $children();
                }
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="stat-card">
            .yz.stat-card {
                align-items: center;
            }

            .yz.stat-card .stat-card-icon {
                width: 40px;
                height: 40px;
            }

            .yz.stat-card .stat-card-value {
                font-size: 24px;
                font-weight: 800;
            }
        </style>
    <?php }
}