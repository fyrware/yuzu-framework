<?php

function yz_flex_layout(array $props): void {
    $class_names = [
        'yuzu',
        'flex-layout'
    ];

    if (isset($props['direction'])) {
        $class_names[] = 'flex-direction-' . $props['direction'];
    }

    if (isset($props['justification'])) {
        $class_names[] = 'flex-justification-' . $props['justification'];
    }

    if (isset($props['alignment'])) {
        $class_names[] = 'flex-alignment-' . $props['alignment'];
    }

    if (isset($props['wrap'])) {
        $class_names[] = 'flex-wrap-' . $props['wrap'];
    }

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    if (isset($props['gap']) && (gettype($props['gap']) === 'integer' || gettype($props['gap']) === 'double')) {
        $props['gap'] .= 'px';
    } else {
        $props['gap'] = 0;
    } ?>

    <section id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>" style="gap: <?= $props['gap'] ?>">
        <?php foreach ($props['items'] as $item) {
            $item_class_names = [
                'yuzu',
                'flex-item'
            ];

            if (isset($item['grow']) && $item['grow']) {
                $item_class_names[] = 'flex-grow';
            }

            if (isset($item['shrink']) && $item['shrink']) {
                $item_class_names[] = 'flex-shrink';
            }

            if (isset($item['class_name'])) {
                $item_class_names[] = $item['class_name'];
            } ?>

            <div id="<?= $item['id'] ?? '' ?>" class="<?= trim(implode(' ', $item_class_names)) ?>">
                <?= $item['content']() ?>
            </div>
        <?php } ?>
    </section>
<?php }