<?php

class Yz_Select {

    public static function render(array $props = []): void {
        global $yz;

        $id       = $yz->tools->get_value($props, 'id');
        $name     = $yz->tools->get_value($props, 'name', $id);
        $class    = $yz->tools->get_value($props, 'class',);
        $label    = $yz->tools->get_value($props, 'label');
        $value    = $yz->tools->get_value($props, 'value');
        $required = $yz->tools->get_value($props, 'required', false);
        $options  = $yz->tools->get_value($props, 'options', []);

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
                            $opt_value    = $yz->tools->get_value($option, 'value', '');
                            $opt_label    = $yz->tools->get_value($option, 'label', $opt_value);
                            $opt_selected = $yz->tools->get_value($option, 'selected', false);

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