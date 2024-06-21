<?php

class Yz_Icon_Picker {

    private const VALID_APPEARANCES = [
        'thin',
        'light',
        'regular',
        'bold',
        'duotone',
        'fill'
    ];

    private static function get_icon_url(string $glyph, string $appearance = 'regular'): string {
        $file_dir   = plugin_dir_url(__FILE__) . '../../../icons/assets/' . $appearance;
        $file_name  = ($appearance === 'regular' ? $glyph : $glyph . '-' . $appearance) . '.svg';

        return $file_dir . '/' . $file_name;
    }

    public static function render(array $props): void {
        global $yz;

        $id         = $yz->tools->get_value($props, 'id');
        $class      = $yz->tools->get_value($props, 'class');
        $name       = $yz->tools->get_value($props, 'name', $id);
        $label      = $yz->tools->get_value($props, 'label');
        $value      = $yz->tools->get_value($props, 'value', 'puzzle-piece');
        $appearance = $yz->tools->get_value($props, 'appearance', 'regular');
        $required   = $yz->tools->get_value($props, 'required', false);

        assert(is_string($value), 'Icon picker value must be a string');
        assert(in_array($appearance, self::VALID_APPEARANCES), 'Icon picker appearance must be one of: ' . $yz->tools->join_values(self::VALID_APPEARANCES, ', '));

        $id ??= $name;

        $classes = [
            'yz',
            'icon-picker'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->flex_layout([
            'gap'       => 5,
            'direction' => 'column',
            'class'     => yz_join($classes),
            'children'  => function() use($yz, $id, $name, $label, $value, $appearance, $required) {
                if ($label) {
                    $label_classes = [
                        'yz',
                        'input-label',
                    ];

                    if ($required) {
                        $label_classes[] = 'input-label-required';
                    }

                    $yz->html->text($label, [
                        'class'   => $label_classes,
                        'variant' => 'label',
                        'attr'    => [ 'for' => $id ]
                    ]);
                }
                $yz->html->input([
                    'id'     => $id,
                    'name'   => $name,
                    'hidden' => true,
                    'value'  => $value,
                    'data' => [
                        'appearance' => $appearance
                    ]
                ]);
                $yz->html->flex_layout([
                    'alignment' => 'stretch',
                    'children' => function() use($yz, $value, $appearance) {
                        $yz->html->image(self::get_icon_url($value, $appearance), [
                            'class' => 'selected-icon',
                            'alt'   => 'selected icon'
                        ]);
                        $yz->html->button([
                            'class' => 'select-icon',
                            'icon'  => 'folder-open'
                        ]);
                    }
                ]);
            }
        ]);

        $file_dir     = plugin_dir_path(__FILE__) . '../../../icons/assets/' . $appearance;
        $icon_files   = array_filter(scandir($file_dir), fn($icon) => $icon !== '.' && $icon !== '..');
        $icon_glyphs = array_map(fn($icon) => str_replace('.svg', '', $icon), $icon_files);
        $icons       = [];

        foreach ($icon_glyphs as $glyph) {
            $icons[$glyph] = self::get_icon_url($glyph, $appearance);
        }

        $yz->html->dialog([
            'id'       => $name . '_dialog',
            'class'    => 'icon-picker-dialog',
            'title'    => 'Select an icon',
            'children' => function() use($yz, $name, $icons) {
                $yz->html->element('aside', [
                    'class'    => 'yz icon-picker-toolbar',
                    'children' => function() use($yz, $name) {
                        $yz->html->input([
                            'id'   => $name . '_search',
                            'type' => 'search',
                            'width' => 320,
                            'placeholder' => 'Search icons'
                        ]);
                    }
                ]);

                $yz->html->grid_layout([
                    'id'      => $name . '_dialog_layout',
                    'gap'     => 15,
                    'columns' => 10,
                ]);

                $yz->html->element('footer', [
                    'class'    => 'yz icon-picker-footer',
                    'children' => function() use($yz, $name) {
                        $yz->html->button([
                            'id'    => $name . '_cancel',
                            'label' => 'Cancel',
                            'variant' => 'secondary',
                            'type' => 'reset'
                        ]);
                        $yz->html->button([
                            'id'    => $name . '_submit',
                            'label' => 'Select',
                            'variant' => 'primary',
                            'type' => 'submit',
                            'disabled' => true
                        ]);
                    }
                ]);
            }
        ]); ?>

        <script>
            yz.icons.learn('<?= $appearance ?>', <?= json_encode($icons) ?>);
        </script>
    <?php }
    
    public static function render_style(): void { ?>
        <style>
            .yz.icon-picker {
                max-width: max-content;
            }

            .yz.icon-picker .yz.selected-icon {
                display: block;
                width: 48px;
                height: 48px;
                padding: 8px;
                border: 1px solid #8c8f94;
                border-right: none;
                border-radius: 4px 0 0 4px;
                background-color: #ffffff;
                background-image:
                    repeating-linear-gradient(
                        45deg,
                        #f0f0f1 25%,
                        transparent 25%,
                        transparent 75%,
                        #f0f0f1 75%,
                        #f0f0f1
                    ),
                    repeating-linear-gradient(
                        45deg,
                        #f0f0f1 25%,
                        #ffffff 25%,
                        #ffffff 75%,
                        #f0f0f1 75%,
                        #f0f0f1
                    );
                background-position: 0 0, 8px 8px;
                background-size: 16px 16px;
            }

            [data-darkreader-scheme="dark"] .yz.icon-picker .yz.selected-icon {
                background: none;
                filter: invert(100%);
                opacity: 0.66;
            }

            .yz.icon-picker .yz.select-icon {
                height: 66px;
                box-sizing: border-box;
                border-radius: 0 4px 4px 0;
            }

            .yz.icon-picker-dialog {
                /*height: 100%;*/
            }

            .yz.icon-picker-dialog .yz.icon-picker-toolbar {
                display: flex;
                flex-shrink: 0;
                align-items: center;
                background: white;
                border-bottom: 1px solid #dcdcde;
                height: 48px;
                padding: 5px 20px;
                margin: -20px -20px 20px;
                position: sticky;
                top: -20px;
                z-index: 1;
            }

            .yz.icon-picker-dialog .yz.icon-picker-footer {
                display: flex;
                flex-shrink: 0;
                align-items: center;
                justify-content: end;
                background: #fff;
                gap: 10px;
                height: 48px;
                padding: 5px 20px;
                margin: 20px -20px -20px;
                border-top: 1px solid #dcdcde;
                position: sticky;
                bottom: -20px;
                z-index: 1;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                width: 80px;
                height: 80px;
                border: 1px solid #c3c4c7;
                border-radius: 4px;
                position: relative;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option::after {
                content: attr(data-glyph);
                padding: 0 2px;
                font-size: 8px;
                line-height: 8px;
                height: 8px;
                text-align: center;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option:hover {
                background-color: #fff;
                opacity: 1;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option:focus-within,
            .yz.icon-picker-dialog .yz.icon-picker-option:has(input[type="radio"]:checked) {
                background-color: #fff;
                border: 1px solid #fff;
                box-shadow: 0 0 0 3px #2271b1;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option img {
                display: block;
                width: 40px;
                height: 40px;
                opacity: 0.66;
            }

            [data-darkreader-scheme="dark"] .yz.icon-picker-dialog .yz.icon-picker-option img {
                filter: invert(100%);
            }

            .yz.icon-picker-dialog .yz.icon-picker-option:focus-within img,
            .yz.icon-picker-dialog .yz.icon-picker-option:has(input[type="radio"]:checked) img  {
                opacity: 1;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option input[type="radio"] {
                opacity: 0;
                position: absolute;
                top: -8px;
                right: -8px;
                margin: 0;
                width: 20px;
                height: 20px;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option input[type="radio"]:checked::before {
                width: 10px;
                height: 10px;
                margin: 4px;
            }

            .yz.icon-picker-dialog .yz.icon-picker-option:hover input[type="radio"],
            .yz.icon-picker-dialog .yz.icon-picker-option:focus-within input[type="radio"],
            .yz.icon-picker-dialog .yz.icon-picker-option input[type="radio"]:checked {
                opacity: 1;
            }
        </style>
    <?php } 

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                yz('.icon-picker').forEach((iconPicker) => {
                    const input        = yz('input', iconPicker);
                    const searchInput  = yz(`#${ input.name() }_search`);
                    const gridLayout   = yz(`#${ input.name() }_dialog_layout`);
                    const submitButton = yz(`#${ input.name() }_submit`);
                    const dialog       = yz(`#${ input.name() }_dialog`);
                    const dialogForm   = yz('form', dialog);

                    iconPicker.spy('click').observe(() => {
                        const docFragment = yz.fragment();

                        dialog.prop('scrollTop', 0);
                        searchInput.prop('value', '');
                        gridLayout.html('');
                        submitButton.prop('disabled', true);

                        Object.entries(yz.icons.library(input.data('appearance'))).forEach(([glyph, url]) => {
                            const label = yz.element('label');
                            label.attr('for', input.name() + '_option_' + glyph);
                            label.class('yz icon-picker-option');
                            label.data('glyph', glyph);
                            label.data('iconUrl', url);

                            const image = yz.element('img');
                            image.class('yz icon-picker-image');
                            image.prop('loading', 'lazy');
                            image.prop('src', url);

                            const radio = yz.element('input');
                            radio.id(input.name() + '_option_' + glyph);
                            radio.name(input.name() + '_option');
                            radio.class('yz icon-picker-radio');
                            radio.type('radio');
                            radio.value(glyph);

                            radio.spy('change').observe(() => {
                                if (submitButton.prop('disabled')) {
                                    submitButton.prop('disabled', false);
                                }
                            });

                            label.append(image);
                            label.append(radio);

                            docFragment.append(label);
                        });

                        gridLayout.append(docFragment);
                        dialog.item().showModal();
                    });

                    searchInput.spy('input').observe((input) => {
                        const query = input.target.value.toLowerCase().trim().replaceAll(/\s+/g, '-');

                        gridLayout.children().forEach((icon) => {
                            if (icon.data('glyph').includes(query)) {
                                icon.style('display', 'flex');
                            } else {
                                icon.style('display', 'none');
                            }
                        });
                    });

                    dialogForm.spy('submit').observe(() => {
                        const selectedGlyph     = yz(`input[name="${ input.name() }_option"]:checked`).value();
                        const selectedIcon      = yz(`label[for="${ input.id() }_option_${ selectedGlyph }"]`);
                        const selectedIconImage = yz(`input[name="${ input.name() }"] + .flex-layout .selected-icon`);

                        input.value(selectedIcon.data('glyph'));
                        selectedIconImage.prop('src', selectedIcon.data('iconUrl'));
                    });
                });
            });
        </script>
    <?php }
}