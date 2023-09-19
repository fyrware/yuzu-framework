<?php

class Yz_Schema_Table_Foreign_Key {

    public string $column;
    public string $table;
    public string $foreign_column;

    public function __construct(string $column, string $table, string $foreign_column) {
        $this->column = $column;
        $this->table = $table;
        $this->foreign_column = $foreign_column;
    }
}

class Yz_Schema_Table_Column {

    public string $name;
    public string $type;
    public bool $nullable;
    public bool $auto_increment;

    public function __construct(string $name, string $type, bool $nullable, bool $auto_increment) {
        $this->name = $name;
        $this->type = $type;
        $this->nullable = $nullable;
        $this->auto_increment = $auto_increment;
    }
}

class Yz_Schema_Table {

    public string $name;
    public array $columns;

    public string $primary_key;
    public array $foreign_keys;

    public function __construct(string $name) {
        $this->name = $name;
        $this->columns = [];
        $this->primary_key = 'id';
        $this->foreign_keys = [];
    }

    public function get_column(string $name): ?Yz_Schema_Table_Column {
        foreach ($this->columns as $column) if ($column instanceof Yz_Schema_Table_Column) {
            if ($column->name === $name) {
                return $column;
            }
        }
        return null;
    }

    public function create_column(string $name, string $type, bool $nullable = true, bool $auto_increment = false): Yz_Schema_Table_Column {
        $column = new Yz_Schema_Table_Column($name, $type, $nullable, $auto_increment);
        $this->columns[] = $column;
        return $column;
    }

    public function create_primary_key(string $column_name): Yz_Schema_Table_Column {
        $column = $this->create_column($column_name, 'int', false, true);
        $this->primary_key = $column->name;
        return $column;
    }

    public function create_foreign_key(string $column, string $table, string $foreign_column): Yz_Schema_Table_Foreign_Key {
        $foreign_key = new Yz_Schema_Table_Foreign_Key($column, $table, $foreign_column);
        $this->foreign_keys[] = $foreign_key;
        return $foreign_key;
    }
}

class Yz_Schema {

    public string $name;
    public array $tables;
    public bool $exists;

    public function __construct(string $name, bool $exists = true) {
        $this->name = $name;
        $this->tables = [];
        $this->exists = $exists;
    }

    public function get_table(string $name): ?Yz_Schema_Table {
        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {
            if ($table->name === $name) {
                return $table;
            }
        }
        return null;
    }

    public function create_table(string $name): Yz_Schema_Table {
        global $wpdb;
        $table = new Yz_Schema_Table($wpdb->prefix . $this->name . '_' . $name);
        $this->tables[] = $table;
        return $table;
    }

    public function save_schema(): void {
        global $wpdb;

        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {
            $sql = "create table if not exists `$table->name` (";

            if ($table->primary_key) {
                $sql .= "`{$table->primary_key}` int unsigned not null auto_increment primary key";
            }

            $sql .= ')';
            $sql .= " collate {$wpdb->collate}";

            Yz_Script::console_log($sql);

            $wpdb->query($sql);
        }

        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {

            foreach ($table->columns as $column) if ($column instanceof Yz_Schema_Table_Column) {
                $column_nullable = $column->nullable ? 'null' : 'not null';
                $column_auto_increment = $column->auto_increment ? 'auto_increment' : '';

                $sql = "alter table `{$table->name}` add column if not exists `{$column->name}` {$column->type}";

                if ($column->nullable) {
                    $sql .= ' null';
                } else {
                    $sql .= ' not null';
                }

                if ($column->auto_increment) {
                    $sql .= ' auto_increment';
                }

                Yz_Script::console_log($sql);

                $wpdb->query($sql);
            }

//            foreach ($table->foreign_keys as $foreign_key) if ($foreign_key instanceof Yz_Schema_Table_Foreign_Key) {
//                $sql = $wpdb->prepare(
//                    'alter table %s add foreign key if not exists (%s) references %s(%s);',
//                    $table->name,
//                    $foreign_key->column,
//                    $foreign_key->table,
//                    $foreign_key->foreign_column
//                );
//
//                Yz_Script::console_log('sql', $sql);
//
//                $wpdb->query($sql);
//            }
        }
    }

    public static function get_full_schema(): Yz_Schema {
        global $wpdb;

        $schema = new Yz_Schema('wp');
        $tables = $wpdb->get_results('show tables', ARRAY_N);

        foreach ($tables as $table) {
            $table_name = $table[0];
            $table_schema = $wpdb->get_results("show create table $table_name", ARRAY_N);
            $table_schema = $table_schema[0][1];

            $table = $schema->create_table($table_name);
            $lines = explode("\n", $table_schema);

            foreach ($lines as $line) {
                $line = trim($line);

                if (preg_match('/^`(.+?)` .*/i', $line, $matches)) {
                    $column_name = $matches[1];

                    if (preg_match('/^`.+?` (.+?) .+/i', $line, $matches)) {
                        $column_type = $matches[1];
                        $column_nullable = boolval(preg_match('/^`.+?` .+? not null/i', $line, $matches));
                        $column_auto_increment = boolval(preg_match('/^`.+?` .+? auto_increment/i', $line, $matches));

                        $table->create_column($column_name, $column_type, $column_nullable, $column_auto_increment);
                    }
                }

                if (preg_match('/^primary key/i', $line, $matches)) {
                    if (preg_match('/^primary key \(`(.+?)`\)/i', $line, $matches)) {
                        $table->create_primary_key($matches[1]);
                    }
                }

                if (preg_match('/^foreign key/i', $line, $matches)) {
                    if (preg_match('/^foreign key \(`(.+?)`\) references `(.+?)` \(`(.+?)`\)/i', $line, $matches)) {
                        $table->create_foreign_key($matches[1], $matches[2], $matches[3]);
                    }
                }

                if (preg_match('/^key/i', $line, $matches)) {
                    // this is an index
                }
            }
        }

        return $schema;
    }
}