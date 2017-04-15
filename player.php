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

$detailed_player = new ReadTemplater('DetailedPlayers', $columns, $id, 'id', null, true, 'create-edit-player.php', null, null, null, null);

echo '<h1>Player Details</h1>';
echo $detailed_player->execute();

$properties = include 'properties.php';
$mysqli = new mysqli($properties['db_host'], $properties['db_user'], $properties['db_pass'], $properties['db_name'], '3306');

$positionQuery = "select position from Player where id = $id";
$result = $mysqli->query($positionQuery)->fetch_assoc();
$position = array_values($result)[0];

if ($position === 'LHP' || $position === 'RHP') {
    $columns = array(Column::simpleDisplayColumn('era', 'ERA')
    , Column::simpleDisplayColumn('whip', 'WHIP')
    , Column::simpleDisplayColumn('YEAR(year)', 'Year')
    , Column::valuedColumn('player_id', $id, 'hidden'));

    $referrer = "player.php?id=$id";
    $creationUrl = "create-edit-pitching-stat.php";
    $pitching_stats = new ReadTemplater('PitchingStat', $columns, $id, 'player_id', 'PitchingStat', true, $creationUrl, "?player_id=$id", null, $referrer, 'Pitching Stat');

    echo '<h2>Statistics</h2>';
    echo $pitching_stats->execute();
} else {
    $columns = array(Column::simpleDisplayColumn('eff_rating', 'Efficiency Rating')
    , Column::simpleDisplayColumn('batting_avg', 'Batting Average')
    , Column::simpleDisplayColumn('YEAR(year)', 'Year')
    , Column::valuedColumn('player_id', $id, 'hidden'));

    $referrer = "player.php?id=$id";
    $creationUrl = "create-edit-position-stat.php";
    $position_stats = new ReadTemplater('PositionStat', $columns, $id, 'player_id', 'PositionStat', true, $creationUrl, "?player_id=$id", null, $referrer, 'Position Stat');

    echo '<h2>Statistics</h2>';
    echo $position_stats->execute();
}

include 'footer.html';
