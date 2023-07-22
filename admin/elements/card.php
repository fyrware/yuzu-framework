<?php

function yz_card(array $props): void {
    $class_names = [
        'yuzu',
        'card'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <section class="<?= trim(implode(' ', $class_names)) ?>">
        <?php if (isset($props['content'])) { ?>
            <?php $props['content'](); ?>
        <?php } ?>
    </section>
 <?php }