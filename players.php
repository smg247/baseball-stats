<?php
include 'html_templaters.php';
include 'header.html';

$column_names = array('name' => 'Name'
                    , 'age' => 'Age'
                    , 'height' => 'Height'
                    , 'weight' => 'Weight'
                    , 'handedness' => 'Handedness'
                    , 'position' => 'Position'
                    , 'team' => 'Team');

$read_templater = new ReadTemplater('DetailedPlayers', $column_names, null, 'id', true, true, 'player.php');

echo '<h1>Players</h1>';
echo $read_templater->execute();

include 'footer.html';
