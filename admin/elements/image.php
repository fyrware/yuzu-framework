<?php

function yz_image(array $props): void {
    $class_names = [
        'yuzu',
        'image'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <img
        class="<?= trim(implode(' ', $class_names)) ?>"
        src="<?= $props['src'] ?>"
        alt="<?= $props['alt'] ?? '' ?>"
    />
<?php }