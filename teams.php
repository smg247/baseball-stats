<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleDisplayColumn('city', 'City'), Column::simpleDisplayColumn('name', 'Name'));
$read_templater = new ReadTemplater('Team', $columns, null, "id", true, true, 'create-team.php', 'team.php', 'teams.php', 'Team');

echo '<h1>Teams</h1>';
echo $read_templater->execute();

include 'footer.html';
