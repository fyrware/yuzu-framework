<?php function yz_button(array $props): void {
    $class_names = [
        'yuzu',
        'button'
    ];

    if (isset($props['size'])) {
        $class_names[] = 'button-' . $props['size'];
    }

    if (isset($props['variant'])) {
        $class_names[] = 'button-' . $props['variant'];
    }

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    switch ($props['type'] ?? 'button') {
        case 'link': ?>
            <a id="<?= $props['id'] ?? '' ?>" href="<?= $props['href'] ?>" class="<?= trim(implode(' ', $class_names)) ?>">
                <? if (isset($props['icon'])) echo $props['icon']; ?>
                <?= $props['label'] ?>
            </a>
        <?php break;
        default: ?>
            <button id="<?= $props['id'] ?? '' ?>" type="<?= $props['type'] ?? 'button' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
                <? if (isset($props['icon'])) echo $props['icon']; ?>
                <?= $props['label'] ?>
            </button>
        <?php break;
    }
}