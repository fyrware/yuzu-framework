<?php

class Yz_Posts_Service {

    public function get_posts(array $query_options): array {
        $query = new WP_Query($query_options);
        return $query->get_posts();
    }

    public function get_post_meta(int $post_id, string $key, bool $single = true) {
        return get_post_meta($post_id, $key, $single);
    }

    public function create_post(array $post_options): int {
        $post_type    = Yz::use_prop($post_options, 'post_type', 'post');
        $post_title   = Yz::use_prop($post_options, 'post_title', '');
        $post_content = Yz::use_prop($post_options, 'post_content', '');
        $post_status  = Yz::use_prop($post_options, 'post_status', 'publish');
        $post_image   = Yz::use_prop($post_options, 'post_image');
        $post_meta    = Yz::use_prop($post_options, 'post_meta', []);

        $post_id = wp_insert_post([
            'post_type'    => $post_type,
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => $post_status
        ]);

        if (isset($post_image)) $this->update_thumbnail($post_id, $post_image);

        foreach ($post_meta as $key => $value) {
            $this->update_meta($post_id, $key, $value);
        }

        return $post_id;
    }

    public function update_post(int $post_id, array $post_options): void {
        $post_type    = Yz::use_prop($post_options, 'post_type', 'post');
        $post_title   = Yz::use_prop($post_options, 'post_title', '');
        $post_content = Yz::use_prop($post_options, 'post_content', '');
        $post_status  = Yz::use_prop($post_options, 'post_status', 'publish');
        $post_image   = Yz::use_prop($post_options, 'post_image');
        $post_meta    = Yz::use_prop($post_options, 'post_meta', []);

        wp_update_post([
            'ID'           => $post_id,
            'post_type'    => $post_type,
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => $post_status
        ]);

        if (isset($post_image)) $this->update_thumbnail($post_id, $post_image);

        foreach ($post_meta as $key => $value) {
            $this->update_meta($post_id, $key, $value);
        }
    }

    public function update_thumbnail(int $post_id, int $thumbnail_id): void {
        set_post_thumbnail($post_id, $thumbnail_id);
    }

    public function update_meta(int $post_id, string $key, $value): void {
        update_post_meta($post_id, $key, $value);
    }

    /** @deprecated */
    public static function s_get_posts(array $query_options): array {
        $query = new WP_Query($query_options);
        return $query->get_posts();
    }

    /** @deprecated */
    public static function s_get_post_meta(int $post_id, string $key, bool $single = true) {
        return get_post_meta($post_id, $key, $single);
    }

    /** @deprecated */
    public static function write_post(array $post_options): int {
        $post_type = Yz::use_prop($post_options, 'post_type', 'post');
        $post_title = Yz::use_prop($post_options, 'post_title', '');
        $post_content = Yz::use_prop($post_options, 'post_content', '');
        $post_status = Yz::use_prop($post_options, 'post_status', 'publish');
        $post_image = Yz::use_prop($post_options, 'post_image');
        $post_meta = Yz::use_prop($post_options, 'post_meta', []);

        $post_id = wp_insert_post([
            'post_type'    => $post_type,
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => $post_status
        ]);

        if (isset($post_image)) set_post_thumbnail($post_id, $post_image);

        foreach ($post_meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }

        return $post_id;
    }
}