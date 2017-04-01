<?php
include 'html_templaters.php';
include 'header.html';
$id = $_GET['id'];
$column_names = array('name', 'age', 'height', 'weight', 'handedness', 'position', 'team');
$detailed_player = new ReadTemplater('DetailedPlayers', $column_names, $id, 'id', true, true, null);

echo '<h1>Player Details</h1>';
echo $detailed_player->execute();

$column_names = array('eff_rating', 'batting_avg', 'year');
$position_stats = new ReadTemplater('PositionStat', $column_names, $id, 'player_id', true, true, null);
echo $position_stats->execute();

include 'footer.html';
