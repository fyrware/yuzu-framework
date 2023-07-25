<?php

function yz_icon_picker(array $props): void {
    $class_names = [
        'yuzu',
        'icon-picker'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $id = $props['id'] ?? $props['name'] ?? '';
    $name = $props['name'] ?? $id;
    $class = trim(implode(' ', $class_names));
    $appearance = $props['appearance'] ?? 'regular';
    $value = $props['value'] ?? 'puzzle-piece';

    $file_dir = plugin_dir_path(__FILE__) . '../../icons/assets/' . $appearance;
    $icon_files = array_filter(scandir($file_dir), fn($icon) => $icon !== '.' && $icon !== '..');
    $icon_glyphs = array_map(fn($icon) => str_replace('.svg', '', $icon), $icon_files);
    $icons = [];

    foreach ($icon_glyphs as $glyph) {
        $icons[$glyph] = yz_icon_url([
            'glyph' => $glyph,
            'appearance' => $appearance
        ]);
    }

    yz_container(['class_name' => $class, 'content' => function() use($id, $name, $value, $appearance) {
        yz_input(['id' => $id, 'name' => $name, 'hidden' => true, 'value' => $value]);
        yz_flex_layout(['inline' => true, 'alignment' => 'stretch', 'items' => [
            ['content' => function() use($value, $appearance) {
                yz_image([
                    'class_name' => 'selected-icon',
                    'alt' => 'selected icon',
                    'src' => yz_icon_url(['glyph' => $value, 'appearance' => $appearance])
                ]);
            }],
            ['content' => function() {
                yz_button([
                    'class_name' => 'select-icon',
                    'icon' => yz_icon_svg([
                        'glyph' => 'compass',
                        'appearance' => 'duotone'
                    ])
                ]);
            }]
        ]]);
    }]); ?>
    <script>
        yz.setIconSet('<?= $appearance ?>',  <?= json_encode($icons) ?>);

        yz.ready().then(() => {
            const dialog = document.getElementById('<?= $id . '_dialog' ?>');
            const selectIconButton = document.querySelector('#<?= $id ?> + .flex-layout .select-icon');

            selectIconButton.addEventListener('click', (event) => {
                event.preventDefault();
                yz.openDialog(dialog, { modal: true });
            });
        });
    </script>
    <?php yz_dialog([
        'fixed' => true,
        'id' => $id . '_dialog',
        'class_name' => 'yuzu icon-picker-dialog',
        'title' => 'Select an icon',
        'content' => function() use($id, $appearance, $icons) {
            yz_container(['variant' => 'aside', 'class_name' => 'icon-picker-toolbar', 'content' => function() use($id) {
                yz_input([
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
                    const dialog = document.getElementById('<?= $id . '_dialog' ?>');
                    const searchInput = document.getElementById('<?= $id . '_dialog_search' ?>');
                    const gridLayout = document.getElementById('<?= $id . '_dialog_layout' ?>');
                    const submitButton = document.getElementById('<?= $id . '_submit' ?>');
                    const cancelButton = document.getElementById('<?= $id . '_cancel' ?>');

                    dialog.addEventListener('open', () => {
                        const icons = yz.getIconSet('<?= $appearance ?>');

                        dialog.scrollTop = 0;
                        searchInput.value = '';
                        gridLayout.innerHTML = '';
                        submitButton.disabled = true;

                        for (const [glyph, url] of Object.entries(icons)) {
                            const label = document.createElement('label');
                            label.htmlFor = '<?= $id ?>_option_' + glyph;
                            label.className = 'yuzu icon-picker-option';
                            label.dataset.glyph = glyph;
                            label.dataset.iconUrl = url;

                            const image = document.createElement('img');
                            image.className = 'yuzu icon-picker-image';
                            image.src = url;

                            const radio = document.createElement('input');
                            radio.id = '<?= $id ?>_option_' + glyph;
                            radio.className = 'yuzu icon-picker-radio';
                            radio.type = 'radio';
                            radio.name = '<?= $id ?>_option';
                            radio.value = glyph;

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
                            const dialogCard = dialog.querySelector('.yuzu.card');
                            dialogCard.style.minWidth = dialog.offsetWidth + 'px';
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
                        const selectedGlyph = document.querySelector('input[name="<?= $id ?>_option"]:checked').value;
                        const selectedIcon = document.querySelector('label[for="<?= $id ?>_option_' + selectedGlyph + '"]');
                        const iconPickerInput = document.getElementById('<?= $id ?>');
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
            yz_button(['id' => $id . '_cancel', 'label' => 'Cancel']);
            yz_button(['id' => $id . '_submit', 'disabled' => true, 'variant' => 'primary', 'label' => 'Use icon']);
        }
    ]);
}