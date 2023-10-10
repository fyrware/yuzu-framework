<?php

class Yz_Text {

    private const VALID_VARIANTS = [
        'label',
        'span',
        'strong',
        'em',
    ];

    public static function render(string $text, array $props): void {
        $id       = Yz_Array::value_or($props, 'id');
        $class    = Yz_Array::value_or($props, 'class');
        $variant  = Yz_Array::value_or($props, 'variant', 'span');
        $attr_set = Yz_Array::value_or($props, 'attr', []);
        $aria_set = Yz_Array::value_or($props, 'aria', []);
        $data_set = Yz_Array::value_or($props, 'data', []);

        assert(is_string($text), 'Text must be a string');
        assert(in_array($variant, Yz_Text::VALID_VARIANTS), 'Invalid text variant');

        $classes = [
            'yz',
            'yuzu',
            'text',
        ];

        if ($class) {
            $classes[] = $class;
        }

        Yz::Element($variant, [
            'id'       => $id,
            'class'    => Yz_Array::join($classes),
            'attr'     => $attr_set,
            'aria'     => $aria_set,
            'data'     => $data_set,
            'children' => function() use ($text) {
                echo $text;
            }
        ]);
    }
}
