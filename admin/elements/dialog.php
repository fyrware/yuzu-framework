<?php

function yz_dialog(array $props): void {
    yz_portal_inject($props['portal'] ?? 'default', function() use($props) {
        $id        = yz_prop($props, 'id', uniqid('dialog_'));
        $class     = yz_prop($props, 'class', '');
        $open      = yz_prop($props, 'open', false);
        $fixed     = yz_prop($props, 'fixed', false);
        $full_size = yz_prop($props, 'full-size', false);
        $action    = yz_prop($props, 'action');
        $method    = yz_prop($props, 'method', 'dialog');
        $title     = yz_prop($props, 'title', '');
        $children  = yz_prop($props, 'children');
        $footer    = yz_prop($props, 'footer');

        $classes = [
            'yuzu',
            'dialog'
        ];

        if ($fixed) {
            $classes[] = 'dialog-fixed';
        }

        if ($full_size) {
            $classes[] = 'dialog-full-size';
        }

        if ($class) {
            $classes[] = $class;
        }

        yz_element('dialog', [
            'id'       => $id,
            'class'    => yz_join($classes),
            'children' => function() use($action, $method, $title, $children, $footer) {
                yz_card(['children' => function() use($action, $method, $title, $children, $footer) {
                    yz_form([
                        'action'  => $action,
                        'method'  => $method,
                        'children' => function() use($title, $children, $footer) {
                            if ($title) {
                                yz_element('header', [
                                    'class'    => 'yuzu dialog-header',
                                    'children' => function() use($title) {
                                        yz_title(['level' => 2, 'children' => function() use($title) {
                                            yz_text($title);
                                        }]);
                                    }
                                ]);
                            }
                            if ($children) {
                                yz_element(['class' => 'yuzu dialog-content', 'children' => $children]);
                            }
                            if ($footer) {
                                yz_element('footer', [
                                    'class' => 'yuzu dialog-footer',
                                    'children' => $footer
                                ]);
                            }
                        }
                    ]);
                }]);
            }
        ]);
        if ($open) { ?>
            <script>
                yz.ready().then(() => {
                    yz.openDialog(yz('#<?= $id ?>').item());
                });
            </script>
        <?php }
    });
}