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

    switch ($props['type'] ?? null) {
        case 'link': ?>
            <a id="<?= $props['id'] ?? '' ?>" href="<?= $props['href'] ?>" class="<?= trim(implode(' ', $class_names)) ?>">
                <? if (isset($props['icon'])) echo $props['icon']; ?>
                <?= $props['label'] ?>
            </a>
        <?php break;
        default: ?>
            <button
                id="<?= $props['id'] ?? '' ?>"
                class="<?= trim(implode(' ', $class_names)) ?>"
                <?php if (isset($props['type'])) echo 'type="' . $props['type'] . '"' ?>
                <?php if (isset($props['disabled'])) echo 'disabled' ?>
            >
                <?= $props['icon'] ?? '' ?>
                <?= $props['label'] ?? '' ?>
            </button>
        <?php break;
    }
}