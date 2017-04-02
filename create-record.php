<?php
include 'actions.php';
$table = $_GET['table'];
$columns = $_GET['columns'];
$column_types = $_GET['column-types'];
$redirectUrl = $_GET['redirectUrl'];

$columns = explode(',', $columns);
$column_types = explode(',', $column_types);

$column_list = array();
$counter = 0;
foreach ($columns as &$column) {
    $value = $_GET[$column];
    $column_list[$counter] = Column::complexValuedColumn($column, $value, $column_types[$counter]);
    $counter++;
}

$create_action = new CreateAction($column_list, $table, null);
$create_action->execute();

// We might need to direct to the new entity if we don't already have a place to go
if (strpos($redirectUrl, '?id=') === false) {
    $redirectUrl .= '?id=' . $create_action->get_inserted_id();
}

header("Location: /$redirectUrl");
exit();