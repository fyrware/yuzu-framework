<?php

function yz_stat_card(array $props): void {
    $id         = yz_prop($props, 'id', '');
    $class      = yz_prop($props, 'class', '');
    $stat       = yz_prop($props, 'stat', 'Unknown');
    $stat_value = yz_prop($props, 'stat_value', '0');
    $icon_glyph = yz_prop($props, 'icon_glyph', 'question');

    $classes = [
        'stat-card'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_card([
        'id'       => $id,
        'class'    => yz_join($classes),
        'children' => function () use($stat, $stat_value, $icon_glyph) {
            yz_flex_layout(['alignment' => 'center', 'gap' => 10, 'items' => [
                ['children' => function() use($icon_glyph) {
                    yz_icon([
                        'class'      => 'stat-card-icon',
                        'glyph'      => $icon_glyph,
                        'appearance' => 'duotone'
                    ]);
                }],
                ['children' => function() use($stat_value) {
                    yz_text($stat_value, ['class' => 'stat-card-value']);
                }]
            ]]);
            yz_text($stat);
        }
    ]);
}