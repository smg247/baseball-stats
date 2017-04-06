<?php
include 'actions.php';
$properties = include 'properties.php';

$table = $_GET['table'];
$id = $_GET['id'];
$referrer = $_GET['referrer'];

$deleteAction = new DeleteAction($table, $id);
$deleteAction->execute();

$base = $properties['base'];
header("Location: /$referrer");
exit();