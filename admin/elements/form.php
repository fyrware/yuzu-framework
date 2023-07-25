<?php

function yz_form(array $props): void {
    $class_names = [
        'yuzu',
        'form'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <form
        class="<?= trim(implode(' ', $class_names)) ?>"
        method="<?= $props['method'] ?? 'post' ?>"
        <?php if (isset($props['action'])) { ?>
            action="<?= esc_url(admin_url('admin-post.php')) ?>"
        <?php } ?>
    >

        <?php if (isset($props['action'])) { ?>
            <input type="hidden" name="action" value="<?= $props['action'] ?>"/>
            <?php wp_nonce_field($props['action'], 'nonce') ?>
        <?php } ?>

        <?php if (isset($props['content'])) {
            $props['content']();
        } ?>

        <?php if (isset($props['fields'])) foreach ($props['fields'] as $field) { ?>
            <div class="form-group">
                <label for="<?= $field['id'] ?? '' ?>">
                    <?= $field['label'] ?>
                </label>
                <?= $field['content']($field['id']) ?>
            </div>
        <?php } ?>

    </form>
<?php }