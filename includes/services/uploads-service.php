<?php

class Yz_Uploads_Scanned_Dir {

    private string $path;
    private array $folders;
    private array $files;

    public function get_path(): string {
        return $this->path;
    }

    /**
     * @return string[]
     */
    public function get_folders(): array {
        return array_values($this->folders);
    }

    /**
     * @return Yz_Uploads_File[]
     */
    public function get_files(): array {
        return $this->files;
    }

    public function __construct(string $path, array $folders, array $files) {
        $this->path = $path;
        $this->folders = $folders;
        $this->files = $files;
    }

    public function to_array(): array {
        return [
            'folders' => $this->get_folders(),
            'files'   => array_map(fn($file) => $file->to_array(), $this->get_files())
        ];
    }
}

class Yz_Uploads_Media_Pagination {

    private int $total_uploads = 0;
    private int $uploads_per_page = 20;
    private int $current_page = 1;

    /** @var Yz_Uploads_File[] */
    private array $files = [];

    public function get_total_upload_count(): int {
        return $this->total_uploads;
    }

    public function get_page_count(): int {
        return ceil($this->total_uploads / $this->uploads_per_page);
    }

    public function get_page_number(): int {
        return $this->current_page;
    }

    public function has_next_page(): bool {
        return $this->current_page * $this->uploads_per_page < $this->total_uploads;
    }

    /** @return Yz_Uploads_File[] */
    public function get_files(): array {
        return $this->files;
    }

    public function __construct(int $total_uploads, int $uploads_per_page, int $current_page, array $files = []) {
        $this->total_uploads = $total_uploads;
        $this->uploads_per_page = $uploads_per_page;
        $this->current_page = $current_page;
        $this->files = $files;
    }

    public function to_array(): array {
        return [
            'total_uploads'    => $this->get_total_upload_count(),
            'uploads_per_page' => $this->uploads_per_page,
            'current_page'     => $this->current_page,
            'page_count'       => $this->get_page_count(),
            'has_next_page'    => $this->has_next_page(),
            'files'            => array_map(fn($file) => $file->to_array(), $this->get_files())
        ];
    }
}

class Yz_Uploads_File {

    public const UPLOAD_LOCATION = 'yz_upload_location';
    public const ATTACHED_FILE   = '_wp_attached_file';

    private Wp_Post $post;

    public function get_id(): int {
        return $this->post->ID;
    }

    public function get_title(): string {
        return $this->post->post_title;
    }

    public function get_url(): string {
        global $yz;

        $uploads_dir = wp_get_upload_dir();
        $uploads_dir_url = $uploads_dir['baseurl'];
        $relative_location = $yz->posts->get_metadata($this->post->ID, static::ATTACHED_FILE);

        return $uploads_dir_url . '/' . $relative_location;
    }

    public function get_path(): string {
        return get_attached_file($this->post->ID) ?: '';
    }

    public function get_thumbnail_url(): string {
        return wp_get_attachment_image_src($this->post->ID)[0] ?: '';
    }

    public function get_upload_date(): string {
        return $this->post->post_date;
    }

    public function get_extension(): string {
        return pathinfo($this->get_url(), PATHINFO_EXTENSION);
    }

    public function get_mime_type(): string {
        return $this->post->post_mime_type;
    }

    public function get_width(): ?int {
        return yz()->tools->get_value(wp_get_attachment_metadata($this->post->ID), 'width');
    }

    public function get_height(): ?int {
        return yz()->tools->get_value(wp_get_attachment_metadata($this->post->ID), 'height');
    }

    public function get_file_size(): int {
        $meta = wp_get_attachment_metadata($this->post->ID);
        return $meta['filesize'];
    }

    public function get_upload_location(): string {
        global $yz;

        return $yz->posts->get_metadata($this->post->ID, static::UPLOAD_LOCATION);
    }

    public function __construct(Wp_Post $post) {
        $this->post = $post;
    }

    public function to_array(): array {
        return [
            'id'            => $this->get_id(),
            'title'         => $this->get_title(),
            'url'           => $this->get_url(),
            'thumbnail_url' => $this->get_thumbnail_url(),
            'upload_date'   => $this->get_upload_date(),
            'extension'     => $this->get_extension(),
            'mime_type'     => $this->get_mime_type(),
            'width'         => $this->get_width(),
            'height'        => $this->get_height(),
            'file_size'     => $this->get_file_size()
        ];
    }
}

class Yz_Uploads_Service {

    public const ATTACHMENT_POST_TYPE = 'attachment';

    public function resolve_path(...$paths): string {
        return yz()->tools->get_value(wp_upload_dir(), 'basedir') . '/' . implode('/', $paths);
    }

