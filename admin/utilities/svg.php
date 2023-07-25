<?php

function yz_encode_svg(string $icon_svg): string {
    return 'data:image/svg+xml;base64,' . base64_encode($icon_svg);
}
