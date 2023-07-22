<?php

function yz_media_picker(array $props): void {
    wp_enqueue_media();

    $id = array_key_exists('id', $props) ? $props['id'] : null;
    $name = array_key_exists('name', $props) ? $props['name'] : $id;
    $label = array_key_exists('label', $props) ? $props['label'] : 'Form Field';
    $description = array_key_exists('description', $props) ? $props['description'] : null;
    $value = array_key_exists('value', $props) ? $props['value'] : '';
    $preview = array_key_exists('preview', $props) ? $props['preview'] : null;
    $display_as = array_key_exists('display_as', $props) ? $props['display_as'] : 'default';

    assert(isset($id), 'Form field must have an id');

    $wrapper_tag = $display_as === 'default' ? 'div' : 'tr';
    $label_tag = $display_as === 'default' ? 'div' : 'th';
    $contents_tag = $display_as === 'default' ? 'div' : 'td'; ?>

    <<?php echo $wrapper_tag; ?> class="yuzu form-field">
        <<?php echo $label_tag; ?> scope="row">
            <label for="<?php echo $id; ?>">
                <?php echo $label; ?>
            </label>
        </<?php echo $label_tag; ?>>
        <<?php echo $contents_tag; ?>>
            <?php if (isset($description)) { ?>
                <p class="description">
                    <?php echo $description; ?>
                </p>
            <?php } ?>
            <div id="<?php echo $name; ?>_preview">
                <?php if (!empty($preview)) { ?>
                    <img id="<?php echo $name; ?>_preview" src="<?php echo esc_url($preview); ?>" alt="<?php echo $label; ?>" width="160">
                <?php } else { ?>
                    <img id="<?php echo $name; ?>_preview" src="<?php echo esc_url(wc_placeholder_img_src()); ?>" width="160" alt="No image"/>
                <?php } ?>
            </div>
            <div>
                <input type="hidden" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
                <button id="logo_upload" type="button" class="upload_image_button button">
                    <?php esc_html_e( 'Upload/Add image', 'yuzu' ); ?>
                </button>
                <button id="logo_remove" type="button" class="remove_image_button button">
                    <?php esc_html_e( 'Remove image', 'yuzu' ); ?>
                </button>
            </div>
            <script>
                const mediaIdInput = document.getElementById('<?php echo $id; ?>');
                const mediaPreview = document.getElementById('<?php echo $name; ?>_preview').querySelector('img');
                const uploadButton = document.getElementById('logo_upload');
                const removeButton = document.getElementById('logo_remove');

                uploadButton.addEventListener('click', (event) => {
                    event.preventDefault();

                    if (wp.media.frames.downloadable_file) {
                        wp.media.frames.downloadable_file.open();
                        return;
                    }

                    const mediaFrame = wp.media.frames.file_frame = wp.media({
                        title: '<?php echo __('Choose an image', 'yuzu') ?>',
                        button: {
                            text: '<?php echo __('Use image', 'yuzu') ?>',
                        },
                        multiple: false
                    });

                    mediaFrame.on('select', () => {
                        const attachment = mediaFrame.state().get('selection').first().toJSON();
                        const attachmentThumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                        mediaIdInput.value = attachment.id;
                        mediaPreview.src = attachmentThumbnail.url;
                        removeButton.hidden = false;
                    });

                    mediaFrame.open();
                });

                removeButton.addEventListener('click', (event) => {
                    event.preventDefault();

                    mediaIdInput.value = '';
                    mediaPreview.src = '<?php echo esc_url(wc_placeholder_img_src()); ?>';
                    removeButton.hidden = true;

                    return false;
                });

                <?php if (empty($value)) { ?>
                jQuery(document).ajaxComplete((event, response, options) => {
                    if (response.status === 200 && options.data.includes('add')) {
                        mediaIdInput.value = '';
                        mediaPreview.src = '<?php echo esc_url(wc_placeholder_img_src()); ?>';
                        removeButton.hidden = true;
                    }
                });
                <?php } ?>
            </script>
        </<?php echo $contents_tag; ?>>
    </<?php echo $wrapper_tag; ?>>
<?php }