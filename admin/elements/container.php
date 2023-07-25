<?php

function yz_container(array $props): void {
    $class_names = [
        'yuzu',
        'container'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $tag = $props['variant'] ?? 'div';
    $id = $props['id'] ?? '';
    $class = trim(implode(' ', $class_names)); ?>

    <<?= $tag ?> id="<?= $id ?>" class="<?= $class ?>">
        <?php if (isset($props['content'])) { ?>
            <?= $props['content']() ?>
        <?php } ?>
    </<?= $tag ?>>
<?php }