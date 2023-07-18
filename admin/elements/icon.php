<?php

function yz_icon(array $props) {
    $appearance = $props['appearance'] ?? 'regular';
    $file_dir = plugin_dir_path(__FILE__) . '../../icons/assets/' . $appearance;
    $file_name = ($appearance === 'regular' ? $props['glyph'] : $props['glyph'] . '-' . $props['appearance']) . '.svg';
    $svg_icon = file_get_contents($file_dir . '/' . $file_name);

    echo $svg_icon;
}


//on"]=> int(4) ["icon"]=> string(338) "
//Warning: file_get_contents(/Applications/MAMP/htdocs/fyrware/wp-content/plugins/yuzu-framework/admin/elements/icons/assets/regular/certificate.svg): Failed to open stream: No such file or directory in /Applications/MAMP/htdocs/fyrware/wp-content/plugins/yuzu-framework/admin/elements/icon.php on line 7
//" ["title"]=> string(17) "Clubhouse Courses" ["slug"]=> string(17) "clubhouse-courses" ["capability"]=> string(14) "manage_options" ["content"]=> object(Closure)#2861 (0) { } }