<?php

class Yz_Select {

    public static function render(array $props = []): void {
        global $yz;

        $id       = $yz->tools->key_or_default($props, 'id');
        $name     = $yz->tools->key_or_default($props, 'name', $id);
        $class    = $yz->tools->key_or_default($props, 'class',);
        $label    = $yz->tools->key_or_default($props, 'label');
        $value    = $yz->tools->key_or_default($props, 'value');
        $required = $yz->tools->key_or_default($props, 'required', false);
        $options  = $yz->tools->key_or_default($props, 'options', []);

        $classes = [
            'yuzu',
            'select'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->flex_layout([
            'gap' => 5,
            'direction' => 'column',
            'children' => function() use($yz, $id, $name, $classes, $required, $label, $value, $options) {
                if ($label) {
                    $yz->html->text($label, [ 'variant' => 'strong' ]);
                }
                $yz->html->element('select', [
                    'id'    => $id,
                    'name'  => $name,
                    'class' => $classes,
                    'style' => [ 'max-width' => '100%' ],
                    'attr'  => [
                        'required' => $required
                    ],
                    'children' => function() use($yz, $value, $options) {
                        foreach ($options as $option) {
                            $opt_value    = $yz->tools->key_or_default($option, 'value', '');
                            $opt_label    = $yz->tools->key_or_default($option, 'label', $opt_value);
                            $opt_selected = $yz->tools->key_or_default($option, 'selected', false);

                            $yz->html->element('option', [
                                'attr' => [
                                    'value'    => $opt_value,
                                    'selected' => $value === $opt_value || $opt_selected
                                ],
                                'children' => function() use($opt_label) {
                                    echo $opt_label;
                                }
                            ]);
                        }
                    }
                ]);
            }
        ]);
    }
}