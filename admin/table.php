<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Yuzu_Admin_Query_Table extends WP_List_Table {
    private WP_Query $query;
    private array $table_options;

    public function __construct(array $query_vars, array $table_options = []) {
        $this->query = new WP_Query($query_vars);
        $this->table_options = $table_options;

        parent::__construct([
            'singular' => $table_options['singular'] ?? $query_vars['post_type'],
            'plural' => $table_options['plural'] ?? $query_vars['post_type'] . 's',
            'ajax' => $table_options['ajax'] ?? false,
        ]);
    }

    public function prepare_items() {
        $this->_column_headers = $this->get_column_info();

//        $this->process_bulk_action();

        $per_page = $this->get_items_per_page( $this->query->query_vars['post_type'] . '_per_page', 5);
        $total_items = $this->query->post_count;

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = array_map(function(WP_Post $post) {
            return [
                'id' => $post->ID,
                'name' => $post->post_title,
                'address' => $post->post_content,
                'city' => $post->post_date
            ];
        }, $this->query->get_posts());
    }

    function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => __( 'Name', 'sp' ),
            'address' => __( 'Address', 'sp' ),
            'city' => __( 'City', 'sp' )
        ];
    }

    public function column_default($item, $column_name) {
        return $item[$column_name];
    }
}