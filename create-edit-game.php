<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$team_id = $_GET['home_team_id']; // For edit from the home team screen
if ($team_id == null) { // For edit from the away team screen
    $team_id = $_GET['away_team_id'];
}
if ($team_id == null) { // For create
    $team_id = $_GET['team_id'];
}

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

$teamsQuery = 'select id, name from Team;';
$teamsResult = $mysqli->query($teamsQuery);

$teams = array();
$home_team_id = null;
$away_team_id = null;
foreach ($teamsResult as $team) {
    $teamValues = array_values($team);
    $id = $teamValues[0];
    $name = $teamValues[1];
    $teams[$id] = $name;

    if ($_GET['home_team_name'] == $name) {
        $home_team_id = $id;
    } else if ($_GET['away_team_name'] == $name) {
        $away_team_id = $id;
    }
}

$columns = array(
    new Column('home_team_id', 'Home Team', $home_team_id, 'select', $teams),
    new Column('away_team_id', 'Away Team', $away_team_id, 'select', $teams),
    Column::createEditColumn('home_score', 'Home Score', 'number', $_GET['home_score']),
    Column::createEditColumn('away_score', 'Away Score', 'number', $_GET['away_score']),
    Column::createEditColumn('date', 'Date', 'date', $_GET['date'])
);

$createTemplater = new CreateEditTemplater('Game', $columns, $_GET['id'], "team.php?id=$team_id", boolval($_GET['is-edit']));

echo $createTemplater->execute();

include 'footer.html';