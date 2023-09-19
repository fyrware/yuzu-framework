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
        $id           = yz_prop($props, 'id');
        $inline       = yz_prop($props, 'inline', false);
        $direction    = yz_prop($props, 'direction', 'row');
        $justification = yz_prop($props, 'justification', 'start');
        $alignment    = yz_prop($props, 'alignment', 'stretch');
        $wrap         = yz_prop($props, 'wrap', 'nowrap');
        $class_name   = yz_prop($props, 'class', '');
        $style        = yz_prop($props, 'style', []);
        $gap          = yz_prop($props, 'gap', 0);
        $children     = yz_prop($props, 'children');

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

        $classes = [
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

        Yz::Element('section', [
            'id'       => $id,
            'class'    => Yz_Array::join($classes),
            'style'    => Yz_Array::join_key_value($style),
            'children' => function() use($children) {
                if (is_callable($children)) {
                    $children();
                }
            }
        ]);
    }

    public static function render_style() { ?>
        <style>
            .yuzu.flex-layout {
                display: flex;
            }

            .yuzu.flex-layout.flex-inline {
                display: inline-flex;
            }

            .yuzu.flex-layout.flex-direction-row {
                flex-direction: row;
            }

            .yuzu.flex-layout.flex-direction-row-reverse {
                flex-direction: row-reverse;
            }

            .yuzu.flex-layout.flex-direction-column {
                flex-direction: column;
            }

            .yuzu.flex-layout.flex-direction-column-reverse {
                flex-direction: column-reverse;
            }

            .yuzu.flex-layout.flex-justification-start {
                justify-content: flex-start;
            }

            .yuzu.flex-layout.flex-justification-center {
                justify-content: center;
            }

            .yuzu.flex-layout.flex-justification-end {
                justify-content: flex-end;
            }

            .yuzu.flex-layout.flex-justification-space-between {
                justify-content: space-between;
            }

            .yuzu.flex-layout.flex-justification-space-around {
                justify-content: space-around;
            }

            .yuzu.flex-layout.flex-justification-space-evenly {
                justify-content: space-evenly;
            }

            .yuzu.flex-layout.flex-alignment-start {
                align-items: flex-start;
            }

            .yuzu.flex-layout.flex-alignment-center {
                align-items: center;
            }

            .yuzu.flex-layout.flex-alignment-end {
                align-items: flex-end;
            }

            .yuzu.flex-layout.flex-alignment-stretch {
                align-items: stretch;
            }

            .yuzu.flex-layout.flex-alignment-baseline {
                align-items: baseline;
            }

            .yuzu.flex-layout.flex-wrap {
                flex-wrap: wrap;
            }

            .yuzu.flex-layout.flex-wrap-reverse {
                flex-wrap: wrap-reverse;
            }

            .yuzu.flex-layout.flex-wrap-nowrap {
                flex-wrap: nowrap;
            }

            .yuzu.flex-layout > *:first-child {
                margin-top: 0;
                padding-top: 0;
            }
        </style>
    <?php }
}