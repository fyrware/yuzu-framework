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

    public function column(string $column_name, array $options = []): ?Yz_Schema_Table_Column {
        global $yz;

        foreach ($this->columns as $column) if ($column instanceof Yz_Schema_Table_Column) {
            if ($column->name === $column_name) {
                return $column;
            }
        }

        $column_type = $yz->tools->get_value($options, 'type', 'text');
        $column_nullable = $yz->tools->get_value($options, 'nullable', false);
        $column_auto_increment = $yz->tools->get_value($options, 'auto_increment', false);

        return $this->columns[] = new Yz_Schema_Table_Column($column_name, $column_type, $column_nullable, $column_auto_increment);
    }

    public function primary_column(string $column_name, string $column_type = 'bigint(20)'): Yz_Schema_Table {
        $column = $this->column($column_name, [ 'type' => $column_type, 'nullable' => false, 'auto_increment' => true ]);
        $this->primary_key = $column->name;
        return $this;
    }

    public function reference_column(string $column_name, string $foreign_table, string $foreign_column, string $column_type = 'bigint(20)'): Yz_Schema_Table {
        $column = $this->column($column_name, [ 'type' => $column_type ]);
        $this->foreign_keys[] = new Yz_Schema_Table_Foreign_Key($column_name, $foreign_table, $foreign_column);
        return $this;
    }

    public function insert(array $data) {
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
                if (str_starts_with($value, '<') || str_starts_with($value, '>')) {
                    $where_clauses[] = "$key $value";
                } else if (str_starts_with($value, 'like')) {
                    $where_clauses[] = "$key like $value";
                } else if (str_starts_with($value, 'in')) {
                    $where_clauses[] = "$key in $value";
                } else if (str_starts_with($value, 'not in')) {
                    $where_clauses[] = "$key not in $value";
                } else if (str_starts_with($value, 'is')) {
                    $where_clauses[] = "$key is $value";
                } else if (str_starts_with($value, 'is not')) {
                    $where_clauses[] = "$key is not $value";
                } else if (str_starts_with($value, 'not')) {
                    $where_clauses[] = "$key not $value";
                } else if (str_starts_with($value, 'between')) {
                    $where_clauses[] = "$key between $value";
                } else if (str_starts_with($value, 'not between')) {
                    $where_clauses[] = "$key not between $value";
                } else if (str_starts_with($value, 'exists')) {
                    $where_clauses[] = "$key exists $value";
                } else if (str_starts_with($value, 'not exists')) {
                    $where_clauses[] = "$key not exists $value";
                } else if (str_starts_with($value, 'regexp')) {
                    $where_clauses[] = "$key regexp $value";
                } else if (str_starts_with($value, 'not regexp')) {
                    $where_clauses[] = "$key not regexp $value";
                } else if (str_starts_with($value, 'rlike')) {
                    $where_clauses[] = "$key rlike $value";
                } else if (str_starts_with($value, 'not rlike')) {
                    $where_clauses[] = "$key not rlike $value";
                } else if (str_starts_with($value, 'sounds like')) {
                    $where_clauses[] = "$key sounds like $value";
                } else if (str_starts_with($value, 'not sounds like')) {
                    $where_clauses[] = "$key not sounds like $value";
                } else {
                    $where_clauses[] = "$key = $value";
                }
            }

            $sql .= implode(' and ', $where_clauses);
        }

        return $wpdb->get_results($sql, ARRAY_A);
    }

    public function select_row_by_id(int $id): ?array {
        global $wpdb;
        return $wpdb->get_row("select * from $this->name where id = $id", ARRAY_A);
    }

    public function select_first_row(array $where = []): ?array {
        global $wpdb;
        return $wpdb->get_row("select * from $this->name limit 1", ARRAY_A);
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

    public function table(string $table_name): Yz_Schema_Table {
        foreach ($this->tables as $table) if ($table instanceof Yz_Schema_Table) {
            if ($table->name === $this->prefix . $this->name . '_' . $table_name) {
                return $table;
            }
        }
        return $this->tables[] = new Yz_Schema_Table($this->prefix . $this->name . '_' . $table_name);
    }

    public function save(): void {
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
}

class Yz_Database_Service {

    private array $schemas;

    public function define_schema(string $name, array $tables): Yz_Schema {
        $schema = new Yz_Schema($name);

        $this->schemas[$name] = $schema;

        foreach ($tables as $name => $definition) if (is_callable($definition)) {
            $definition($schema->table($name));
        }

        return $schema;
    }
}