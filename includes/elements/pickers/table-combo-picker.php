<?php

class Yz_Table_Combo_Picker {

    public static function render(array $props): void {
        global $yz;

        $label = $yz->tools->key_or_default($props, 'label');
        $fields = $yz->tools->key_or_default($props, 'fields', []);

        $yz->html->flex_layout([
            'gap' => 5,
            'class'     => 'table-combo-picker',
            'direction' => 'column',
            'children'  => function() use($yz, $label, $fields) {
                $yz->html->flex_layout([
                    'alignment' => 'center',
                    'justification' => 'space-between',
                    'children' => function() use($yz, $label) {
                        $yz->html->text($label, [
                            'class'   => 'table-combo-picker-label',
                            'variant' => 'label'
                        ]);
                        $yz->html->button([
                            'icon'  => 'plus-circle',
                            'size' => 'small',
                            'label' => 'Add Row'
                        ]);
                    }
                ]);
                $yz->html->table([
                    'columns' => array_map(function($field) use($yz) {
                        $label = $yz->tools->key_or_default($field, 'label');
                        return fn() => $yz->html->text($label);
                    }, $fields),
                    'rows' => [
                        array_map(function($key) use($yz, $fields) {
                            $field = $yz->tools->key_or_default($fields, $key, []);
                            $type = $yz->tools->key_or_default($field, 'type');
                            $options = $yz->tools->key_or_default($field, 'options', []);
                            $placeholder = $yz->tools->key_or_default($field, 'placeholder');

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
                    const button = yz('button', container).item();
                    const tbody  = yz('tbody', container).item();
                    const row    = yz('tr', tbody).item().cloneNode(true);

                    button.addEventListener('click', () => {
                        tbody.appendChild(row.cloneNode(true));
                    });
                });
            });
        </script>
    <?php }
}