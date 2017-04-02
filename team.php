<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];

$columns = array(Column::simpleDisplayColumn('city', 'City'), Column::simpleDisplayColumn('name', 'Name'));
$team_templater = new ReadTemplater('Team', $columns, $id, "id", false, true, 'create-edit-team.php', null, null, null);

echo '<h1>Team Details</h1>';
echo $team_templater->execute();

$columns = array(
    Column::simpleDisplayColumn('YEAR(year)', 'Year'),
    Column::simpleDisplayColumn('wins', 'Wins'),
    Column::simpleDisplayColumn('losses', 'Losses'),
    Column::simpleDisplayColumn('rank', 'Rank'),
    Column::complexValuedDisplayColumn('team_id', '', 'hidden', $id)
);

$season_templater = new ReadTemplater('Season', $columns, $id, "team_id", true, true, "create-edit-season.php", null, "team.php?id=$id", "Season");

echo '<h2>Seasons</h2>';
echo $season_templater->execute();

include 'footer.html';
