<?php
include 'actions.php';
$table = $_GET['table'];
$id = $_GET['id'];
$columns = $_GET['columns'];
$column_types = $_GET['column-types'];
$redirectUrl = $_GET['redirectUrl'];
$is_edit = $_GET['is-edit'];
$is_edit = boolval($is_edit);

$columns = explode(',', $columns);
$column_types = explode(',', $column_types);

$column_list = array();
$counter = 0;
foreach ($columns as &$column) {
    $value = $_GET[$column];
    $column_list[$counter] = Column::complexValuedColumn($column, $value, $column_types[$counter]);
    $counter++;
}

if ($is_edit) {
    $action = new EditAction($column_list, $table, $id);
} else {
    $action = new CreateAction($column_list, $table, null);
}
$action->execute();

// We might need to direct to the new entity if we don't already have a place to go
$contains_id = !strpos($redirectUrl, '?id=') === false;
if (!$is_edit && !$contains_id) {
    $redirectUrl .= '?id=' . $action->get_inserted_id();
} else if (!$contains_id) {
    $redirectUrl .= "?id=$id";
}

header("Location: $redirectUrl");
exit();