<?php

class Yz_Title {

        public static function render(string $text, array $props = []): void {
            global $yz;

            $id         = $yz->tools->key_or_default($props, 'id');
            $class      = $yz->tools->key_or_default($props, 'class');
            $level      = $yz->tools->key_or_default($props, 'level', 1);
            $attributes = $yz->tools->key_or_default($props, 'attr', []);
            $data_set   = $yz->tools->key_or_default($props, 'data', []);
            $icon       = $yz->tools->key_or_default($props, 'icon');

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
                    if ($icon) $yz->html->icon($icon, [ 'appearance' => 'duotone' ]);
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
                    width: 1.5em;
                    height: 1.5em;
                    margin-right: 0.25em;
                }
            </style>
        <?php }
}