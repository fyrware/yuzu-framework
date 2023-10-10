<?php

function yz_dialog(array $props): void {
    yz_portal_inject($props['portal'] ?? 'default_portal', function() use($props) {
        $id         = yz_prop($props, 'id', uniqid('dialog_'));
        $class      = yz_prop($props, 'class', '');
        $open       = yz_prop($props, 'open', false);
        $stubborn   = yz_prop($props, 'stubborn', false);
        $fixed      = yz_prop($props, 'fixed', false);
        $full_size  = yz_prop($props, 'full_size', false);
        $action     = yz_prop($props, 'action');
        $method     = yz_prop($props, 'method', 'dialog');
        $icon       = yz_prop($props, 'icon');
        $title      = yz_prop($props, 'title', '');
        $children   = yz_prop($props, 'children');
        $footer     = yz_prop($props, 'footer');
        $attributes = yz_prop($props, 'attributes', []);
        $data_set   = yz_prop($props, 'data_set', []);

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
            'data_set' => $data_set,
            'children' => function() use($id, $action, $method, $icon, $title, $children, $footer) {
                yz_card(['children' => function() use($id, $action, $method, $icon, $title, $children, $footer) {
                    yz_form([
                        'action'  => $action,
                        'method'  => $method,
                        'children' => function() use($icon, $title, $children, $footer) {
                            if ($title) {
                                yz_element('header', [
                                    'class'    => 'yuzu dialog-header',
                                    'children' => function() use($icon, $title) {
                                        if ($icon) {
                                            yz_element([
                                                'class' => 'yuzu dialog-header-icon',
                                                'children' => function() use($icon) {
                                                    echo $icon;
                                                }
                                            ]);
                                        }
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
                yz.ready().observe(() => {
                    yz.dialog.open(yz('#<?= $id ?>').item());
                });
            </script>
        <?php }
        if ($stubborn) { ?>
            <script>
                yz.ready().observe(() => {
                    yz.dialog.persist(yz('#<?= $id ?>').item());
                });
            </script>
        <?php } ?>
        <script>
            //yz.ready().observe(() => {
            //    const parentDialog = yz('#<?php //= $id ?>//').item().parentElement.closest('dialog');
            //
            //    if (parentDialog) {
            //       yz(`#${parentDialog.id} > [data-portal]`).item().appendChild(yz('#<?php //= $id ?>//').item());
            //    }
            //});
        </script>
    <?php });
}