<?php

class Yz_Dialog {

    public static function render(array $props): void {
        $portal = Yz_Array::value_or($props, 'portal', 'default_portal');

        Yz::Portal_Injection($portal, [
            'children' => function() use($props) {
                $id          = Yz_Array::value_or($props, 'id');
                $class       = Yz_Array::value_or($props, 'class');
                $icon        = Yz_Array::value_or($props, 'icon');
                $title       = Yz_Array::value_or($props, 'title');
                $action      = Yz_Array::value_or($props, 'action');
                $method      = Yz_Array::value_or($props, 'method', 'dialog');
                $open        = Yz_Array::value_or($props, 'open', false);
                $modal       = Yz_Array::value_or($props, 'modal', false);
                $dismissible = Yz_Array::value_or($props, 'dismissible', true);
                $fixed        = Yz_Array::value_or($props, 'fixed', false);
                $full_size   = Yz_Array::value_or($props, 'full_size', false);
                $width       = Yz_Array::value_or($props, 'width');
                $children    = Yz_Array::value_or($props, 'children');
                $attributes  = Yz_Array::value_or($props, 'attr', []);
                $data_set    = Yz_Array::value_or($props, 'data', []);

                $classes = [
                    'yz',
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

                $style = [];

                if ($width) {
                    $style['width'] = is_string($width) ? $width : $width . 'px';
                }

                Yz::Element('dialog', [
                    'id'       => $id,
                    'class'    => Yz_Array::join($classes),
                    'style'    => Yz_Array::join_key_value($style),
                    'data'     => $data_set,
                    'attr'     => $attributes,
                    'children' => function() use($action, $method, $children, $icon, $title) {
                        Yz::Card([
                            'children' => function() use($action, $method, $children, $icon, $title) {
                                Yz::Form([
                                    'action'   => $action,
                                    'method'   => $method,
                                    'children' => function() use($children, $icon, $title) {
                                        Yz::Element('header', [
                                            'class' => 'yz dialog-header',
                                            'children' => function() use($icon, $title) {
                                                if ($icon) {
                                                    Yz::Element('div', [
                                                        'class' => 'yz dialog-header-icon',
                                                        'children' => function() use($icon) {
                                                            Yz::Icon($icon, [ 'appearance' => 'duotone' ]);
                                                        }
                                                    ]);
                                                }
                                                if ($title) {
                                                    Yz::Title($title, [ 'level' => 2 ]);
                                                }
                                            }
                                        ]);
                                        if (is_callable($children)) {
                                            Yz::Element('div', [
                                                'class' => 'yz dialog-content',
                                                'children' => $children
                                            ]);
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
            .yz.dialog {
                min-width: 240px;
                max-height: calc(100% - 60px);
                background: none;
                border: none;
                border-radius: 4px;
                outline: none;
                padding: 0;
                inset: 0;
                z-index: 9991;
            }

            .yz.dialog::backdrop {
                background: rgba(0, 0, 0, 0.5);
            }

            .yz.dialog:has(.yz.dialog[open])::backdrop {
                background: rgba(0, 0, 0, 0);
            }

            .yz.dialog.dialog-fixed {
                position: fixed;
            }

            .yz.dialog.dialog-full-size {
                width: calc(100% - 60px);
                height: calc(100% - 60px);
            }

            .yz.dialog .yz.card {
                display: flex;
                flex-direction: column;
                height: 100%;
                border: none;
                background: #f0f0f1;
                padding: 0;
                box-shadow: 0 5px 15px rgba(0,0,0,.7);
                border-radius: 4px;
            }

            .yz.dialog .yz.form {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }

            .yz.dialog .yz.dialog-header {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #fff;
                padding: 5px 20px;
                height: 48px;
                border-bottom: 1px solid #dcdcde;
                border-radius: 4px 4px 0 0;
                position: sticky;
                top: 0;
                z-index: 1;
            }

            .yz.dialog .yz.dialog-header .yz.dialog-header-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 20px;
                width: 20px;
            }

            .yz.dialog .yz.dialog-header .yz.dialog-header-icon .yz.icon {
                width: 24px;
                height: 24px;
                flex-shrink: 0;
            }

            .yz.dialog .yz.dialog-header .yz.title {
                margin: 0 !important;
                padding: 0 !important;
            }

            .yz.dialog .yz.dialog-content {
                flex-grow: 1;
                padding: 20px;
            }

            .yz.dialog .yz.dialog-footer {
                display: flex;
                align-items: center;
                justify-content: end;
                background: #fff;
                gap: 10px;
                height: 48px;
                padding: 5px 20px;
                border-top: 1px solid #dcdcde;
                border-radius: 0 0 4px 4px;
                position: sticky;
                bottom: 0;
                z-index: 1;
            }

        </style>
    <?php }

    public static function render_script() { ?>
        <script>
            yz.ready().observe(() => {
                yz('.yuzu.dialog').forEach((dialog) => {
                    const open        = 'open'        in dialog.dataset;
                    const modal       = 'modal'       in dialog.dataset;
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