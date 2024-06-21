<?php

class Yz_Dialog {

    public static function render(array $props): void {
        global $yz;

        $portal = $yz->tools->get_value($props, 'portal', 'default_portal');

        $yz->html->transport_source($portal, [
            'children' => function() use($yz, $props) {
                $id          = $yz->tools->get_value($props, 'id');
                $class       = $yz->tools->get_value($props, 'class');
                $icon        = $yz->tools->get_value($props, 'icon');
                $title       = $yz->tools->get_value($props, 'title');
                $action      = $yz->tools->get_value($props, 'action');
                $method      = $yz->tools->get_value($props, 'method', 'dialog');
                $open        = $yz->tools->get_value($props, 'open', false);
                $modal       = $yz->tools->get_value($props, 'modal', false);
                $dismissible = $yz->tools->get_value($props, 'dismissible', true);
                $fixed        = $yz->tools->get_value($props, 'fixed', false);
                $full_size   = $yz->tools->get_value($props, 'full_size', false);
                $grow_height = $yz->tools->get_value($props, 'grow_height', false);
                $width       = $yz->tools->get_value($props, 'width');
                $children    = $yz->tools->get_value($props, 'children');
                $attributes  = $yz->tools->get_value($props, 'attr', []);
                $data_set    = $yz->tools->get_value($props, 'data', []);

                $classes = [
                    'yz',
                    'yuzu',
                    'dialog'
                ];

                if ($fixed) {
                    $classes[] = 'dialog-fixed';
                }

                if ($grow_height) {
                    $classes[] = 'dialog-grow-height';
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

                $yz->html->element('dialog', [
                    'id'       => $id,
                    'class'    => $classes,
                    'style'    => $style,
                    'data'     => $data_set,
                    'attr'     => $attributes,
                    'children' => function() use($yz, $action, $method, $children, $icon, $title) {
                        $yz->html->card([
                            'children' => function() use($yz, $action, $method, $children, $icon, $title) {
                                $yz->html->form([
                                    'action'   => $action,
                                    'method'   => $method,
                                    'children' => function() use($yz, $children, $icon, $title) {
                                        $yz->html->element('header', [
                                            'class' => 'yz dialog-header',
                                            'children' => function() use($yz, $icon, $title) {
                                                if ($icon) {
                                                    $yz->html->element('div', [
                                                        'class' => 'yz dialog-header-icon',
                                                        'children' => function() use($yz, $icon) {
                                                            $yz->html->icon($icon, [ 'appearance' => 'duotone' ]);
                                                        }
                                                    ]);
                                                }
                                                if ($title) {
                                                    $yz->html->title($title, [ 'level' => 2 ]);
                                                }
                                            }
                                        ]);
                                        if (is_callable($children)) {
                                            $yz->html->element('div', [
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
        <style data-yz-element="dialog">
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

            .yz.dialog[open] {
                display: flex;
                align-items: center;
                justify-content: center;
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

            .yz.dialog.grow-height {
                height: 100%;
            }

            .yz.dialog.dialog-full-size {
                width: 100%;
                height: 100%;
            }

            .yz.dialog.dialog-full-size > .yz.card {
                flex-grow: 1;
                width: 100%;
                height: 100%;
            }

            .yz.dialog > .yz.card {
                display: flex;
                flex-direction: column;
                width: 100%;
                max-width: calc(100vw - 60px);
                max-height: calc(100vh - 60px);
                /*height: 100%;*/
                border: none;
                background: #f0f0f1;
                padding: 0;
                /*box-shadow: 0 5px 15px rgba(0,0,0,.7);*/
                border-radius: 4px;
                overflow: auto;
            }

            .yz.dialog .yz.form {
                display: contents;
            }

            .yz.dialog .yz.dialog-header {
                display: flex;
                flex-shrink: 0;
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
                display: flex;
                flex-direction: column;
                padding: 20px;
                overflow: auto;
            }

            .yz.dialog.grow-height .yz.dialog-content {
                flex-grow: 1;
                flex-shrink: 1;
                height: 0;
            }

            .yz.dialog.dialog-full-size .yz.dialog-content {
                flex-grow: 1;
            }
        </style>
    <?php }

    public static function render_script() { ?>
        <script data-yz-element="dialog">
            yz.ready().observe(() => {
                yz('.yz.dialog').forEach((dialog) => {
                    const open        = 'open'        in dialog.item().dataset;
                    const modal       = 'modal'       in dialog.item().dataset;
                    const dismissible = 'dismissible' in dialog.item().dataset;

                    console.log('dialog', dialog, open, modal, dismissible)

                    if (open && modal) {
                        dialog.hide()
                        dialog.show({ modal: true })
                    }

                    if (!dismissible) {
                        dialog.spy('cancel').observe((cancel) => {
                            cancel.preventDefault();
                        });
                    }

                    yz('form', dialog).spy('reset').observe(() => {
                        dialog.hide();
                    });
                });
            });
        </script>
    <?php }
}