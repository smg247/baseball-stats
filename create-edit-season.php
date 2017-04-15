<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$team_id = $_GET['team_id'];
$columns = array(
    Column::complexValuedDisplayColumn('year', 'Year', 'year', $_GET['YEAR(year)']), // This is a little hacky, not worth the effort to fix it though
    Column::complexValuedDisplayColumn('wins', 'Wins', 'number', $_GET['wins']),
    Column::complexValuedDisplayColumn('losses', 'Losses', 'number', $_GET['losses']),
    Column::complexValuedDisplayColumn('rank', 'Rank', 'number', $_GET['rank']),
    Column::complexValuedDisplayColumn('team_id', '', 'hidden', $team_id)
);

$createTemplater = new CreateEditTemplater('Season', $columns, $_GET['id'], "team.php?id=$team_id", boolval($_GET['is-edit']));

echo $createTemplater->execute();

include 'footer.html';