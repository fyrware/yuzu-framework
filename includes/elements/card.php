<?php

class Yz_Card {

    public static function render(array $props = []): void {
        global $yz;

        $id         = $yz->tools->key_or_default($props, 'id');
        $class      = $yz->tools->key_or_default($props, 'class');
        $alignment  = $yz->tools->key_or_default($props, 'alignment', 'start');
        $children   = $yz->tools->key_or_default($props, 'children');
        $padding    = $yz->tools->key_or_default($props, 'padding');

        $classes = [
            'yuzu',
            'card'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $style = [];

        if ($alignment) {
            $style[ 'justify_content' ] = $alignment;
        }

        if ($padding) {
            $style[ 'padding' ] = is_string($padding) ? $padding : $padding . 'px';
        }

        $yz->html->element('section', [
            'id'       => $id,
            'class'    => $classes,
            'style'    => $style,
            'children' => function() use($children) {
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="card">
            .yuzu.card {
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
                margin: 0;
                padding: 20px;
                min-width: 0;
                max-width: 100%;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                background: #fff;
                color: #50575e;
                font-size: 13px;
                position: relative;
            }

            .yuzu.card > *:first-child {
                margin-top: 0;
                padding-top: 0;
            }
        </style>
    <?php }
}
