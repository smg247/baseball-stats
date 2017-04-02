<?php
include 'actions.php';
$table = $_GET['table'];
$columns = $_GET['columns'];
$redirectUrl = $_GET['redirectUrl'];

$columns = explode(',', $columns);

$column_list = array();
$counter = 0;
foreach ($columns as &$column) {
    $value = $_GET[$column];
    $column_list[$counter] = Column::valuedColumn($column, $value);
    $counter++;
}

$create_action = new CreateAction($column_list, $table, null);
$inserted_id = $create_action->execute();

header("Location: /$redirectUrl?id=$inserted_id");
exit();