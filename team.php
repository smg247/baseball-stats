<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];
$columns = array(Column::simpleDisplayColumn('city', 'City'), Column::simpleDisplayColumn('name', 'Name'));
$team_templater = new ReadTemplater('Team', $columns, $id, "id", false, true, null, null, null);

echo '<h1>Team Details</h1>';
echo $team_templater->execute();

$column_names = array('YEAR(year)' => 'Year', 'wins' => 'Wins', 'losses' => 'Losses', 'rank' => 'Rank');
$columns = array(
    Column::simpleDisplayColumn('YEAR(year)', 'Year'),
    Column::simpleDisplayColumn('wins', 'Wins'),
    Column::simpleDisplayColumn('losses', 'Losses'),
    Column::simpleDisplayColumn('rank', 'Rank')
);

$season_templater = new ReadTemplater('Season', $columns, $id, "team_id", true, true, null, null, null);

echo '<h2>Seasons</h2>';
echo $season_templater->execute();

include 'footer.html';
