<?php

class Yz_Pages_Service {

    public const PAGES_LOCATION = 'pages';

    public function var(string $name): string | null {
        return get_query_var($name);
    }

    public function add_virtual_page(string $path, ?string $template = null): void {
        $variables = [];

        if (preg_match_all('/:(\w+)/', $path, $variables)) {
            $variables = $variables[1];
        } else {
            $variables = [];
        }

        add_action('query_vars', function($vars) use($variables) {

            if (!in_array('yz_virtual_page', $vars)) {
                $vars[] = 'yz_virtual_page';
            }

            foreach ($variables as $var) {
                if (!in_array($var, $vars)) {
                    $vars[] = $var;
                }
            }

            return $vars;
        });

        add_action('init', function() use($path, $variables) {
            $url = 'index.php?yz_virtual_page=' . $path;
            $pattern = $path;

            foreach ($variables as $index => $var) {
                $url .= '&' . $var . '=$matches[' . $index + 1 . ']';
                $pattern = str_replace(':' . $var, '([^/]+?)', $pattern);
            }

            if (!str_ends_with($pattern, '/')) {
                $pattern .= '/';
            }

            add_rewrite_rule('^' . $pattern . '?$', $url, 'top');
            flush_rewrite_rules(false);
        });

        add_action('template_redirect', function() use($path, $template) {
            if ($this->var('yz_virtual_page') === $path) {
                get_template_part(static::PAGES_LOCATION . '/' . ($template ?? preg_replace('/[^A-Za-z0-9.-]/', '-', $path)), null);
                exit;
            }
        });
    }

    public function add_admin_menu_separator(float $position): void {
        global $menu;

        $separator = [
            0 => '',
            1 => 'read',
            2 => 'separator' . $position,
            3 => '',
            4 => 'wp-menu-separator'
        ];

        if (isset($menu[$position])) {
            $menu = array_splice($menu, $position, 0, $separator);
        } else {
            $menu[$position] = $separator;
        }
    }

    public function add_admin_page(array $page_settings): string {
        global $yz;

        $parent     = $yz->tools->key_or_default($page_settings, 'parent');
        $title      = $yz->tools->key_or_default($page_settings, 'title');
        $capability = $yz->tools->key_or_default($page_settings, 'capability');
        $slug       = $yz->tools->key_or_default($page_settings, 'slug');
        $children   = $yz->tools->key_or_default($page_settings, 'children');
        $position   = $yz->tools->key_or_default($page_settings, 'position');
        $icon       = $yz->tools->key_or_default($page_settings, 'icon');

        if (isset($icon) && str_starts_with($icon, '<svg')) {
            $icon = $yz->tools->format_data_url('image/svg+xml', $icon);
        }

        if (isset($parent)) {
            $page = add_submenu_page(
                $parent,
                $title,
                $title,
                $capability,
                $slug,
                function() use($yz, $children) { ?>
                    <script>
                        yz.wordpress.ajax = <?= json_encode(admin_url('admin-ajax.php')) ?>;
                        yz.wordpress.nonce = <?= json_encode(wp_create_nonce('yz')) ?>;
                    </script>
                    <section class="wrap">
                        <?php if (is_callable($children)) echo $children() ?>
                        <?php Yz::Portal(); ?>
                        <?php foreach ($yz->html->dependency_queue as $dependency) do_action($dependency); ?>
                    </section>
                <?php },
                $position ?? null
            );
        } else {
            $page = add_menu_page(
                $title,
                $title,
                $capability,
                $slug,
                function() use($yz, $children) { ?>
                    <script>
                        yz.wordpress.ajax = <?= json_encode(admin_url('admin-ajax.php')) ?>;
                        yz.wordpress.nonce = <?= json_encode(wp_create_nonce('yz')) ?>;
                    </script>
                    <section class="wrap">
                        <?php if (is_callable($children)) echo $children() ?>
                        <?php Yz::Portal(); ?>
                        <?php foreach ($yz->html->dependency_queue as $dependency) do_action($dependency); ?>
                    </section>
                <?php },
                $icon,
                $position ?? null
            );
        }

        return $page;
    }
}