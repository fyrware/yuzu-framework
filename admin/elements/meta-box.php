<?php

function yz_meta_box(array $props): void {
    $class_names = [
        'yuzu',
        'metabox',
        'postbox'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <section id="<?= $props['id'] ?? '' ?>" class="<?= trim(implode(' ', $class_names)) ?>">
        <header class="yuzu metabox-header postbox-header">
            <h2 class="yuzu handle hndle ui-sortable-handle">
                <?= $props['title'] ?>
            </h2>
<!--            <div class="yuzu handle-actions hide-if-no-js">-->
<!--                <button class="yuzu handle-order-higher" aria-disabled="false">-->
<!--                    <span class="yuzu screen-reader-text">Move up</span>-->
<!--                    <span class="yuzu order-higher-indicator" aria-hidden="true"></span>-->
<!--                </button>-->
<!--                <button class="yuzu handle-order-lower" aria-disabled="false">-->
<!--                    <span class="yuzu screen-reader-text">Move down</span>-->
<!--                    <span class="yuzu order-lower-indicator" aria-hidden="true"></span>-->
<!--                </button>-->
<!--                <button class="yuzu handlediv" aria-expanded="true">-->
<!--                    <span class="yuzu screen-reader-text">Toggle panel: Excerpt</span>-->
<!--                    <span class="yuzu toggle-indicator" aria-hidden="true"></span>-->
<!--                </button>-->
<!--            </div>-->
        </header>
        <div class="yuzu inside">
            <?= $props['content']() ?>
        </div>
    </section>
<?php }