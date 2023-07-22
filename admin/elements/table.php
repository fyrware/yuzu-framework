<?php

function yz_table(array $props): void { ?>
    <div class="tablenav top">

    </div>
    <table class="wp-list-table widefat striped <?php if ($props['fixed']) echo 'fixed' ?>">
        <thead>
        <tr>
            <?php if ($props['selectable']) { ?>
                <td class="check-column">
                    <input type="checkbox" />
                </td>
            <?php } ?>
            <?php foreach ($props['columns'] as $column) { ?>
                <th scope="col" class="<?php if ($column['sortable']) echo 'sortable desc' ?>">
                    <?php if (isset($column['content'])) $column['content']() ?>
                </th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($props['rows'] as $row) { ?>
            <tr id="<?= $row['id'] ?? '' ?>">
                <?php if ($table['selectable']) { ?>
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="post[]" value="<?= $row['id'] ?? '' ?>">
                    </th>
                <?php } ?>
                <?php foreach ($row as $cell) { ?>
                    <td>
                        <?= $cell ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php }