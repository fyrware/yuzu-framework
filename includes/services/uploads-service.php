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
        return get_attached_file($this->post->ID);
    }

    public function get_thumbnail_url(): string {
        return wp_get_attachment_image_src($this->post->ID)[0];
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

    public function get_width(): int {
        $meta = wp_get_attachment_metadata($this->post->ID);
        return $meta['width'];
    }

    public function get_height(): int {
        $meta = wp_get_attachment_metadata($this->post->ID);
        return $meta['height'];
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
            'id'              => $this->get_id(),
            'title'           => $this->get_title(),
            'url'             => $this->get_url(),
            'thumbnail_url'   => $this->get_thumbnail_url(),
            'upload_date'     => $this->get_upload_date(),
            'extension'       => $this->get_extension(),
            'mime_type'       => $this->get_mime_type(),
            'width'           => $this->get_width(),
            'height'          => $this->get_height(),
            'file_size'        => $this->get_file_size()
        ];
    }
}

class Yz_Uploads_Service {

    public const ATTACHMENT_POST_TYPE = 'attachment';

    public function create_folder(string $path): void {
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