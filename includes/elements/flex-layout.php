<?php

class Yz_Flex_Layout {

    private const VALID_DIRECTIONS = [
        'row',
        'row-reverse',
        'column',
        'column-reverse'
    ];

    private const VALID_JUSTIFICATIONS = [
        'start',
        'end',
        'center',
        'space-between',
        'space-around',
        'space-evenly'
    ];

    private const VALID_ALIGNMENTS = [
        'start',
        'end',
        'center',
        'baseline',
        'stretch'
    ];

    private const VALID_WRAPS = [
        'wrap',
        'wrap-reverse',
        'nowrap'
    ];

    public static function render(array $props): void {
        global $yz;

        $id           = $yz->tools->get_value($props, 'id');
        $as           = $yz->tools->get_value($props, 'as', 'section');
        $inline       = $yz->tools->get_value($props, 'inline', false);
        $direction    = $yz->tools->get_value($props, 'direction', 'row');
        $justification = $yz->tools->get_value($props, 'justification', 'start');
        $alignment    = $yz->tools->get_value($props, 'alignment', 'stretch');
        $wrap         = $yz->tools->get_value($props, 'wrap', 'nowrap');
        $class_name   = $yz->tools->get_value($props, 'class');
        $style        = $yz->tools->get_value($props, 'style');
        $data         = $yz->tools->get_value($props, 'data', []);
        $gap          = $yz->tools->get_value($props, 'gap', 0);
        $children     = $yz->tools->get_value($props, 'children');
        $width        = $yz->tools->get_value($props, 'width');
        $height       = $yz->tools->get_value($props, 'height');
        $padding      = $yz->tools->get_value($props, 'padding');
        $grow         = $yz->tools->get_value($props, 'grow');
        $shrink       = $yz->tools->get_value($props, 'shrink');
        $overflow      = $yz->tools->get_value($props, 'overflow');

        assert(is_bool($inline));
        assert(in_array($direction,    Yz_Flex_Layout::VALID_DIRECTIONS));
        assert(in_array($justification, Yz_Flex_Layout::VALID_JUSTIFICATIONS));
        assert(in_array($alignment,    Yz_Flex_Layout::VALID_ALIGNMENTS));
        assert(in_array($wrap,         Yz_Flex_Layout::VALID_WRAPS));

        if ($gap && (is_int($gap) || is_double($gap))) {
            $gap .= 'px';
        }

        if ($gap) {
            $style['gap'] = $gap;
        }

        if (isset($width)) {
            $style['width'] = is_string($width) ? $width : $width . 'px';
        }

        if (isset($height)) {
            $style['height'] = is_string($height) ? $height : $height . 'px';
        }

        if (isset($padding)) {
            $style['padding'] = is_string($padding) ? $padding : $padding . 'px';
        }

        if (isset($grow)) {
            $style['flex-grow'] = (int)$grow;
        }

        if (isset($shrink)) {
            $style['flex-shrink'] = (int)$shrink;
        }

        if (isset($overflow)) {
            $style['overflow'] = $overflow;
        }

        $classes = [
            'yz',
            'yuzu',
            'flex-layout'
        ];

        if ($inline) {
            $classes[] = 'flex-inline';
        }

        if ($direction) {
            $classes[] = 'flex-direction-' . $direction;
        }

        if ($justification) {
            $classes[] = 'flex-justification-' . $justification;
        }

        if ($alignment) {
            $classes[] = 'flex-alignment-' . $alignment;
        }

        if ($wrap) {
            $classes[] = 'flex-wrap-' . $wrap;
        }

        if ($class_name) {
            $classes[] = $class_name;
        }

        $yz->html->element($as, [
            'id'       => $id,
            'data'     => $data,
            'class'    => $classes,
            'style'    => $style,
            'children' => function() use($children) {
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }

    public static function render_style() { ?>
        <style data-yz-element="flex-layout">
            .yz.flex-layout {
                display: flex;
            }

            .yz.flex-layout.flex-inline {
                display: inline-flex;
            }

            .yz.flex-layout.flex-direction-row {
                flex-direction: row;
            }

            .yz.flex-layout.flex-direction-row-reverse {
                flex-direction: row-reverse;
            }

            .yz.flex-layout.flex-direction-column {
                flex-direction: column;
            }

            .yz.flex-layout.flex-direction-column-reverse {
                flex-direction: column-reverse;
            }

            .yz.flex-layout.flex-justification-start {
                justify-content: flex-start;
            }

            .yz.flex-layout.flex-justification-center {
                justify-content: center;
            }

            .yz.flex-layout.flex-justification-end {
                justify-content: flex-end;
            }

            .yz.flex-layout.flex-justification-space-between {
                justify-content: space-between;
            }

            .yz.flex-layout.flex-justification-space-around {
                justify-content: space-around;
            }

            .yz.flex-layout.flex-justification-space-evenly {
                justify-content: space-evenly;
            }

            .yz.flex-layout.flex-alignment-start {
                align-items: flex-start;
            }

            .yz.flex-layout.flex-alignment-center {
                align-items: center;
            }

            .yz.flex-layout.flex-alignment-end {
                align-items: flex-end;
            }

            .yz.flex-layout.flex-alignment-stretch {
                align-items: stretch;
            }

            .yz.flex-layout.flex-alignment-baseline {
                align-items: baseline;
            }

            .yz.flex-layout.flex-wrap-wrap {
                flex-wrap: wrap;
            }

            .yz.flex-layout.flex-wrap-reverse {
                flex-wrap: wrap-reverse;
            }

            .yz.flex-layout.flex-wrap-nowrap {
                flex-wrap: nowrap;
            }

            .yz.flex-layout > *:first-child {
                margin-top: 0;
                padding-top: 0;
            }
        </style>
    <?php }
}