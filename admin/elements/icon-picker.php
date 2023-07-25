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

    $icon_urls = array_map(function($icon) use($appearance) {
        return yz_icon_url([
            'glyph' => str_replace('.svg', '', $icon),
            'appearance' => $appearance
        ]);
    }, $icon_glyphs);

    yz_container(['class_name' => $class, 'content' => function() use($id, $name, $appearance, $icon_urls) {
        yz_input(['id' => $id, 'name' => $name, 'hidden' => true]);
        yz_flex_layout(['inline' => true, 'alignment' => 'stretch', 'items' => [
            ['content' => function() use($appearance) {
                yz_icon([
                    'class_name' => 'selected-icon',
                    'glyph' => 'crown-simple',
                    'appearance' => $appearance
                ]);
            }],
            ['content' => function() {
                yz_button([
                    'class_name' => 'select-icon',
                    'icon' => yz_icon_svg([
                        'glyph' => 'images-square',
                        'appearance' => 'duotone'
                    ])
                ]);
            }]
        ]]);
    }]);

    yz_dialog([
        'open' => true,
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
                'gap' => 15,
                'columns' => 10,
                'items' => []
            ]); ?>
            <script>
                globalThis.phosphorIcons ??= {};
                globalThis.phosphorIcons.<?= $appearance ?> ??= <?= json_encode($icons) ?>;
                globalThis.debounce ??= function debounce(fn, delay = 250) {
                    let timeout;

                    return (...args) => {
                        clearTimeout(timeout)
                        timeout = setTimeout(() => fn(...args), delay);
                    };
                }

                window.addEventListener('load', () => {
                    const dialog = document.getElementById('<?= $id . '_dialog' ?>');
                    const searchInput = document.getElementById('<?= $id . '_dialog_search' ?>');
                    const gridLayout = dialog.querySelector('.yuzu.grid-layout');
                    const icons = globalThis.phosphorIcons['<?= $appearance ?>'];

                    searchInput.addEventListener('input', window.debounce(() => {
                        const query = searchInput.value.toLowerCase();

                        for (const icon of gridLayout.children) {
                            if (icon.dataset.glyph.includes(query)) {
                                icon.style.display = 'unset';
                            } else {
                                icon.style.display = 'none';
                            }
                        }
                    }));

                    for (const [glyph, url] of Object.entries(icons)) {
                        const icon = document.createElement('label');
                        icon.htmlFor = '<?= $id ?>_option_' + glyph;
                        icon.className = 'yuzu icon-picker-option';
                        icon.dataset.glyph = glyph;
                        icon.style.backgroundImage = `url(${url})`;

                        const radio = document.createElement('input');
                        radio.id = '<?= $id ?>_option_' + glyph;
                        radio.classList.add('icon-radio');
                        radio.type = 'radio';
                        radio.name = '<?= $id ?>_option';
                        radio.value = glyph;

                        icon.appendChild(radio);
                        gridLayout.appendChild(icon);
                    }

                    window.requestAnimationFrame(() => {
                        dialog.style.minWidth = dialog.offsetWidth + 'px';
                        dialog.style.minHeight = dialog.offsetHeight + 'px';
                    });
                });
            </script>
        <?php },
        'footer' => function() {
            yz_button(['label' => 'Cancel']);
            yz_button(['variant' => 'primary', 'label' => 'Use icon']);
        }
    ]);
}