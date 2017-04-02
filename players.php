<?php
include 'html_templaters.php';
include 'header.html';

$columns = array(Column::simpleDisplayColumn('name', 'Name')
                    , Column::simpleDisplayColumn('age', 'Age')
                    , Column::simpleDisplayColumn('height', 'Height')
                    , Column::simpleDisplayColumn('weight', 'Weight')
                    , Column::simpleDisplayColumn('handedness', 'Handedness')
                    , Column::simpleDisplayColumn('position', 'Position')
                    , Column::simpleDisplayColumn('team', 'Team'));

$read_templater = new ReadTemplater('DetailedPlayers', $columns, null, 'id', true, true, 'create-player.php', 'player.php', 'players.php', 'Player');

echo '<h1>Players</h1>';
echo $read_templater->execute();

include 'footer.html';
