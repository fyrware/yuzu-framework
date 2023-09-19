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
        $id    = Yz_Array::value_or($props, 'id', $name);
        $class = Yz_Array::value_or($props, 'class', '');

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

        Yz::Element('section', [
            'id'    => $id,
            'class' => Yz_Array::join($classes),
            'data'  => [
                'portal' => $name
            ],
            'children' => function() use ($name) {
                echo Yz_Portal::$registered_portals[$name] ?? '';
            }
        ]);
    }
}