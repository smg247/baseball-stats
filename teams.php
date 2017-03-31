<?php
include 'html_templaters.php';
include 'header.html';

$column_names = array('city', 'name');
$read_templater = new ReadTemplater('Team', $column_names, null);

echo $read_templater->execute();

include 'footer.html';
