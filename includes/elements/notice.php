<?php

class Yz_Notice {

    private const VALID_VARIANTS = [
        'success',
        'warning',
        'error',
        'info'
    ];

    public static function render(array $props): void {
        global $yz;

        $id          = $yz->tools->key_or_default($props, 'id');
        $class       = $yz->tools->key_or_default($props, 'class');
        $alt         = $yz->tools->key_or_default($props, 'alt', false);
        $dismissible = $yz->tools->key_or_default($props, 'dismissible', false);
        $variant     = $yz->tools->key_or_default($props, 'variant', 'info');
        $title       = $yz->tools->key_or_default($props, 'title');
        $icon        = $yz->tools->key_or_default($props, 'icon');
        $action      = $yz->tools->key_or_default($props, 'action');
        $children    = $yz->tools->key_or_default($props, 'children');
        $inline      = $yz->tools->key_or_default($props, 'inline', false);

        assert(is_string($title), 'Title must be a string');
        assert(is_null($action) || is_array($action), 'Action must be an array');
        assert(in_array($variant, Yz_Notice::VALID_VARIANTS), 'Invalid variant');

        $classes = [
            'yz',
            'notice'
        ];

        if ($alt) {
            $classes[] = 'notice-alt';
        }

        if ($dismissible) {
            $classes[] = 'is-dismissible';
        }

        if ($inline) {
            $classes[] = 'inline';
        }

        if ($variant) {
            $classes[] = 'notice-' . $variant;
        }

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('aside', [
            'id' => $id,
            'class' => $classes,
            'children' => function() use($yz, $icon, $title, $children, $action) {
                $yz->html->flex_layout([
                    'gap' => 12,
                    'alignment' => 'center',
                    'class' => 'notice-content',
                    'children' => function() use($yz, $icon, $title, $children, $action) {
                        if ($icon) $yz->html->icon($icon, [ 'appearance' => 'bold' ]);
                        $yz->html->flex_layout([
                            'class' => 'notice-text',
                            'direction' => 'column',
                            'justification' => 'center',
                            'children' => function() use($yz, $title, $children) {
                                if ($title) $yz->html->text($title, [ 'variant' => 'strong' ]);
                                if (is_callable($children)) $children();
                            }
                        ]);
                        if ($action) {
                            $yz->html->button([
                                'size' => 'small',
                                'type' => 'link',
                                'label' => $action['label'],
                                'href' => $action['href'],
                                'target' => $action['target'] ?? '_self'
                            ]);
                        }
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="notice">
            .notice {
                border-radius: 4px;
            }

            .yz.notice {
                margin: 0;
                min-height: 40px;
                box-sizing: border-box;
            }

            .yz.notice.inline {
                margin: 15px 0 15px;
            }

            .yz.notice .notice-content {
                margin: 0.5em 0;
            }

            .yz.notice .notice-text {
                flex-grow: 1;
            }

            .yz.notice .yz.icon {
                width: 24px;
                height: 24px;
            }
        </style>
    <?php }
}