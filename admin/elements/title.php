<?php

function yz_title(array $props): void {
    $class_names = [
        'yuzu',
        'title',
    ];

    if (isset($props['inline']) && $props['inline']) {
        $class_names[] = 'wp-heading-inline';
    } else {
        $class_names[] = 'wp-heading';
    }

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <h1 id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?= $props['content']() ?>
    </h1>
<?php }