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

    yz_container(['class_name' => $class, 'content' => function() use($id, $name, $appearance) {
        yz_input(['id' => $id, 'name' => $name, 'hidden' => true]);
        yz_flex_layout(['inline' => true, 'alignment' => 'stretch', 'items' => [
            ['content' => function() use($appearance) { ?>
                <img
                    alt="selected icon"
                    class="yuzu selected-icon"
                    src="<?= yz_icon_url(['glyph' => 'question', 'appearance' => $appearance]) ?>"
                />
            <?php }],
            ['content' => function() {
                yz_button([
                    'class_name' => 'select-icon',
                    'icon' => yz_icon_svg([
                        'glyph' => 'hand-tap',
                        'appearance' => 'duotone'
                    ])
                ]);
            }]
        ]]);
    }]); ?>
    <script>
        globalThis.addEventListener('load', () => {
            const dialog = document.getElementById('<?= $id . '_dialog' ?>');
            const selectIconButton = document.querySelector('#<?= $id ?> + .flex-layout .select-icon');

            selectIconButton.addEventListener('click', (event) => {
                event.preventDefault();
                dialog.showModal();
                dialog.dispatchEvent(new Event('open'));
            });
        });
    </script>
    <?php yz_dialog([
        'fixed' => true,
        'id' => $id . '_dialog',
        'class_name' => 'yuzu icon-picker-dialog',
        'title' => 'Select an icon',
        'content' => function() use($id, $appearance, $icons) { ?>
            <aside class="yuzu icon-picker-toolbar">
                <?php yz_input([
                    'id' => $id . '_dialog_search',
                    'placeholder' => 'ex: arrow'
                ]) ?>
            </aside>
            <?php yz_grid_layout([
                'id' => $id . '_dialog_layout',
                'gap' => 15,
                'columns' => 10
            ]); ?>
            <script>
                globalThis.phosphorIcons ??= {};
                globalThis.phosphorIcons.<?= $appearance ?> ??= <?= json_encode($icons) ?>;

                globalThis.addEventListener('load', () => {
                    const icons = globalThis.phosphorIcons.<?= $appearance ?>;
                    const dialog = document.getElementById('<?= $id . '_dialog' ?>');
                    const searchInput = document.getElementById('<?= $id . '_dialog_search' ?>');
                    const gridLayout = document.getElementById('<?= $id . '_dialog_layout' ?>');
                    const submitButton = document.getElementById('<?= $id . '_submit' ?>');
                    const cancelButton = document.getElementById('<?= $id . '_cancel' ?>');

                    dialog.addEventListener('open', () => {
                        for (const [glyph, url] of Object.entries(icons)) {
                            const icon = document.createElement('label');
                            icon.htmlFor = '<?= $id ?>_option_' + glyph;
                            icon.className = 'yuzu icon-picker-option';
                            icon.dataset.glyph = glyph;
                            icon.dataset.iconUrl = url;

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

                            icon.appendChild(image);
                            icon.appendChild(radio);
                            gridLayout.appendChild(icon);
                        }
                    });

                    searchInput.addEventListener('input', yz.debounce(() => {
                        const query = searchInput.value.toLowerCase();

                        for (const icon of gridLayout.children) {
                            if (icon.dataset.glyph.includes(query)) {
                                icon.style.display = 'unset';
                            } else {
                                icon.style.display = 'none';
                            }
                        }
                    }));

                    dialog.querySelector('form').addEventListener('submit', (event) => {
                        const selectedGlyph = document.querySelector('input[name="<?= $id ?>_option"]:checked').value;
                        const selectedIcon = document.querySelector('label[for="<?= $id ?>_option_' + selectedGlyph + '"]');
                        const iconPickerInput = document.getElementById('<?= $id ?>');
                        const selectedIconImage = document.querySelector('#<?= $id ?> + .flex-layout .selected-icon');

                        iconPickerInput.value = selectedIcon.dataset.glyph;
                        selectedIconImage.src = selectedIcon.dataset.iconUrl;

                        event.preventDefault();
                        dialog.close();
                    });

                    submitButton.addEventListener('click', (event) => {
                        const selectedGlyph = document.querySelector('input[name="<?= $id ?>_option"]:checked').value;
                        const selectedIcon = document.querySelector('label[for="<?= $id ?>_option_' + selectedGlyph + '"]');
                        const iconPickerInput = document.getElementById('<?= $id ?>');
                        const selectedIconImage = document.querySelector('#<?= $id ?> + .flex-layout .selected-icon');

                        iconPickerInput.value = selectedIcon.dataset.glyph;
                        selectedIconImage.src = selectedIcon.dataset.iconUrl;

                        event.preventDefault();
                        dialog.close();
                    });

                    cancelButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        dialog.close();
                    });

                    globalThis.requestAnimationFrame(() => {
                        dialog.style.minWidth = dialog.offsetWidth + 'px';
                        dialog.style.minHeight = dialog.offsetHeight + 'px';
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