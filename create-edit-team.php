<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$is_edit = $_GET['is-edit'];

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

$divisionQuery = 'select id, league, division from Division;';
$divisionResult = $mysqli->query($divisionQuery);

$divisions = array();
$currentDivisionId = null;
foreach ($divisionResult as $division) {
    $divisionValues = array_values($division);
    $id = $divisionValues[0];
    $league = $divisionValues[1];
    $divisionName = $divisionValues[2];
    $leagueAndDivision = $league . ' ' . $divisionName;
    $divisions[$id] = $leagueAndDivision;

    if ($_GET['league'] . ' ' . $_GET['division'] == $leagueAndDivision) {
        $currentDivisionId = $id;
    }
}

$columns = array(Column::createEditColumn('city', 'City', 'text', $_GET['city']),
                 Column::createEditColumn('name', 'Name', 'text', $_GET['name']),
                 new Column('division_id', 'Division', $currentDivisionId, 'select', $divisions));

$createTemplater = new CreateEditTemplater('Team', $columns, $_GET['id'], 'team.php', boolval($is_edit));

echo $createTemplater->execute();

include 'footer.html';