<?php

class Yz_Title {

        public static function render(string $text, array $props = []): void {
            global $yz;

            $id         = $yz->tools->get_value($props, 'id');
            $class      = $yz->tools->get_value($props, 'class');
            $level      = $yz->tools->get_value($props, 'level', 1);
            $attributes = $yz->tools->get_value($props, 'attr', []);
            $data_set   = $yz->tools->get_value($props, 'data', []);
            $icon       = $yz->tools->get_value($props, 'icon');

            $classes = [
                'yz',
                'yuzu',
                'title'
            ];

            if ($class) {
                $classes[] = $class;
            }

            $yz->html->element('h' . $level, [
                'id'       => $id,
                'class'    => $classes,
                'attr'     => $attributes,
                'data'     => $data_set,
                'children' => function() use($yz, $text, $icon) {
                    if ($icon) $yz->html->icon($icon, [ 'appearance' => 'duotone', 'size' => '1.5em' ]);
                    echo $text;
                }
            ]);
        }

        public static function render_style(): void { ?>
            <style data-yz-element="title">
                .yuzu.title {
                    display: flex;
                    align-items: center;
                    padding: 9px 0 0;
                }

                .yuzu.title svg {
                    margin-right: 0.25em;
                }
            </style>
        <?php }
}