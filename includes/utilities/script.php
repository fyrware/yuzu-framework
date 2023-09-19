<?php

class Yz_Script {

    public static function console_log(mixed ...$values) { ?>
        <script>
            console.log(...<?= json_encode($values) ?>);
        </script>
    <?php }
}