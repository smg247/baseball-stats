<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];
$column_names = array('city' => 'City', 'name' => 'Name');
$read_templater = new ReadTemplater('Team', $column_names, $id, "id", true, true, null);

echo $read_templater->execute();

include 'footer.html';
