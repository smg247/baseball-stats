<?php
include 'html_templaters.php';
include 'header.html';
$id = $_GET['id'];
$columns = array(Column::simpleDisplayColumn('name', 'Name')
                , Column::simpleDisplayColumn('age', 'Age')
                , Column::simpleDisplayColumn('height', 'Height')
                , Column::simpleDisplayColumn('weight', 'Weight')
                , Column::simpleDisplayColumn('handedness', 'Handedness')
                , Column::simpleDisplayColumn('position', 'Position')
                , Column::simpleDisplayColumn('team', 'Team'));

$detailed_player = new ReadTemplater('DetailedPlayers', $columns, $id, 'id', false, true, null, null, null, null, null);

echo '<h1>Player Details</h1>';
echo $detailed_player->execute();

$columns = array(Column::simpleDisplayColumn('eff_rating', 'Efficiency Rating')
                , Column::simpleDisplayColumn('batting_avg', 'Batting Average')
                , Column::simpleDisplayColumn('YEAR(year)', 'Year')
                , Column::complexValuedDisplayColumn('player_id', '', 'hidden', $id));
$referrer = "player.php?id=$id";
$creationUrl = "create-edit-position-stat.php";
$position_stats = new ReadTemplater('PositionStat', $columns, $id, 'player_id', true, true, $creationUrl, null, null, $referrer, 'Position Stat');

echo '<h2>Statistics</h2>';
echo $position_stats->execute();

include 'footer.html';
