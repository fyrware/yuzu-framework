<?php

function yz_notice(array $props): void {
    $class_names = [
        'yuzu',
        'notice'
    ];

    if (isset($props['alt']) && $props['alt']) {
        $class_names[] = 'notice-alt';
    }

    if (isset($props['dismissible']) && $props['dismissible']) {
        $class_names[] = 'is-dismissible';
    }

    if (isset($props['variant'])) {
        $class_names[] = 'notice-' . $props['variant'];
    }

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <div id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?php if (isset($props['icon'])) echo $props['icon']; ?>
        <?php if (isset($props['title'])) { ?>
            <strong><?= $props['title'] ?>:</strong>
        <?php } ?>
        <?= $props['content']() ?>
    </div>
<?php }
