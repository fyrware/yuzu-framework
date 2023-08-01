<?php

const YUZU_TAG_START = '<';
const YUZU_TAG_STOP  = '>';
const YUZU_TAG_CLOSE = '/';

const YUZU_SELF_CLOSING_TAGS = [
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

const YUZU_NAMED_ELEMENTS = [
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

function yz_html(string $tag, array $attributes = [], ?callable $children = null): void {
    echo YUZU_TAG_START . $tag;

    if (!empty($attributes)) foreach ($attributes as $name => $value) {
        if ($value !== false) echo ' ' . $name . '="' . $value . '"';
    }

    echo YUZU_TAG_STOP;

    if (in_array($tag, YUZU_SELF_CLOSING_TAGS)) {
        return;
    }

    if ($children) {
        $children();
    }

    echo YUZU_TAG_START . YUZU_TAG_CLOSE . $tag . YUZU_TAG_STOP;
}

function yz_element(mixed $tag_or_props, ?array $props = null): void {
    $props = is_array($tag_or_props)  ? $tag_or_props : $props;
    $tag   = is_string($tag_or_props) ? $tag_or_props : yz_prop($props, 'tag', 'div');

    $id         = yz_prop($props, 'id', '');
    $name       = yz_prop($props, 'name', $id);
    $class      = yz_prop($props, 'class', '');
    $style      = yz_prop($props, 'style', '');
    $children   = yz_prop($props, 'children', fn() => null);
    $attributes = yz_prop($props, 'attributes', []);
    $data_set   = yz_prop($props, 'data_set', []);
    $aria_set   = yz_prop($props, 'aria', []);

    assert(is_string($tag),        'Tag must be a string.');
    assert(is_string($id),         'ID must be a string.');
    assert(is_string($name),       'Name must be a string.');
    assert(is_string($class),      'Class must be a string.');
    assert(is_string($style),      'Style must be a string.');
    assert(is_callable($children), 'Children must be a function.');
    assert(is_array($attributes),  'Attributes must be an array.');
    assert(is_array($aria_set),    'Aria set must be an array.');
    assert(is_array($data_set),    'Data set must be an array.');

    if ($id) {
        $attributes['id'] = $id;
    }

    if ($name && in_array($tag, YUZU_NAMED_ELEMENTS)) {
        $attributes['name'] = $name;
    }

    if ($style) {
        $attributes['style'] = $style;
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

    $attributes['class'] = yz_join($classes);

    yz_html($tag, $attributes, $children);
}
