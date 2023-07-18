<?php

function yz_encode_svg(string $icon_svg): string {
    return 'data:image/svg+xml;base64,' . base64_encode($icon_svg);
}

function yz_icon_svg(array $props): string {
    return yz_capture(fn() => yz_icon($props));
}