<?php

class Yz_Buffer {

    public static function capture(callable $callback): string {
        ob_start();
        $callback();
        return ob_get_clean();
    }
}