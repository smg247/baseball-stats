<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$player_id = $_GET['player_id'];
$columns = array(Column::complexValuedDisplayColumn('era', 'ERA', 'number', $_GET['era']),
                Column::complexValuedDisplayColumn('whip', 'WHIP', 'number', $_GET['whip']),
                Column::complexValuedDisplayColumn('year', 'Year', 'year', $_GET['YEAR(year)']),
                Column::complexValuedDisplayColumn('player_id', '', 'hidden', $player_id));

$createEditTemplater = new CreateEditTemplater('PitchingStat', $columns, $_GET['id'], "player.php?id=$player_id", boolval($_GET['is-edit']));

echo $createEditTemplater->execute();

include 'footer.html';