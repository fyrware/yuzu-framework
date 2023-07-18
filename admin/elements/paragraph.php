<?php

function yz_paragraph(array $props): void {
    $class_names = [
        'yuzu',
        'paragraph'
    ];

    if (isset($props['variant'])) {
        $class_names[] = $props['variant'];
    }

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <p id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?= $props['content']() ?>
    </p>
<?php }