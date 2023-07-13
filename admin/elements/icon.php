<?php

function yz_icon(array $props) {
    $file_dir = plugin_dir_path(__FILE__) . 'icons/assets/' . $props['appearance'];
    $file_name = ($props['appearance'] === 'regular' ? $props['glyph'] : $props['glyph'] . '-' . $props['appearance']) . '.svg';
    $svg_icon = file_get_contents($file_dir . '/' . $file_name);

    echo $svg_icon;
}