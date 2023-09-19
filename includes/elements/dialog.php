<?php

class Yz_Dialog {

    public static function render(array $props): void {
        $portal = Yz_Array::value_or($props, 'portal', 'default_portal');

        Yz::Portal_Injection($portal, [
            'children' => function() use($props) {
                $id          = Yz_Array::value_or($props, 'id');
                $class       = Yz_Array::value_or($props, 'class');
                $action      = Yz_Array::value_or($props, 'action');
                $method      = Yz_Array::value_or($props, 'method', 'dialog');
                $open        = Yz_Array::value_or($props, 'open', false);
                $modal       = Yz_Array::value_or($props, 'modal', false);
                $dismissible = Yz_Array::value_or($props, 'dismissible', true);
                $fixed        = Yz_Array::value_or($props, 'fixed', false);
                $full_size   = Yz_Array::value_or($props, 'full_size', false);
                $children    = Yz_Array::value_or($props, 'children');
                $attributes  = Yz_Array::value_or($props, 'attr', []);
                $data_set    = Yz_Array::value_or($props, 'data', []);

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

                if ($open) {
                    $attributes['open'] = true;
                    $data_set['open'] = true;
                }

                if ($modal) {
                    $data_set['modal'] = true;
                }

                if ($dismissible) {
                    $data_set['dismissible'] = true;
                }

                Yz::Element('dialog', [
                    'id'       => $id,
                    'class'    => Yz_Array::join($classes),
                    'data'     => $data_set,
                    'attr'     => $attributes,
                    'children' => function() use($action, $method, $children) {
                        Yz::Card([
                            'children' => function() use($action, $method, $children) {
                                Yz::Form([
                                    'action'   => $action,
                                    'method'   => $method,
                                    'children' => function() use($children) {
                                        if (is_callable($children)) {
                                            $children();
                                        }
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style>

        </style>
    <?php }

    public static function render_script() { ?>
        <script>
            yz.ready().then(() => {
                yz('.yuzu.dialog').forEach((dialog) => {
                    const open        = 'open' in dialog.dataset;
                    const modal       = 'modal' in dialog.dataset;
                    const dismissible = 'dismissible' in dialog.dataset;

                    if (open && modal) {
                        dialog.close();
                        dialog.showModal();
                    }

                    if (!dismissible) {
                        dialog.addEventListener('cancel', (event) => {
                            event.preventDefault();
                        });
                    }

                    yz('form', dialog).forEach((form) => {
                        form.addEventListener('reset', () => {
                            dialog.close();
                        });
                    });
                });
            });
        </script>
    <?php }
}