<?php

class Yz_Forum_Service {

    public function add_configuration(array $options): void {
        global $yz;

        $archive_title  = $yz->tools->get_value($options, 'archive_title');
        $freshness_link = $yz->tools->get_value($options, 'freshness_link');

        if (isset($archive_title)) {
            add_filter('bbp_get_forum_archive_title', $archive_title);
        }

        if (isset($freshness_link)) {
            add_filter('bbp_get_forum_freshness_link', $freshness_link);
        }
    }

    public function add_admin_field(string $name, string $label): void {
        add_action('bbp_forum_metabox', function() use($name, $label) { ?>
            <p>
                <strong class="label"><?= $label ?>:</strong>
                <label class="screen-reader-text" for="<?= $name ?>"><?= $label ?></label>
                <input name="<?= $name ?>" id="<?= $name ?>" type="text" value="<?= esc_html(get_post_meta(get_the_ID(), $name, true)) ?>">
            </p>
        <?php });
        add_action('bbp_forum_attributes_metabox_save', function(int $forum_id) use($name) {
            update_post_meta($forum_id, $name, $_POST[$name]);
        });
    }
}