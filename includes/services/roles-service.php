<?php

class Yz_Roles_Service {

    public function get_editable_roles(): array {
        return get_editable_roles();
    }

    public function create_role(string $role_name, string $role_display_name, array $capabilities): WP_Role {
        return add_role($role_name, $role_display_name, $capabilities);
    }
}