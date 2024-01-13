<?php

class Yz_Portal {
    private static array $registered_portals = [];

    public static function inject(string $name, callable $children): void {

        if (!isset(Yz_Portal::$registered_portals[$name])) {
            Yz_Portal::$registered_portals[$name] = '';
        }

        Yz_Portal::$registered_portals[$name] .= Yz_Buffer::capture(fn() => $children());
    }

    public static function render(string $name, array $props): void {
        global $yz;

        $id    = $yz->tools->key_or_default($props, 'id', $name);
        $class = $yz->tools->key_or_default($props, 'class', '');

        $classes = [
            'yuzu',
            'portal'
        ];

        if ($class) {
            $classes[] = $class;
        }

        if (!isset(Yz_Portal::$registered_portals[$name])) {
            Yz_Portal::$registered_portals[$name] = '';
        }

        $yz->html->element('section', [
            'id'    => $id,
            'class' => $classes,
            'data'  => [
                'portal' => $name
            ],
            'children' => function() use ($name) {
                echo Yz_Portal::$registered_portals[$name] ?? '';
            }
        ]);
    }
}