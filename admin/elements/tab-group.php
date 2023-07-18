<?php

function _yz_tab_group_find_current_tab(array $tabs): array {
    $current_tab_slug = $_GET['tab'] ?? $tabs[0]['slug'];

    foreach ($tabs as $tab) {
        if ($tab['slug'] === $current_tab_slug) {
            return $tab;
        }
    }

    return $tabs[0];
}

function yz_tab_group(array $props): void {
    $class_names = [
        'yuzu',
        'tab-group',
        'nav-tab-wrapper'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    }

    $current_page = $_GET['page'];
    $current_tab = _yz_tab_group_find_current_tab($props['tabs']) ?>

    <nav class="<?= trim(implode(' ', $class_names)) ?>">
        <?php foreach ($props['tabs'] as $tab) {
            $tab_class_names = [
                'yuzu',
                'tab',
                'nav-tab'
            ];

            if (isset($tab['class_name'])) {
                $tab_class_names[] = $tab['class_name'];
            }

            if ($current_tab['slug'] === $tab['slug']) {
                $tab_class_names[] = 'nav-tab-active';
            } ?>

            <a href="?page=<?= $current_page ?>&tab=<?= $tab['slug'] ?>" class="<?= trim(implode(' ', $tab_class_names)) ?>">
                <?= $tab['icon'] ?? '' ?>
                <?= $tab['title'] ?>
            </a>
        <?php } ?>
    </nav>
    <main class="yuzu tab-content nav-tab-content">
        <?= $current_tab['content']() ?>
    </main>
<?php }