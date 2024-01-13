<?php

class Yz_Text {

    private const VALID_VARIANTS = [
        'label',
        'span',
        'strong',
        'em',
    ];

    public static function render(string $text, array $props): void {
        global $yz;

        $id       = $yz->tools->key_or_default($props, 'id');
        $class    = $yz->tools->key_or_default($props, 'class');
        $variant  = $yz->tools->key_or_default($props, 'variant', 'span');
        $attr_set = $yz->tools->key_or_default($props, 'attr', []);
        $aria_set = $yz->tools->key_or_default($props, 'aria', []);
        $data_set = $yz->tools->key_or_default($props, 'data', []);

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

        $yz->html->element($variant, [
            'id'       => $id,
            'class'    => $classes,
            'attr'     => $attr_set,
            'aria'     => $aria_set,
            'data'     => $data_set,
            'children' => function() use ($text) {
                echo $text;
            }
        ]);
    }
}
