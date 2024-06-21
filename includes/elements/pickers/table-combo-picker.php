<?php

class Yz_Table_Combo_Picker {

    public static function render(array $props): void {
        global $yz;

        $id     = $yz->tools->get_value($props, 'id');
        $label  = $yz->tools->get_value($props, 'label');
        $fields  = $yz->tools->get_value($props, 'fields', []);
        $entity = $yz->tools->get_value($props, 'entity', 'Variation');

        $yz->html->flex_layout([
            'id'        => $id,
            'gap'       => 5,
            'class'     => 'table-combo-picker',
            'direction' => 'column',
            'children'  => function() use($yz, $label, $fields, $entity) {
                $yz->html->flex_layout([
                    'alignment' => 'center',
                    'justification' => 'space-between',
                    'children' => function() use($yz, $label, $entity) {
                        $yz->html->text($label, [
                            'class'   => 'table-combo-picker-label',
                            'variant' => 'label'
                        ]);
                        $yz->html->button([
                            'class' => 'add-variation',
                            'icon'  => 'plus-circle',
                            'size' => 'small',
                            'label' => 'Add ' . $entity
                        ]);
                    }
                ]);
                $yz->html->table([
                    'columns' => array_map(function($field) use($yz) {
                        $label = $yz->tools->get_value($field, 'label');
                        return fn() => $yz->html->text($label);
                    }, $fields),
                    'rows' => [
                        array_map(function($key) use($yz, $fields) {
                            $field = $yz->tools->get_value($fields, $key, []);
                            $type = $yz->tools->get_value($field, 'type');
                            $options = $yz->tools->get_value($field, 'options', []);
                            $placeholder = $yz->tools->get_value($field, 'placeholder');

                            return match ($type) {
                                'select' => fn() => $yz->html->select([
                                    'name' => $key,
                                    'options' => $options
                                ]),
                                default => fn() => $yz->html->input([
                                    'type' => $type,
                                    'name' => $key,
                                    'placeholder' => $placeholder
                                ]),
                            };
                        }, array_keys($fields))
                    ]
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style>
            .table-combo-picker {

            }

            .table-combo-picker label {
                font-weight: 700;
            }

            .table-combo-picker select {
                width: 100%;
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                yz('.table-combo-picker').forEach(container => {
                    const button = yz('button.add-variation', container);
                    const tbody  = yz('tbody', container);
                    const row    = yz('tr', tbody);

                    button.spy('click').observe(() => {
                        tbody.append(row.item().cloneNode(true));
                    });
                });
            });
        </script>
    <?php }
}