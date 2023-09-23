<?php

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

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

    public function use_column(string $name): ?Yz_Schema_Table_Column {
        foreach ($this->columns as $column) if ($column instanceof Yz_Schema_Table_Column) {
            if ($column->name === $name) {
                return $column;
            }
        }
        return null;
    }

    public function define_column(string $column_name, string $column_type, bool $nullable = true, bool $auto_increment = false): Yz_Schema_Table {
        $column = new Yz_Schema_Table_Column($column_name, $column_type, $nullable, $auto_increment);
        $this->columns[] = $column;
        return $this;
    }

    public function define_primary_column(string $column_name, string $column_type = 'bigint(20)'): Yz_Schema_Table {
        $column = $this->define_column($column_name, $column_type, false, true);
        $this->primary_key = $column->name;
        return $this;
    }

    public function define_reference_column(string $column_name, string $foreign_table, string $foreign_column, string $column_type = 'bigint(20)'): Yz_Schema_Table {
        $column = $this->define_column($column_name, $column_type);
        $this->foreign_keys[] = new Yz_Schema_Table_Foreign_Key($column_name, $foreign_table, $foreign_column);
        return $this;
    }

    public function insert_row(array $data) {
        global $wpdb;
        $wpdb->insert($this->name, $data);
    }

    public function select_rows(array $where = []): array {
        global $wpdb;

        $sql = "select * from $this->name";

        if (count($where) > 0) {
            $sql .= ' where ';

            $where_clauses = [];

            foreach ($where as $key => $value) {
                $where_clauses[] = "$key = $value";
            }

            $sql .= implode(' and ', $where_clauses);
        }

        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function select_row_by_id(int $id): ?array {
        global $wpdb;
        return $wpdb->get_row("select * from $this->name where id = $id", ARRAY_A);
    }
}

class Yz_Schema {

    public string $prefix;
    public string $name;
    public array $tables;

    public function __construct(string $name) {
        global $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->name = $name;
        $this->tables = [];
    }

    public function use_table(string $table_name): Yz_Schema_Table {
        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {
            if ($table->name === $this->prefix . $this->name . '_' . $table_name) {
                return $table;
            }
        }
        return $this->tables[] = new Yz_Schema_Table($this->prefix . $this->name . '_' . $table_name);
    }

    public function define_table(string $table_name): Yz_Schema_Table {
        $table = new Yz_Schema_Table(str_starts_with($table_name, $this->prefix . $this->name)
            ? $table_name
            : $this->prefix . $this->name . '_' . $table_name
        );
        $this->tables[] = $table;
        return $table;
    }

    public function save_schema(): void {
        global $wpdb;

        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {
            $sql = "create table `$table->name` (";

            if ($table->primary_key) {
                $sql .= "`$table->primary_key` int unsigned not null auto_increment primary key";
            }

            $sql .= ')';
            $sql .= " collate $wpdb->collate";

            maybe_create_table($table->name, $sql);
        }

        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {

            foreach ($table->columns as $column) if ($column instanceof Yz_Schema_Table_Column) {
                $sql = "alter table `$table->name` add column `$column->name` $column->type";

                if ($column->nullable) {
                    $sql .= ' null';
                } else {
                    $sql .= ' not null';
                }

                if ($column->auto_increment) {
                    $sql .= ' auto_increment';
                }

                maybe_add_column($table->name, $column->name, $sql);
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

    public static array $loaded_schemas = [];

    public static function of(string $schema_name, bool $load_schema = false): Yz_Schema {
        global $wpdb;

        $schema = new Yz_Schema($schema_name);

        if (!$load_schema) {
            return $schema;
        } else if (array_key_exists($schema_name, static::$loaded_schemas)) {
            return static::$loaded_schemas[ $schema_name ];
        }

        $tables = $wpdb->get_results('show tables', ARRAY_N);

        foreach ($tables as $table) {
            $table_name = $table[0];

            if (!str_starts_with($table_name, $wpdb->prefix . $schema_name)) {
                continue;
            }

            $table_schema = $wpdb->get_results("show create table $table_name", ARRAY_N);
            $table_schema = $table_schema[0][1];

            $table = $schema->define_table($table_name);
            $lines = explode("\n", $table_schema);

            foreach ($lines as $line) {
                $line = trim($line);

                if (preg_match('/^`(.+?)` .*/i', $line, $matches)) {
                    $column_name = $matches[1];

                    if (preg_match('/^`.+?` (.+?) .+/i', $line, $matches)) {
                        $column_type = $matches[1];
                        $column_nullable = boolval(preg_match('/^`.+?` .+? not null/i', $line, $matches));
                        $column_auto_increment = boolval(preg_match('/^`.+?` .+? auto_increment/i', $line, $matches));

                        $table->define_column($column_name, $column_type, $column_nullable, $column_auto_increment);
                    }
                }

                if (preg_match('/^primary key/i', $line, $matches)) {
                    if (preg_match('/^primary key \(`(.+?)`\)/i', $line, $matches)) {
                        $table->primary_key = $matches[1];
                    }
                }

                if (preg_match('/^foreign key/i', $line, $matches)) {
                    if (preg_match('/^foreign key \(`(.+?)`\) references `(.+?)` \(`(.+?)`\)/i', $line, $matches)) {
                        $table->define_reference_column($matches[1], $matches[2], $matches[3]);
                    }
                }

                if (preg_match('/^key/i', $line, $matches)) {
                    // this is an index
                }
            }
        }

        static::$loaded_schemas[ $schema_name ] = $schema;

        return $schema;
    }
}