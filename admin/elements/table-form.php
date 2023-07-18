<?php

function yz_table_form(array $props): void {
    $class_names = [
        'yuzu',
        'table-form',
        'form-table'
    ];

    if (isset($props['class_name'])) {
        $class_names[] = $props['class_name'];
    } ?>

    <table class="<?= trim(implode(' ', $class_names)) ?>">
        <tbody>
            <?php if (isset($props['fields'])) foreach ($props['fields'] as $field) { ?>
                <tr>
                    <th scope="row">
                        <label for="<?= $field['id'] ?? '' ?>">
                            <?= $field['label'] ?>
                        </label>
                    </th>
                    <td>
                        <?= $field['content']() ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php }