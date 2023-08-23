<?php

function yz_array_find($array, $callback) {
    foreach ($array as $key => $value) {
        if ($callback($value, $key)) {
            return $value;
        }
    }
}
