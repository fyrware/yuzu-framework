<?php

class Yz_Empty_State {

    public static function render(array $props): void {
        global $yz;

        $id          = $yz->tools->key_or_default($props, 'id');
        $class       = $yz->tools->key_or_default($props, 'class');
        $title       = $yz->tools->key_or_default($props, 'title');
        $description = $yz->tools->key_or_default($props, 'description');
        $icon        = $yz->tools->key_or_default($props, 'icon');
        $actions     = $yz->tools->key_or_default($props, 'actions', []);

        $classes = [
            'empty-state'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->flex_layout([
            'id' => $id,
            'class' => $classes,
            'gap' => 20,
            'direction' => 'column',
            'alignment' => 'center',
            'justification' => 'center',
            'children' => function() use($yz, $icon, $title, $description, $actions) {
                if ($icon) $yz->html->icon($icon, [ 'appearance' => 'duotone' ]);
                $yz->html->flex_layout([
                    'class' => 'empty-state-content',
                    'direction' => 'column',
                    'alignment' => 'center',
                    'justification' => 'center',
                    'gap' => 10,
                    'children' => function() use($yz, $title, $description, $actions) {
                        $yz->html->title($title, [
                            'class' => 'empty-state-title',
                            'level' => 2,
                        ]);

                        if (isset($description)) {
                            $yz->html->element('p', [
                                'class' => 'empty-state-description',
                                'children' => function() use($yz, $description) {
                                    $yz->html->text($description ?? '');
                                }
                            ]);
                        }

                        $yz->html->flex_layout([
                            'class' => 'empty-state-actions',
                            'direction' => 'row',
                            'alignment' => 'center',
                            'justification' => 'center',
                            'gap' => 20,
                            'children' => function() use($yz, $actions) {
                                foreach ($actions as $action) {
                                    $yz->html->button($action);
                                }
                            }
                        ]);
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="empty-state">
            .yuzu.empty-state {
                padding: 80px 0;
            }
            .yuzu.empty-state > svg {
                width:   112px;
                height:  112px;
                opacity: 0.5;
            }
            .yuzu.empty-state h2,
            .yuzu.empty-state p {
                opacity: 0.5;
            }
            .yuzu.empty-state .yuzu.empty-state-title {
                margin:    0;
                font-size: 1.75em;
            }
        </style>
    <?php }
}
