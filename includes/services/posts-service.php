<?php

class Yz_Posts_Service {

    public function register_post_type(string $post_type_name, array $props): WP_Post_Type {
        global $yz;

        $singular_name = $yz->tools->get_value($props, 'singular_name', $post_type_name);
        $plural_name   = $yz->tools->get_value($props, 'plural_name', $post_type_name);
        $description   = $yz->tools->get_value($props, 'description', '');
        $slug          = $yz->tools->get_value($props, 'slug', $post_type_name);

        $post_type = register_post_type($post_type_name, [
            'labels' => [
                'name'                  => $plural_name,
                'singular_name'         => $singular_name,
                'add_new'               => __('Add New', YZ),
                'add_new_item'          => __('Add New ' . $singular_name, YZ),
                'edit_item'             => __('Edit ' . $singular_name, YZ),
                'new_item'              => __('New ' . $singular_name, YZ),
                'view_item'             => __('View ' . $singular_name, YZ),
                'view_items'            => __('View ' . $plural_name, YZ),
                'search_items'          => __('Search ' . $plural_name, YZ),
                'not_found'             => __('No ' . $plural_name . ' found', YZ),
                'not_found_in_trash'    => __('No ' . $plural_name . ' found in Trash', YZ),
                'parent_item_colon'     => __('Parent ' . $singular_name . ':', YZ),
                'all_items'             => __('All ' . $plural_name, YZ),
                'archives'              => __('' . $singular_name . ' Archives', YZ),
                'attributes'            => __('' . $singular_name . ' Attributes', YZ),
                'insert_into_item'      => __('Insert into ' . $singular_name, YZ),
                'uploaded_to_this_item' => __('Uploaded to this ' . $singular_name, YZ),
            ],
            'description'           => $description,
            'rewrite'               => ['slug' => $slug],
            'supports'              => ['title', 'thumbnail'],
            'taxonomies'            => [],
            'capability_type'       => $post_type_name,
            'map_meta_cap'          => true,
            'menu_position'         => 1,
            'menu_icon'             => 'dashicons-groups',
            'has_archive'           => false,
            'public'                => true,
            'publicly_queryable'    => false,
            'exclude_from_search'   => true,
            'delete_with_user'      => true,
            'show_ui'               => false,
            'show_in_menu'          => false,
            'show_in_nav_menus'     => false,
            'show_in_rest'          => false,
            'rest_base'             => Yz_String::format_slug($plural_name),
            'rest_controller_class' => WP_REST_Posts_Controller::class
        ]);

        foreach ($post_type->cap as $capability) {
            get_role('administrator')->add_cap($capability);
        }

        return $post_type;
    }

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