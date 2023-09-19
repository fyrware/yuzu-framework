<?php

class Yz_Title {

        public static function render(string $text, array $props = []): void {
            $id         = Yz_Array::value_or($props, 'id');
            $class      = Yz_Array::value_or($props, 'class');
            $level      = Yz_Array::value_or($props, 'level', 1);
            $attributes = Yz_Array::value_or($props, 'attr', []);
            $data_set   = Yz_Array::value_or($props, 'data', []);
            $icon       = Yz_Array::value_or($props, 'icon');

            $classes = [
                'yuzu',
                'title'
            ];

            if ($class) {
                $classes[] = $class;
            }

            Yz::Element('h' . $level, [
                'id'       => $id,
                'class'    => Yz_Array::join($classes),
                'attr'     => $attributes,
                'data'     => $data_set,
                'children' => function() use($text, $icon) {
                    if ($icon) Yz::Icon($icon, [ 'appearance' => 'duotone' ]);
                    echo $text;
                }
            ]);
        }

        public static function render_style(): void { ?>
            <style>
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