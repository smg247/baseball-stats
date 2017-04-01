<?php
include 'html_templaters.php';
include 'header.html';

$id = $_GET['id'];
$column_names = array('city' => 'City', 'name' => 'Name');
$team_templater = new ReadTemplater('Team', $column_names, $id, "id", true, true, null);

echo '<h1>Team Details</h1>';
echo $team_templater->execute();

$column_names = array('YEAR(year)' => 'Year', 'wins' => 'Wins', 'losses' => 'Losses', 'rank' => 'Rank');
$season_templater = new ReadTemplater('Season', $column_names, $id, "team_id", true, true, null);

echo '<h2>Seasons</h2>';
echo $season_templater->execute();

include 'footer.html';
