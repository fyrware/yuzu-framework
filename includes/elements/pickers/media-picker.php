<?php

class Yz_Media_Picker {

    public static function render(array $props): void {
        $id = Yz_Array::value_or($props, 'id', Yz_Array::value_or($props, 'name'));
        $name = Yz_Array::value_or($props, 'name', $id);
        $class = Yz_Array::value_or($props, 'class');
        $label = Yz_Array::value_or($props, 'label');
        $description = Yz_Array::value_or($props, 'description');
        $value = Yz_Array::value_or($props, 'value');
        $preview = Yz_Array::value_or($props, 'preview');

        $classes = [
            'yz',
            'media-picker',
        ];

        if ($class) {
            $classes[] = $class;
        }

        wp_enqueue_media();

        Yz::Flex_Layout([
            'gap' => 5,
            'direction' => 'column',
            'children'  => function() use($id, $name, $value, $label, $preview, $classes) {
                if ($label) {
                    Yz::Text($label, [ 'variant' => 'label' ]);
                }
                Yz::Element('div', [
                    'class' => Yz_Array::join($classes),
                    'children' => function() use($id, $name, $value, $label, $preview) {
                        Yz::Input([
                            'class'  => 'yz media-picker-input',
                            'hidden' => true,
                            'id'     => $id,
                            'name'   => $name,
                            'value'  => $value,
                        ]);
                        Yz::Element('div', [
                            'id' => $id . '_preview',
                            'class' => 'yz media-picker-preview',
                            'children' => function() use($value, $label, $preview) {
                                if ($value) {
                                    Yz::Image($value, ['alt' => $label, 'src' => $preview]);
                                } else {
                                    Yz::Element('div', [
                                        'class' => 'yz media-picker-placeholder',
                                        'children' => function() {
                                            Yz::Icon('park', ['appearance' => 'light']);
                                        }
                                    ]);
                                }
                            }
                        ]);
                        Yz::Flex_Layout([
                            'class' => 'yz media-picker-actions',
                            'children' => function() use($id) {
                                Yz::Button([
                                    'id'         => $id . '_select',
                                    'class' => 'yz select-media-button',
                                    'label'      => __('Select Media', 'yuzu')
                                ]);
                                Yz::Button([
                                    'id'    => $id . '_remove',
                                    'color' => 'danger',
                                    'class' => 'yz remove-media-button',
                                    'icon'  => 'x-circle'
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
            .yz.media-picker {
                display: flex;
                flex-direction: column;
                box-sizing: border-box;
                width: 162px;
                margin: 0;
                min-width: 0;
                position: relative;
            }

            .yz.media-picker-preview {
                cursor: pointer;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                width: 160px;
                height: 160px;
                border: 1px solid #8c8f94;
                border-bottom: none;
                border-radius: 4px 4px 0 0;
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

            .yz.media-picker-preview img {
                display: block;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .yz.media-picker-preview svg {
                display: block;
                width: 96px;
                height: 96px;
                fill: #c3c4c7;
            }

            .yz.media-picker-actions button {
                border-radius: 0 0 4px 4px;
            }

            .yz.media-picker-actions .yz.select-media-button {
                border-right: 0;
                border-bottom-right-radius: 0;
                justify-content: center;
                width: 100%;
            }

            .yz.media-picker-actions .yz.remove-media-button {
                border-bottom-left-radius: 0;
            }

            .media-modal-content {
                border-radius: 4px !important;
                box-shadow: none !important;
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                yz('.yz.media-picker').forEach(container => {
                    const mediaIdInput = yz('.yz.media-picker-input',   container).item()
                    const mediaPreview = yz('.yz.media-picker-preview', container).item();
                    const selectButton = yz('.yz.select-media-button',  container).item();
                    const removeButton = yz('.yz.remove-media-button',  container).item();

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
                        const placeholder         = yz('svg', mediaPreview).item();
                        const preview             = yz('img', mediaPreview).item() ?? document.createElement('img');
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

                    yz.spy(mediaFrame, 'select').observe(selectMedia);

                    yz.spy(mediaPreview, 'click').observe((event) => {
                        event.preventDefault();
                        openMediaFrame();
                    });

                    yz.spy(selectButton, 'click').observe((event) => {
                        event.preventDefault();
                        openMediaFrame();
                    });

                    yz.spy(removeButton, 'click').observe((event) => {
                        event.preventDefault();
                        clearMedia();
                    });

                    yz.spy(document, 'ajaxComplete').observe((event, response, options) => {
                        if (response.status === 200 && options.data.includes('add')) {
                            clearMedia();
                        }
                    });
                });
            });
        </script>
    <?php }
}