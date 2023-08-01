<?php

function yz_media_picker(array $props): void {
    $id          = yz_prop($props, 'id', yz_prop($props, 'name'));
    $name        = yz_prop($props, 'name', $id);
    $label       = yz_prop($props, 'label');
    $description = yz_prop($props, 'description');
    $value       = yz_prop($props, 'value');
    $preview     = yz_prop($props, 'preview');

    $class_names = [
        'yuzu',
        'media-picker',
    ];

    if (isset($props['class'])) {
        $class_names[] = $props['class'];
    }

    wp_enqueue_media();
    yz_element(['class' => trim(implode(' ', $class_names)), 'children' => function() use($id, $name, $value, $label, $preview) {
        yz_input([
            'hidden' => true,
            'id'     => $id,
            'name'   => $name,
            'value'  => $value,
        ]);
        yz_element(['id' => $id . '_preview', 'class' => 'yuzu media-picker-preview', 'children' => function() use($value, $label, $preview) {
            if ($value) {
                yz_image($value, ['alt' => $label, 'src' => $preview]);
            } else {
                yz_element(['class' => 'yuzu media-picker-placeholder', 'children' => function() {
                    yz_icon(['glyph' => 'park', 'appearance' => 'light']);
                }]);
            }
        }]);
        yz_flex_layout(['class' => 'yuzu media-picker-actions', 'items' => [
            ['grow' => true, 'children' => function() use($id) {
                yz_button([
                    'id'         => $id . '_select',
                    'class' => 'yuzu select-media-button',
                    'label'      => __('Select Media', 'yuzu')
                ]);
            }],
            ['children' => function() use($id) {
                yz_button([
                    'id'         => $id . '_remove',
                    'color'      => 'danger',
                    'class' => 'yuzu remove-media-button',
                    'icon'       => yz_icon_svg(['glyph' => 'x-circle', 'appearance' => 'duotone'])
                ]);
            }]
        ]]);
    }]); ?>

    <script>
        yz.ready().then(() => {
            const mediaIdInput = document.getElementById('<?= $id ?>');
            const mediaPreview = document.getElementById('<?= $id ?>_preview');
            const uploadButton = document.getElementById('<?= $id ?>_select');
            const removeButton = document.getElementById('<?= $id ?>_remove');

            const mediaFrame = wp.media.frames.file_frame = wp.media({
                title: '<?= __('Choose an image', 'yuzu') ?>',
                button: {
                    text: '<?= __('Use image', 'yuzu') ?>',
                },
                multiple: false
            });

            const openMediaFrame = () => {
                if (wp.media.frames.downloadable_file) {
                    wp.media.frames.downloadable_file.open();
                } else {
                    mediaFrame.open();
                }
            }

            const selectMedia = () => {
                const placeholder         = mediaPreview.querySelector('svg');
                const preview             = mediaPreview.querySelector('img') ?? document.createElement('img');
                const attachment          = mediaFrame.state().get('selection').first().toJSON();
                const attachmentThumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                if (preview.parentElement !== mediaPreview) {
                    mediaPreview.appendChild(preview);
                }

                placeholder.style.display = 'none';
                mediaIdInput.value        = attachment.id;
                preview.src               = attachmentThumbnail.url;
            }

            const clearMedia = () => {
                const placeholder = mediaPreview.querySelector('svg');
                const preview     = mediaPreview.querySelector('img');

                if (preview) {
                    preview.remove();
                }

                placeholder.style.display = 'block';
                mediaIdInput.value        = '';
            }

            mediaPreview.addEventListener('click', (event) => {
                event.preventDefault();
                openMediaFrame();
            });

            uploadButton.addEventListener('click', (event) => {
                event.preventDefault();
                openMediaFrame();
            });

            removeButton.addEventListener('click', (event) => {
                event.preventDefault();
                clearMedia();
            });

            mediaFrame.on('select', () => {
                selectMedia();
            });

            <?php if (empty($value)) { ?>
                jQuery(document).ajaxComplete((event, response, options) => {
                    if (response.status === 200 && options.data.includes('add')) {
                        clearMedia();
                    }
                });
            <?php } ?>
        });
    </script>
<?php }