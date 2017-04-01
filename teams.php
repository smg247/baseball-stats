<?php
include 'html_templaters.php';
include 'header.html';

$column_names = array('city' => 'City', 'name' => 'Name');
$read_templater = new ReadTemplater('Team', $column_names, null, "id", true, true, 'team.php');

echo '<h1>Teams</h1>';
echo $read_templater->execute();

include 'footer.html';
