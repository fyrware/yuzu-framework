<?php

function yz_text(string $content = '', array $props = []): void {
    $class_names = [
        'yuzu',
        'text',
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $tag = $props['variant'] ?? 'span'; ?>

    <<?= $tag ?> id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?= $content ?>
    </<?= $tag ?>>
<?php }