<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$is_edit = $_GET['is-edit'];

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

$positionQuery = 'select value from Position;';
$positionResult = $mysqli->query($positionQuery);

$positions = array();
foreach ($positionResult as $position) {
    $value = array_values($position)[0];
    $positions[$value] = $value;
}


$teamsQuery = 'select id, name from Team;';
$teamsResult = $mysqli->query($teamsQuery);

$teams = array();
foreach ($teamsResult as $team) {
    $teamValues = array_values($team);
    $id = $teamValues[0];
    $name = $teamValues[1];
    $teams[$id] = $name;
}


$columns = array(Column::complexValuedDisplayColumn('name', 'Name', 'text', $_GET['name'])
                , Column::complexValuedDisplayColumn('age', 'Age', 'text', $_GET['age'])
                , Column::complexValuedDisplayColumn('height', 'Height', 'text', $_GET['height'])
                , Column::complexValuedDisplayColumn('weight', 'Weight', 'number', $_GET['weight'])
                , Column::complexValuedDisplayColumn('handedness', 'Handedness', 'handedness', $_GET['handedness'])
                , new Column('position', 'Position', $_GET['position'], 'select', $positions)
                , new Column('team', 'Team', $_GET['team'], 'select', $teams));

$createTemplater = new CreateEditTemplater('Player', $columns, $_GET['id'], 'player.php', boolval($is_edit));

echo $createTemplater->execute();

include 'footer.html';