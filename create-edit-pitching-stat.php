<?php
error_reporting(0); // Need to turn off to use create functionality with missing params
include 'html_templaters.php';
include 'header.html';

$player_id = $_GET['player_id'];
$columns = array(Column::createEditColumn('era', 'ERA', 'number', $_GET['era']),
                Column::createEditColumn('whip', 'WHIP', 'number', $_GET['whip']),
                Column::createEditColumn('year', 'Year', 'year', $_GET['YEAR(year)']),
                Column::createEditColumn('player_id', '', 'hidden', $player_id));

$createEditTemplater = new CreateEditTemplater('PitchingStat', $columns, $_GET['id'], "player.php?id=$player_id", boolval($_GET['is-edit']));

echo $createEditTemplater->execute();

include 'footer.html';