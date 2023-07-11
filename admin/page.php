<?php

class Yuzu_Admin_Page {

    private array $page_options;

    public function __construct(array $page_options) {
        $this->page_options = $page_options;

        if (isset($page_options['tables'])) foreach ($page_options['tables'] as $table_name => $table_options) {
            $this->page_options['tables'][$table_name] = new Yuzu_Admin_Query_Table(
                $table_options['query'] ?? [],
                $table_options['table'] ?? []
            );
        }

        if (isset($page_options['tabs'])) foreach ($page_options['tabs'] as $slug => $tab) {
            $this->page_options['tabs'][$slug] = [
                'icon' => $tab['icon'],
                'title' => $tab['title'],
                'content' => fn() => $tab['content']($this)
            ];
        }
    }

    public function get_option(string $option) {
        return match ($option) {
            'content' => [$this, 'render'],
            default => $this->page_options[$option],
        };
    }

    public function get_table(string $table_name): Yuzu_Admin_Query_Table {
        return $this->page_options['tables'][$table_name];
    }

    public function render(): void {
        yuzu_render_admin_page([
            'title' => $this->page_options['title'],
            'actions' => $this->page_options['actions'],
            'description' => $this->page_options['description'],
            'content' => function() {
                if (isset($this->page_options['content'])) {
                    $this->page_options['content']($this);
                }
                if (isset($this->page_options['tabs'])) {
                    yuzu_render_admin_tabs($this->page_options['tabs']);
                }
            }
        ]);
    }

    public function render_table(string $table_name): void {
        yuzu_render_admin_query_table($this->get_table($table_name));
    }
}