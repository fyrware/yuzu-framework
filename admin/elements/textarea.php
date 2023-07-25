<?php

function yz_textarea(array $props): void {
    $class_names = [
        'yuzu',
        'textarea'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $id = $props['id'] ?? $props['name'] ?? '';
    $name = $props['name'] ?? $id;
    $rows = $props['rows'] ?? 3;
    $type = $props['type'] ?? 'text';
    $label = $props['label'] ?? '';
    $placeholder = $props['placeholder'] ?? null;
    $required = $props['required'] ?? false;
    $default_value = $props['value'] ?? '';
    $class = trim(implode(' ', $class_names));

    if (isset($props['label'])) {
        yz_flex_layout([
            'gap' => 5,
            'direction' => 'column',
            'items' => [
                ['content' => function() use($id, $label, $required) { ?>
                    <label for="<?= $id ?>"  class="yuzu input-label <?php if ($required) echo 'required' ?>">
                        <?= $label ?>
                    </label>
                <?php }],
                ['content' => function() use($id, $name, $rows, $class, $type, $placeholder, $required, $default_value) { ?>
                    <textarea
                        id="<?= $id ?>"
                        name="<?= $name ?>"
                        class="<?= $class ?>"
                        type="<?= $type ?>"
                        <?php if (isset($rows)) { ?>
                            rows="<?= $rows ?>"
                        <?php } ?>
                        <?php if (isset($placeholder)) { ?>
                            placeholder="<?= $placeholder ?>"
                        <?php } ?>
                        <?php if (isset($default_value)) { ?>
                            value="<?= $default_value ?>"
                        <?php } ?>
                        <?php if ($required) { ?>
                            required
                        <?php } ?>
                    ></textarea>
                <?php }]
            ]
        ]);
    } else { ?>
        <textarea
            id="<?= $id ?>"
            name="<?= $name ?>"
            class="<?= $class ?>"
            type="<?= $type ?>"
            <?php if (isset($rows)) { ?>
                rows="<?= $rows ?>"
            <?php } ?>
            <?php if (isset($placeholder)) { ?>
                placeholder="<?= $placeholder ?>"
            <?php } ?>
            <?php if (isset($default_value)) { ?>
                value="<?= $default_value ?>"
            <?php } ?>
            <?php if ($required) { ?>
                required
            <?php } ?>
        ></textarea>
    <?php }
}