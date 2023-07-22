<?php

function yz_text(string $text, array $props = []): void {
    $class_names = [
        'yuzu',
        'text',
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <span id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?= $text ?>
    </span>
<?php }