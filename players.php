<?php
include 'html_templaters.php';
include 'header.html';

$column_names = array('name', 'age', 'height', 'weight', 'handedness', 'position', 'team');
$read_templater = new ReadTemplater('DetailedPlayers', $column_names, null, true, true);

echo '<h1>Players</h1>';
echo $read_templater->execute();

include 'footer.html';
