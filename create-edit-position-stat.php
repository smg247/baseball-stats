<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$player_id = $_GET['player_id'];
$columns = array(Column::complexValuedDisplayColumn('eff_rating', 'Efficiency Rating', 'number', $_GET['eff_rating']),
                Column::complexValuedDisplayColumn('batting_avg', 'Batting Average', 'number', $_GET['batting_avg']),
                Column::complexValuedDisplayColumn('year', 'Year', 'year', $_GET['YEAR(year)']),
                Column::complexValuedDisplayColumn('player_id', '', 'hidden', $player_id));

$createEditTemplater = new CreateEditTemplater('PositionStat', $columns, $_GET['id'], "player.php?id=$player_id", boolval($_GET['is-edit']));

echo $createEditTemplater->execute();

include 'footer.html';