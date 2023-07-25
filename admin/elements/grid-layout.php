<?php

function yz_grid_layout(array $props): void {
    $styles = [];
    $class_names = [
        'yuzu',
        'grid-layout'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    if (isset($props['columns'])) {
        if (gettype($props['columns']) === 'integer' || gettype($props['columns']) === 'double') {
            $styles[] = 'grid-template-columns: repeat(' . $props['columns'] . ', 1fr);';
        } else if (gettype($props['columns']) === 'string') {
            $styles[] = 'grid-template-columns: ' . $props['columns'] . ';';
        } else if (gettype($props['columns']) === 'array') {
            $styles[] = 'grid-template-columns: ' . implode(' ', $props['columns']) . ';';
        }
    }

    if (isset($props['gap'])) {
        if (gettype($props['gap']) === 'integer' || gettype($props['gap']) === 'double') {
            $styles[] = 'gap: ' . $props['gap'] . 'px;';
        } else {
            $styles[] = 'gap: ' . $props['gap'] . ';';
        }
    } ?>

    <section id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>" style="<?= trim(implode(' ', $styles)) ?>">
        <?php foreach ($props['items'] ?? [] as $item) {
            $item_class_names = [
                'yuzu',
                'grid-item',
            ];

            if (isset($item['shape'])) {
                $item_class_names[] = 'grid-item-shape-' . $item['shape'];
            }

            if (isset($item['class_name'])) {
                $item_class_names[] = $item['class_name'];
            } ?>

            <div id="<?= $item['id'] ?? '' ?>" class="<?= trim(implode(' ', $item_class_names)) ?>">
                <div class="yuzu grid-item-content">
                    <?= $item['content']() ?>
                </div>
            </div>
        <?php } ?>
    </section>
<?php }