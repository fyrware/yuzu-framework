<?php

/** @deprecated */
class Yz_Script {

    /** @deprecated */
    public static function console_log(mixed ...$values) { ?>
        <script>
            console.log(...<?= json_encode($values) ?>);
        </script>
    <?php }
}