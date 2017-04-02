<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleColumn('name', 'Name')
                    , Column::simpleColumn('age', 'Age')
                    , Column::simpleColumn('height', 'Height')
                    , Column::simpleColumn('weight', 'Weight')
                    , Column::simpleColumn('handedness', 'Handedness')
                    , Column::simpleColumn('position', 'Position')
                    , Column::simpleColumn('team', 'Team'));

$read_templater = new ReadTemplater('DetailedPlayers', $columns, null, 'id', true, true, 'player.php', 'players.php');

echo '<h1>Players</h1>';
echo $read_templater->execute();

include 'footer.html';
