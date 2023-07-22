<?php

function yz_color_picker(array $props): void {
    $id = array_key_exists('id', $props) ? $props['id'] : null;
    $name = array_key_exists('name', $props) ? $props['name'] : $id;
    $label = array_key_exists('label', $props) ? $props['label'] : 'Form Field';
    $description = array_key_exists('description', $props) ? $props['description'] : null;
    $value = array_key_exists('value', $props) ? $props['value'] : '';
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