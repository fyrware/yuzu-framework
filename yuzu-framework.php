<?php
/**
 * Plugin Name: Yuzu Framework
 * Plugin URI: https://fyrware.com/
 * Description: Quickly and elegantly build WordPress plugins
 * Author: Fyrware
 * Version: 0.0.1
 * Author URI: https://fyrware.com
 * Text Domain: yuzu
 */

/**
 * I am hoping and praying that this constant never becomes useful.
 */
const YUZU_VERSION = '0.0.1';

function is_yuzu() {
    return true;
}

function yuzu_admin_enqueue_media() {
    wp_enqueue_media();
    wp_enqueue_global_styles_css_custom_properties();
}

add_action('admin_enqueue_scripts', 'yuzu_admin_enqueue_media');

function yuzu_init() {
    if (is_admin()) {
        require_once plugin_dir_path(__FILE__) . 'admin/menu.php';
    }
}

add_action('init', 'yuzu_init', 0);

/**
 * Return an SVG icon from the Phosphor icon set (phosphoricons.com)
 * @return string
 */
function yuzu_icon(string $icon_name, string $icon_appearance = 'regular'): string {
    return file_get_contents(plugin_dir_path(__FILE__) . "icons/assets/$icon_appearance/$icon_name-$icon_appearance.svg");
}

/**
 * Render (echo) an SVG icon from the Phosphor icon set (phosphoricons.com)
 * @return void
 */
function render_yuzu_icon(string $icon_name, string $icon_appearance = 'regular'): void {
    echo yuzu_icon($icon_name, $icon_appearance);
}


































/**
 * Render a WordPress admin-panel horizontal divider
 * @return void
 */
function yuzu_render_admin_hr() { ?>
    <hr class="yuzu horizontal divider"/>
<?php }

/**
 * Render a WordPress admin-panel vertical divider
 * @return void
 */
function yuzu_render_admin_vr() { ?>
    <hr class="yuzu vertical divider"/>
<?php }

/**
 * Render a WordPress color input field.
 * Available Args:
 * - `string` id
 * - `string` name
 * - `string` label
 * - `string` description
 * - `string` value
 * - `string` display_as
 * @param array $args
 * @return void
 */
function yuzu_render_admin_color_field(array $args): void {
    $id = array_key_exists('id', $args) ? $args['id'] : null;
    $name = array_key_exists('name', $args) ? $args['name'] : $id;
    $label = array_key_exists('label', $args) ? $args['label'] : 'Form Field';
    $description = array_key_exists('description', $args) ? $args['description'] : null;
    $value = array_key_exists('value', $args) ? $args['value'] : '';
    $display_as = array_key_exists('display_as', $args) ? $args['display_as'] : 'default';

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
            <input type="text" id="<?= $id ?>" name="<?= $name ?>" value="<?= $value ?>" data-coloris/>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
            <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
            <?php if (isset($description)) { ?>
                <p class="description">
                    <?php echo $description; ?>
                </p>
            <?php } ?>
        </<?php echo $contents_tag; ?>>
    </<?php echo $wrapper_tag; ?>>
<?php }

/**
 * Render a WordPress media picker button & modal.
 * Available Args:
 * - `string` id
 * - `string` name
 * - `string` label
 * - `string` description
 * - `string` value
 * - `string` preview
 * - `string` display_as (`default|table`)
 * @param array $args
 * @return void
 */
function yuzu_render_admin_media_field(array $args): void {
    $id = array_key_exists('id', $args) ? $args['id'] : null;
    $name = array_key_exists('name', $args) ? $args['name'] : $id;
    $label = array_key_exists('label', $args) ? $args['label'] : 'Form Field';
    $description = array_key_exists('description', $args) ? $args['description'] : null;
    $value = array_key_exists('value', $args) ? $args['value'] : '';
    $preview = array_key_exists('preview', $args) ? $args['preview'] : null;
    $display_as = array_key_exists('display_as', $args) ? $args['display_as'] : 'default';

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
