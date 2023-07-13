<?php

function yz_grid_layout(array $props): void {
    $class_names = [
        'yuzu',
        'grid-layout'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <section id="<?= $props['id'] ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <?php foreach ($props['items'] as $item) {
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

            <article id="<?= $item['id'] ?>" class="<?= trim(implode(' ', $item_class_names)) ?>">
                <div class="yuzu grid-item-content">
                    <?= $item['content']() ?>
                </div>
            </article>
        <?php } ?>
    </section>
<?php }