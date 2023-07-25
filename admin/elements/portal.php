<?php

function yz_portal(string $name, array $props = []): void {
    $class_names = [
        'yuzu',
        'portal'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $id = $props['id'] ?? '';
    $class = trim(implode(' ', $class_names)); ?>

    <div id="<?= $id ?>" class="<?= $class ?>" data-portal="<?= $name ?>">
        <?php do_action('yuzu_portal_' . $name); ?>
    </div>
<?php }

function yz_open_portal(string $name, callable $content): void {
    add_action('yuzu_portal_' . $name, $content);
}
