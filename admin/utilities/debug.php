<?php

function yz_dump($var): void { ?>
    <pre><code><?php var_dump($var) ?></code></pre>
<?php }

function yz_dump_with_newlines(mixed ...$values): void {
    foreach ($values as $value) {
        var_dump($value);
        echo "\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n";
    }
}