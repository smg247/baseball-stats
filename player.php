<?php
include 'html_templaters.php';
include 'header.html';
$id = $_GET['id'];
$columns = array(Column::simpleColumn('name', 'Name')
                , Column::simpleColumn('age', 'Age')
                , Column::simpleColumn('height', 'Height')
                , Column::simpleColumn('weight', 'Weight')
                , Column::simpleColumn('handedness', 'Handedness')
                , Column::simpleColumn('position', 'Position')
                , Column::simpleColumn('team', 'Team'));

$detailed_player = new ReadTemplater('DetailedPlayers', $columns, $id, 'id', false, true, null, null);

echo '<h1>Player Details</h1>';
echo $detailed_player->execute();

$columns = array(Column::simpleColumn('eff_rating', 'Efficiency Rating')
                , Column::simpleColumn('batting_avg', 'Batting Average')
                , Column::simpleColumn('YEAR(year)', 'Year'));
$referrer = "player.php?id=$id";
$position_stats = new ReadTemplater('PositionStat', $columns, $id, 'player_id', true, true, null, $referrer);

echo '<h2>Statistics</h2>';
echo $position_stats->execute();

include 'footer.html';
