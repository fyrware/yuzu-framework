<?php

function yuzu_render_admin_page_title(string $title): void { ?>
    <h1 class="wp-heading-inline">
        <?= $title ?>
    </h1>
<?php }

function yuzu_render_admin_page_actions(array $actions): void { ?>
    <?php foreach ($actions as $action => $url) { ?>
        <a href="<?= $url ?>" class="page-title-action">
            <?= $action ?>
        </a>
    <?php } ?>
<?php }

/**
 * Render a page in the WordPress admin
 * <br>
 * <br>
 * Page options:
 * - title: `string`
 * - actions: `array` [ action => url ]
 * - description: `string`
 * - content: `callable`
 * @param array $page
 * @return void
 */
function yuzu_render_admin_page(array $page) { ?>
    <div class="wrap">
        <?php if (isset($page['title'])) yuzu_render_admin_page_title($page['title']) ?>
        <?php if (isset($page['actions'])) yuzu_render_admin_page_actions($page['actions']) ?>
        <?php if (isset($page['description'])) yuzu_render_admin_description($page['description']) ?>
        <?php if (isset($page['content'])) $page['content']() ?>
    </div>
<?php }

function yuzu_render_admin_description(string $text): void { ?>
    <p class="description">
        <?= $text ?>
    </p>
<?php }

function yuzu_render_admin_tabs(array $tabs): void {
    $current_page = $_GET['page'];
    $current_tab = $_GET['tab'] ?? array_key_first($tabs) ?>

    <nav class="nav-tabs nav-tab-wrapper">
        <?php foreach ($tabs as $slug => $tab) { ?>
            <a href="?page=<?= $current_page ?>&tab=<?= $slug ?>" class="nav-tab <?= $current_tab === $slug ? 'nav-tab-active' : '' ?>">
                <?= $tab['icon'] ?? '' ?>
                <?= $tab['title'] ?>
            </a>
        <?php } ?>
    </nav>
    <main class="nav-tab-content">
        <?= $tabs[$current_tab]['content']() ?>
    </main>
<?php }

/**
 * Render a WordPress admin-panel horizontal divider
 * @return void
 */
function yuzu_render_admin_hr() { ?>
    <hr class="yuzu horizontal divider"/>
<?php }

/**
 * Render a WordPress admin-panel vertical divider
 * @return void
 */
function yuzu_render_admin_vr() { ?>
    <hr class="yuzu vertical divider"/>
<?php }