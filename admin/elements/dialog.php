<?php

function yz_dialog(array $props): void {
    yz_open_portal($props['portal'] ?? 'default', function() use($props) {
        $class_names = [
            'yuzu',
            'dialog'
        ];

        if (isset($props['fixed']) && $props['fixed']) {
            $class_names[] = 'dialog-fixed';
        }

        if (isset($props['full-size']) && $props['full-size']) {
            $class_names[] = 'dialog-full-size';
        }

        if (isset($props['class_name'])) {
            $class_names[] = $props['class_name'];
        }

        $id = $props['id'] ?? '';
        $class = trim(implode(' ', $class_names)); ?>

        <dialog id="<?= $id ?>" class="<?= $class ?>">
            <?php yz_card(['content' => function() use($props) {
                yz_form(['method' => 'dialog', 'content' => function() use($props) {
                    if (isset($props['title'])) { ?>
                        <header class="yuzu dialog-header">
                            <?php yz_title(['level' => 2, 'content' => function() use($props) {
                                yz_text($props['title']);
                            }]); ?>
                        </header>
                    <?php }
                    if (isset($props['content'])) { ?>
                        <div class="yuzu dialog-content">
                            <?php $props['content'](); ?>
                        </div>
                    <?php }
                    if (isset($props['footer'])) { ?>
                        <footer class="yuzu dialog-footer">
                            <?php $props['footer'](); ?>
                        </footer>
                    <?php }
                }]);
            }]) ?>
        </dialog>
        <script>
            window.addEventListener('load', () => {
                <?php if (isset($props['open']) && $props['open']) { ?>
                    document.getElementById('<?= $id ?>').showModal();
                <?php } ?>
            });
        </script>
    <?php });
}