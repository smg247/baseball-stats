<?php
include 'html_templaters.php';
include 'header.html';

$player_id = $_GET['player-id'];

$columns = array(Column::complexDisplayColumn('eff_rating', 'Efficiency Rating', 'number'),
                Column::complexDisplayColumn('batting_avg', 'Batting Average', 'number'),
                Column::complexDisplayColumn('year', 'Year', 'year'),
                Column::complexValuedDisplayColumn('player_id', '', 'hidden', $player_id));

$createTemplater = new CreateTemplater('PositionStat', $columns, "player.php?id=$player_id");

echo $createTemplater->execute();

include 'footer.html';