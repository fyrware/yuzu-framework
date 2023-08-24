<?php

function yz_register_post_type(string $post_type_name, array $post_type_settings): WP_Post_Type {
    $singular_name = $post_type_settings['singular_name'];
    $plural_name   = $post_type_settings['plural_name'];
    $description   = $post_type_settings['description'];
    $slug          = $post_type_settings['slug'];
    
    $post_type = register_post_type($post_type_name, [
        'labels' => [
            'name'                  => $plural_name,
            'singular_name'         => $singular_name,
            'add_new'               => __('Add New', YUZU),
            'add_new_item'          => __('Add New ' . $singular_name, YUZU),
            'edit_item'             => __('Edit ' . $singular_name, YUZU),
            'new_item'              => __('New ' . $singular_name, YUZU),
            'view_item'             => __('View ' . $singular_name, YUZU),
            'view_items'            => __('View ' . $plural_name, YUZU),
            'search_items'          => __('Search ' . $plural_name, YUZU),
            'not_found'             => __('No ' . $plural_name . ' found', YUZU),
            'not_found_in_trash'    => __('No ' . $plural_name . ' found in Trash', YUZU),
            'parent_item_colon'     => __('Parent ' . $singular_name . ':', YUZU),
            'all_items'             => __('All ' . $plural_name, YUZU),
            'archives'              => __('' . $singular_name . ' Archives', YUZU),
            'attributes'            => __('' . $singular_name . ' Attributes', YUZU),
            'insert_into_item'      => __('Insert into ' . $singular_name, YUZU),
            'uploaded_to_this_item' => __('Uploaded to this ' . $singular_name, YUZU),
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
        'rest_base'             => 'club-members',
        'rest_controller_class' => WP_REST_Posts_Controller::class,
    ]);

    foreach ($post_type->cap as $capability) {
        get_role('administrator')->add_cap($capability);
    }

    return $post_type;
}