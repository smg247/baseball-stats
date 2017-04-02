<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleColumn('city', 'City'), Column::simpleColumn('name', 'Name'));
$read_templater = new ReadTemplater('Team', $columns, null, "id", true, true, 'team.php', 'teams.php');

echo '<h1>Teams</h1>';
echo $read_templater->execute();

include 'footer.html';
