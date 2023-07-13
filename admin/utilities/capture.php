<?php

function yz_capture(callable $callback): string {
    ob_start();
    $callback();
    return ob_get_clean();
}