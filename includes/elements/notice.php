<?php

class Yz_Notice {

    private const VALID_VARIANTS = [
        'success',
        'warning',
        'error',
        'info'
    ];

    public static function render(array $props): void {
        $id          = Yz_Array::value_or($props, 'id');
        $class       = Yz_Array::value_or($props, 'class');
        $alt         = Yz_Array::value_or($props, 'alt', false);
        $dismissible = Yz_Array::value_or($props, 'dismissible', false);
        $variant     = Yz_Array::value_or($props, 'variant', 'info');
        $title       = Yz_Array::value_or($props, 'title');
        $icon        = Yz_Array::value_or($props, 'icon');
        $action      = Yz_Array::value_or($props, 'action');
        $children    = Yz_Array::value_or($props, 'children');
        $inline      = Yz_Array::value_or($props, 'inline', false);

        assert(is_string($title), 'Title must be a string');
        assert(is_null($action) || is_array($action), 'Action must be an array');
        assert(in_array($variant, Yz_Notice::VALID_VARIANTS), 'Invalid variant');

        $classes = [
            'yuzu',
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

        Yz::Element('aside', [
            'id' => $id,
            'class' => Yz_Array::join($classes),
            'children' => function() use($icon, $title, $children, $action) {
                Yz::Flex_Layout([
                    'gap' => 12,
                    'alignment' => 'center',
                    'class' => 'notice-content',
                    'children' => function() use($icon, $title, $children, $action) {
                        if ($icon) Yz::Icon($icon, [ 'appearance' => 'bold' ]);
                        Yz::Flex_Layout([
                            'class' => 'notice-text',
                            'direction' => 'column',
                            'justification' => 'center',
                            'children' => function() use($title, $children) {
                                if ($title) Yz::Text($title, [ 'variant' => 'strong' ]);
                                if (is_callable($children)) $children();
                            }
                        ]);
                        if ($action) {
                            Yz::Button([
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
        <style>
            .notice {
                border-radius: 4px;
            }

            .yuzu.notice {
                margin: 0;
                min-height: 40px;
                box-sizing: border-box;
            }

            .yuzu.notice.inline {
                margin: 15px 0 15px;
            }

            .yuzu.notice .notice-content {
                margin: 0.5em 0;
            }

            .yuzu.notice .notice-text {
                flex-grow: 1;
            }

            .yuzu.notice .yuzu.icon {
                width: 24px;
                height: 24px;
            }
        </style>
    <?php }
}