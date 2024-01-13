<?php

class Yz_Settings_Service {

    public function update_option(string $option, $value): void {
        update_option($option, $value);
    }
}