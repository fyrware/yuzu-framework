<?php

class Yz_Table_Combo_Picker {

    public static function render(array $props): void {
        $label    = Yz_Array::value_or($props, 'label');
        $fields   = Yz_Array::value_or($props, 'fields', []);

        Yz::Flex_Layout([
            'gap' => 5,
            'class'     => 'table-combo-picker',
            'direction' => 'column',
            'children'  => function() use($label, $fields) {
                Yz::Flex_Layout([
                    'alignment' => 'center',
                    'justification' => 'space-between',
                    'children' => function() use($label) {
                        Yz::Text($label, [
                            'class'   => 'table-combo-picker-label',
                            'variant' => 'label'
                        ]);
                        Yz::Button([
                            'icon'  => 'plus-circle',
                            'size' => 'small',
                            'label' => 'Add Row'
                        ]);
                    }
                ]);
                Yz::Table([
                    'columns' => array_map(function($field) {
                        $label = Yz_Array::value_or($field, 'label');
                        return fn() => Yz::Text($label);
                    }, $fields),
                    'rows' => [
                        array_map(function($key) use($fields) {
                            $field = Yz_Array::value_or($fields, $key, []);
                            $type = Yz_Array::value_or($field, 'type');
                            $options = Yz_Array::value_or($field, 'options', []);
                            $placeholder = Yz_Array::value_or($field, 'placeholder');

                            switch ($type) {
                                case 'select':
                                    return fn() => Yz::Select([
                                        'name'    => $key,
                                        'options' => $options
                                    ]);
                                default:
                                    return fn() => Yz::Input([
                                        'type'        => $type,
                                        'name'        => $key,
                                        'placeholder' => $placeholder
                                    ]);
                            }
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