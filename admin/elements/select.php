<?php

function yz_select(array $props): void {
    $class_names = [
        'yuzu',
        'select'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $id = $props['id'] ?? $props['name'] ?? '';
    $name = $props['name'] ?? $id;
    $value = $props['value'] ?? ''; ?>

    <select
        id="<?= $id ?>"
        name="<?= $name ?>"
        class="<?= trim(implode(' ', $class_names)) ?>"
        <?php if (isset($props['required'])) { ?>
            required
        <?php } ?>
    >
        <?php if (isset($props['options'])) foreach ($props['options'] as $option) {
            $opt_value = $option['value'] ?? '';
            $opt_label = $option['label'] ?? '';
            $opt_selected = $option['selected'] ?? false; ?>

            <option
                value="<?= $option['value'] ?>"
                <?php if ($value === $opt_value || $opt_selected) { ?>
                    selected
                <?php } ?>
            >
                <?= $option['label'] ?>
            </option>
        <?php } ?>
    </select>
<?php }
