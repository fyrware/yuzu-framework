<?php

function yz_button_group(array $props): void {
    $class_names = [
        'yuzu',
        'button-group'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <div id="<?= $props['id'] ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?php foreach ($props['buttons'] as $button) { ?>
            <?php yz_button([
                'id' => $button['id'],
                'class_name' => $button['class_name'],
                'label' => $button['label'],
                'type' => $button['type'] ?? $props['button_type'],
                'href' => $button['href'],
                'icon' => $button['icon'],
                'size' => $props['size'],
                'variant' => $props['variant']
            ]) ?>
        <?php } ?>
    </div>
<?php }