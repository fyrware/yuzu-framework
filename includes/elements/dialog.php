<?php

class Yz_Dialog {

    public static function render(array $props): void {
        global $yz;

        $portal = $yz->tools->key_or_default($props, 'portal', 'default_portal');

        $yz->html->transport_source($portal, [
            'children' => function() use($yz, $props) {
                $id          = $yz->tools->key_or_default($props, 'id');
                $class       = $yz->tools->key_or_default($props, 'class');
                $icon        = $yz->tools->key_or_default($props, 'icon');
                $title       = $yz->tools->key_or_default($props, 'title');
                $action      = $yz->tools->key_or_default($props, 'action');
                $method      = $yz->tools->key_or_default($props, 'method', 'dialog');
                $open        = $yz->tools->key_or_default($props, 'open', false);
                $modal       = $yz->tools->key_or_default($props, 'modal', false);
                $dismissible = $yz->tools->key_or_default($props, 'dismissible', true);
                $fixed        = $yz->tools->key_or_default($props, 'fixed', false);
                $full_size   = $yz->tools->key_or_default($props, 'full_size', false);
                $width       = $yz->tools->key_or_default($props, 'width');
                $children    = $yz->tools->key_or_default($props, 'children');
                $attributes  = $yz->tools->key_or_default($props, 'attr', []);
                $data_set    = $yz->tools->key_or_default($props, 'data', []);

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

            .yz.dialog > .yz.card {
                display: flex;
                flex-direction: column;
                width: 100%;
                max-width: 100%;
                height: 100%;
                border: none;
                background: #f0f0f1;
                padding: 0;
                /*box-shadow: 0 5px 15px rgba(0,0,0,.7);*/
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
                /*flex-grow: 1;*/
                /*flex-shrink: 1;*/
                /*height: 0;*/
                padding: 20px;
                overflow: auto;
            }

            .yz.dialog.dialog-full-size .yz.dialog-content {
                flex-grow: 1;
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
        <script data-yz-element="dialog">
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