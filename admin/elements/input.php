<?php

function yz_input(array $props): void {
    $class_names = [
        'yuzu',
        'input'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $id = $props['id'] ?? $props['name'] ?? '';
    $name = $props['name'] ?? $id;
    $type = $props['type'] ?? 'text';
    $label = $props['label'] ?? '';
    $required = $props['required'] ?? false;
    $default_value = $props['value'] ?? '';
    $class = trim(implode(' ', $class_names));

    switch ($type) {
        case 'checkbox': ?>
            <label for="<?= $id ?>">
                <input id="<?= $id ?>" name="<?= $name ?>" class="<?= $class ?>" type="checkbox"/>
                <?= $props['label'] ?>
            </label>
        <?php break;
        case 'radio': ?>
            <label for="<?= $id ?>">
                <input id="<?= $id ?>" name="<?= $name ?>" class="<?= $class ?>" type="radio"/>
                <?= $props['label'] ?>
            </label>
        <?php break;
        default: ?>
            <?php yz_flex_layout([
                'direction' => 'column',
                'items' => [
                    ['content' => function() use($id, $label) { ?>
                        <label for="<?= $id ?>">
                            <?= $label ?>
                        </label>
                    <?php }],
                    ['content' => function() use($id, $name, $class, $type, $required, $default_value) { ?>
                        <input
                            id="<?= $id ?>"
                            name="<?= $name ?>"
                            class="<?= $class ?>"
                            type="<?= $type ?>"
                            <?php if (isset($default_value)) { ?>
                                value="<?= $default_value ?>"
                            <?php } ?>
                            <?php if ($required) { ?>
                                required
                            <?php } ?>
                        />
                    <?php }]
                ]
            ]) ?>
        <?php break;
    } ?>
<?php }