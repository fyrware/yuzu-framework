<?php

function yz_dialog(array $props): void {
    yz_open_portal($props['portal'] ?? 'default', function() use($props) {
        $class_names = [
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
        $class = trim(implode(' ', $class_names));

        yz_container(['variant' => 'dialog', 'id' => $id, 'class_name' => $class, 'content' => function() use($props) {
            yz_card(['content' => function() use($props) {
                yz_form(['method' => 'dialog', 'content' => function() use($props) {
                    if (isset($props['title'])) {
                        yz_container(['variant' => 'header', 'class_name' => 'dialog-header', 'content' => function() use($props) {
                            yz_title(['level' => 2, 'content' => function() use($props) {
                                yz_text($props['title']);
                            }]);
                        }]);
                    }
                    if (isset($props['content'])) {
                        yz_container(['variant' => 'content', 'class_name' => 'dialog-content', 'content' => function() use($props) {
                            $props['content']();
                        }]);
                    }
                    if (isset($props['footer'])) {
                        yz_container(['variant' => 'footer', 'class_name' => 'dialog-footer', 'content' => function() use($props) {
                            $props['footer']();
                        }]);
                    }
                }]);
            }]);
        }]);

        if (isset($props['open']) && $props['open']) { ?>
            <script>
                yz.ready().then(() => {
                    yz.openDialog(document.getElementById('<?= $id ?>'), { modal: true });
                });
            </script>
        <?php } ?>
    <?php });
}