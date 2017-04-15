<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$team_id = $_GET['team_id'];
$columns = array(
    Column::createEditColumn('year', 'Year', 'year', $_GET['YEAR(year)']), // This is a little hacky, not worth the effort to fix it though
    Column::createEditColumn('wins', 'Wins', 'number', $_GET['wins']),
    Column::createEditColumn('losses', 'Losses', 'number', $_GET['losses']),
    Column::createEditColumn('rank', 'Rank', 'number', $_GET['rank']),
    Column::createEditColumn('team_id', '', 'hidden', $team_id)
);

$createTemplater = new CreateEditTemplater('Season', $columns, $_GET['id'], "team.php?id=$team_id", boolval($_GET['is-edit']));

echo $createTemplater->execute();

include 'footer.html';