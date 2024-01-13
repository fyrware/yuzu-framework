<?php

class Yz_Markup {

    public const TAG_START = '<';
    public const TAG_STOP  = '>';
    public const TAG_CLOSE = '/';

    public const SELF_CLOSING_TAGS = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    public const NAMED_ELEMENTS = [
        'button',
        'form',
        'fieldset',
        'iframe',
        'input',
        'object',
        'output',
        'select',
        'textarea',
        'map',
        'meta',
        'param'
    ];

    public static function create_tag(string $tag, array $attributes = [], ?callable $children = null): string {
        $html = Yz_Markup::TAG_START . $tag;

        if (!empty($attributes)) foreach ($attributes as $name => $value) {
            if ($value === null) continue;
            if ($value !== false) {
                $html .= ' ' . $name;
                if ($value !== true) {
                    $html .= '="' . $value . '"';
                }
            }
        }

        $html .= Yz_Markup::TAG_STOP;

        if (in_array($tag, Yz_Markup::SELF_CLOSING_TAGS)) {
            return $html;
        }

        if ($children) {
            $html .= yz_capture(fn() => $children());
        }

        $html .= Yz_Markup::TAG_START . Yz_Markup::TAG_CLOSE . $tag . Yz_Markup::TAG_STOP;

        return $html;
    }

    public static function create_element(mixed $tag, ?array $props = null): string {
        $props = is_array($tag)  ? $tag : $props;
        $tag   = is_string($tag) ? $tag : Yz_Array::value_or($props, 'tag', 'div');

        $id         = Yz_Array::value_or($props, 'id');
        $name       = Yz_Array::value_or($props, 'name', $id);
        $class      = Yz_Array::value_or($props, 'class');
        $style      = Yz_Array::value_or($props, 'style');
        $children   = Yz_Array::value_or($props, 'children');
        $attributes = Yz_Array::value_or($props, 'attr', []);
        $data_set   = Yz_Array::value_or($props, 'data', []);
        $aria_set   = Yz_Array::value_or($props, 'aria', []);

        if (is_array($class)) {
            $class = Yz_Array::join($class);
        }

        if (is_array($style)) {
            $style = Yz_Array::join_key_value($style);
        }

        if (!is_null($tag))        assert(is_string($tag),        'Tag must be a string.');
        if (!is_null($id))         assert(is_string($id),         'ID must be a string.');
        if (!is_null($name))       assert(is_string($name),       'Name must be a string.');
        if (!is_null($class))      assert(is_string($class),      'Class must be a string.');
        if (!is_null($style))      assert(is_string($style),      'Style must be a string.');
        if (!is_null($children))   assert(is_callable($children), 'Children must be a function.');
        if (!is_null($attributes)) assert(is_array($attributes),  'Attributes must be an array.');
        if (!is_null($aria_set))   assert(is_array($aria_set),    'Aria set must be an array.');
        if (!is_null($data_set))   assert(is_array($data_set),    'Data set must be an array.');

        if ($id) {
            $attributes['id'] = $id;
        }

        if ($name && in_array($tag, Yz_Markup::NAMED_ELEMENTS)) {
            $attributes['name'] = $name;
        }

        if ($style) {
            $attributes['style'] = str_replace('_', '-', $style);
        }

        if (!empty($aria_set)) foreach ($aria_set as $name => $value) {
            $attributes['aria-' . str_replace('_', '-', $name)] = $value;
        }

        if (!empty($data_set)) foreach ($data_set as $name => $value) {
            $attributes['data-' . str_replace('_', '-', $name)] = $value;
        }

        $classes = [];

        if (isset($attributes['class'])) {
            $classes[] = $attributes['class'];
        }

        if ($class) {
            $classes[] = $class;
        }

        if (!empty($classes)) {
            $attributes['class'] = Yz_Array::join($classes);
        }

        if (isset($attributes['class']) && !str_contains($attributes['class'], 'yz')) {
            $attributes['class'] = 'yz ' . $attributes['class'];
        }

        return Yz_Markup::create_tag($tag, $attributes, $children);
    }
}