    public function resolve_url(...$paths): string {
        return yz()->tools->get_value(wp_upload_dir(), 'baseurl') . '/' . implode('/', $paths);
    }

    public function create_folder(string $path): void {
        $path = preg_replace('/[^a-zA-Z0-9\/-]/', '', $path);

        if (str_starts_with($path, '/')) {
            $path = substr($path, 1);
        }

        if (str_ends_with($path, '/')) {
            $path = substr($path, 0, -1);
        }

        if (!dir(wp_upload_dir()['basedir'] . '/' . $path)) {
            mkdir(wp_upload_dir()['basedir'] . '/' . $path);
        }
    }

    public function save_file_from_url(string $path, string $url, ?string $file_name = null): bool {
        $uploads_dir = wp_get_upload_dir();
        $uploads_dir_path = yz()->tools->get_value($uploads_dir, 'basedir');

        if (is_null($file_name)) {
            $file_name = basename($url);
        }

        $file_name_parts = yz()->tools->split_string($file_name, '.');
        $file_name = yz()->tools->get_first($file_name_parts);
        $file_extension = yz()->tools->get_last($file_name_parts);

        $upload_location = $uploads_dir_path . '/' . $path . '/' . $file_name . '.' . $file_extension;
        $download_file_response  = wp_remote_get($url);

        if (is_wp_error($download_file_response)) {
            return false;
        }

        $download_file_body = wp_remote_retrieve_body($download_file_response);
        $uploaded_file      = file_put_contents($upload_location, $download_file_body);

        if (!$uploaded_file) {
            return false;
        }

        return true;
    }

    public function save_file(string $path, array $file): void {
        $uploads_dir = wp_get_upload_dir();
        $uploads_dir_path = $uploads_dir['basedir'];

        $file_path = $uploads_dir_path . '/' . $path . $file['name'];

        if (file_exists($file_path)) {
            wp_send_json_error('File already exists');
        }

        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            wp_send_json_error('Failed to save file');
        }

        $attachment_id = wp_insert_attachment([
            'post_title'     => $file['name'],
            'post_content'   => '',
            'post_status'    => 'inherit',
            'post_mime_type' => $file['type']
        ], $file_path);

        if (is_wp_error($attachment_id)) {
            wp_send_json_error($attachment_id->get_error_message());
        }

        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file_path));

        wp_send_json_success([ 'id' => $attachment_id ]);
    }

    /**
     * @param int $page
     * @param int $per_page
     * @return Yz_Uploads_File[]
     */
    public function list_uploads(int $page = 1, int $per_page = 20): Yz_Uploads_Media_Pagination {
        wp_reset_postdata();
        wp_reset_query();

        $count = (int)wp_count_posts(static::ATTACHMENT_POST_TYPE)->inherit;

        $query = new WP_Query([
            'post_type'      => static::ATTACHMENT_POST_TYPE,
            'post_status'    => 'inherit',
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);

        return new Yz_Uploads_Media_Pagination($count, $per_page, $page, array_map(fn($post) => new Yz_Uploads_File($post), $query->get_posts()));
    }

    /**
     * @deprecated
     */
    private static function select_attachment_ids($paths): array {
        global $wpdb;

        $joined_paths = implode("', '", array_map('esc_sql', $paths));

        $query = "
            select p.ID
            from wp_posts p
            join wp_postmeta pm on p.ID = pm.post_id
            where pm.meta_key = '_wp_attached_file'
            and pm.meta_value in ('$joined_paths')
            and p.post_type = 'attachment';
        ";

        return $wpdb->get_col($query);
    }

    /**
     * @deprecated
     */
    public static function scan_uploads_dir(?string $path = null): Yz_Uploads_Scanned_Dir {
        $uploads_dir = wp_get_upload_dir();
        $uploads_dir_path = $uploads_dir['basedir'];

        if (isset($path)) {
            $uploads_dir_path .= '/' . $path;
        }

        $contents = array_filter(scandir($uploads_dir_path), fn($file) => !in_array($file, [ '.', '..' ]));
        $folders = array_filter($contents, fn($file) => is_dir($uploads_dir_path . '/' . $file));
        $files = array_filter($contents, fn($file) => is_file($uploads_dir_path . '/' . $file));

        $ids = count($files) > 0 ? static::select_attachment_ids(isset($path) ? array_map(fn($file) => $path . $file, $files) : $files) : [];

        $query = new WP_Query([
            'post_type'      => static::ATTACHMENT_POST_TYPE,
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'post__in'       => $ids
        ]);

        return new Yz_Uploads_Scanned_Dir(
            $uploads_dir_path,
            $folders,
            count($ids) > 0 ? array_map(fn($post) => new Yz_Uploads_File($post), $query->get_posts()) : []
        );
    }
}