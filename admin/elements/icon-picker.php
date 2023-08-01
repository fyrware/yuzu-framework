<?php

const YUZU_ICON_PICKER_VALID_APPEARANCES = [
    'thin',
    'light',
    'regular',
    'bold',
    'duotone',
    'fill'
];

function yz_icon_picker(array $props): void {
    $id         = yz_prop($props, 'id', '');
    $class      = yz_prop($props, 'class', '');
    $name       = yz_prop($props, 'name', $id);
    $value      = yz_prop($props, 'value', 'puzzle-piece');
    $appearance = yz_prop($props, 'appearance', 'regular');

    assert(is_string($value), 'Icon picker value must be a string');
    assert(in_array($appearance, YUZU_ICON_PICKER_VALID_APPEARANCES), 'Icon picker appearance must be one of: ' . yz_join(YUZU_ICON_PICKER_VALID_APPEARANCES, ', '));

    $classes = [
        'yuzu',
        'icon-picker'
    ];

    if ($class) {
        $classes[] = $class;
    }

    yz_element(['class' => yz_join($classes), 'children' => function() use($id, $name, $value, $appearance) {
        yz_input(['id' => $id, 'name' => $name, 'hidden' => true, 'value' => $value]);
        yz_flex_layout(['alignment' => 'stretch', 'items' => [
            ['children' => function() use($value, $appearance) {
                yz_image(yz_icon_url(['glyph' => $value, 'appearance' => $appearance]), [
                    'class' => 'selected-icon',
                    'alt'   => 'selected icon'
                ]);
            }],
            ['children' => function() {
                yz_button([
                    'class' => 'select-icon',
                    'icon'  => yz_icon_svg(['glyph' => 'folder-open', 'appearance' => 'duotone'])
                ]);
            }]
        ]]);
    }]);

    $file_dir    = plugin_dir_path(__FILE__) . '../../icons/assets/' . $appearance;
    $icon_files  = array_filter(scandir($file_dir), fn($icon) => $icon !== '.' && $icon !== '..');
    $icon_glyphs = array_map(fn($icon) => str_replace('.svg', '', $icon), $icon_files);
    $icons       = [];

    foreach ($icon_glyphs as $glyph) {
        $icons[$glyph] = yz_icon_url([
            'glyph'      => $glyph,
            'appearance' => $appearance
        ]);
    } ?>

    <script>
        yz.setIconSet('<?= $appearance ?>',  <?= json_encode($icons) ?>);

        yz.ready().then(() => {
            const dialog = yz('#<?= $id . '_dialog' ?>').item();
            const button = yz('#<?= $id ?> + .flex-layout .select-icon').item();

            button.addEventListener('click', (event) => {
                event.preventDefault();
                yz.openDialog(dialog);
            });
        });
    </script>

    <?php yz_dialog([
        'id'       => $id . '_dialog',
        'class'    => 'icon-picker-dialog',
        'fixed'    => true,
        'title'    => 'Select an icon',
        'children' => function() use($id, $appearance, $icons) {
            yz_element('aside', ['class' => 'yuzu icon-picker-toolbar', 'children' => function() use($id) {
                yz_input([
                    'type' => 'search',
                    'id' => $id . '_dialog_search',
                    'placeholder' => 'ex: arrow'
                ]);
            }]);
            yz_grid_layout([
                'id' => $id . '_dialog_layout',
                'gap' => 15,
                'columns' => 10
            ]); ?>

            <script>
                yz.ready().then(() => {
                    const dialog       = document.getElementById('<?= $id . '_dialog' ?>');
                    const searchInput  = document.getElementById('<?= $id . '_dialog_search' ?>');
                    const gridLayout   = document.getElementById('<?= $id . '_dialog_layout' ?>');
                    const submitButton = document.getElementById('<?= $id . '_submit' ?>');
                    const cancelButton = document.getElementById('<?= $id . '_cancel' ?>');

                    dialog.addEventListener('open', () => {
                        dialog.scrollTop      = 0;
                        searchInput.value     = '';
                        gridLayout.innerHTML  = '';
                        submitButton.disabled = true;

                        for (const [glyph, url] of Object.entries(yz.getIconSet('<?= $appearance ?>'))) {
                            const label           = document.createElement('label');
                            label.htmlFor         = '<?= $id ?>_option_' + glyph;
                            label.className       = 'yuzu icon-picker-option';
                            label.dataset.glyph   = glyph;
                            label.dataset.iconUrl = url;

                            const image     = document.createElement('img');
                            image.className = 'yuzu icon-picker-image';
                            image.src       = url;

                            const radio     = document.createElement('input');
                            radio.id        = '<?= $id ?>_option_' + glyph;
                            radio.className = 'yuzu icon-picker-radio';
                            radio.type      = 'radio';
                            radio.name      = '<?= $id ?>_option';
                            radio.value     = glyph;

                            radio.addEventListener('change', () => {
                                if (submitButton.disabled) {
                                    submitButton.disabled = false;
                                }
                            });

                            label.appendChild(image);
                            label.appendChild(radio);
                            gridLayout.appendChild(label);
                        }

                        yz.nextFrame().then(() => {
                            const dialogCard           = dialog.querySelector('.yuzu.card');
                            dialogCard.style.minWidth  = dialog.offsetWidth + 'px';
                            dialogCard.style.minHeight = dialog.offsetHeight + 'px';
                        });
                    });

                    searchInput.addEventListener('input', yz.debounce(() => {
                        const query = searchInput.value.toLowerCase().trim().replaceAll(/\s+/g, '-');

                        for (const icon of gridLayout.children) {
                            if (icon.dataset.glyph.includes(query)) {
                                icon.style.display = 'flex';
                            } else {
                                icon.style.display = 'none';
                            }
                        }
                    }));

                    cancelButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        yz.closeDialog(dialog);
                    });

                    dialog.querySelector('form').addEventListener('submit', (event) => {
                        const selectedGlyph     = document.querySelector('input[name="<?= $id ?>_option"]:checked').value;
                        const selectedIcon      = document.querySelector('label[for="<?= $id ?>_option_' + selectedGlyph + '"]');
                        const iconPickerInput   = document.getElementById('<?= $id ?>');
                        const selectedIconImage = document.querySelector('#<?= $id ?> + .flex-layout .selected-icon');

                        iconPickerInput.value = selectedIcon.dataset.glyph;
                        selectedIconImage.src = selectedIcon.dataset.iconUrl;

                        event.preventDefault();
                        yz.closeDialog(dialog);
                    });
                });
            </script>

        <?php },
        'footer' => function() use($id) {
            yz_button(['id' => $id . '_cancel', 'label' => 'Cancel', 'type' => 'reset']);
            yz_button(['id' => $id . '_submit', 'disabled' => true, 'variant' => 'primary', 'type' => 'submit', 'label' => 'Use icon']);
        }
    ]);
}