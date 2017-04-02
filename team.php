<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];
$columns = array(Column::simpleColumn('city', 'City'), Column::simpleColumn('name', 'Name'));
$team_templater = new ReadTemplater('Team', $columns, $id, "id", false, true, null, null, null);

echo '<h1>Team Details</h1>';
echo $team_templater->execute();

$column_names = array('YEAR(year)' => 'Year', 'wins' => 'Wins', 'losses' => 'Losses', 'rank' => 'Rank');
$columns = array(
    Column::simpleColumn('YEAR(year)', 'Year'),
    Column::simpleColumn('wins', 'Wins'),
    Column::simpleColumn('losses', 'Losses'),
    Column::simpleColumn('rank', 'Rank')
);

$season_templater = new ReadTemplater('Season', $columns, $id, "team_id", true, true, null, null, null);

echo '<h2>Seasons</h2>';
echo $season_templater->execute();

include 'footer.html';
